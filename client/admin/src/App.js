import React, { Component } from 'react';
import { BrowserRouter as Router, Route, NavLink, Link, Switch, Redirect } from "react-router-dom";
import './App.css';
import Articles from './views/Articles.jsx';
import Article from './views/Article.jsx';

const baseUrl = '/adminapp';

class App extends Component {
    constructor(props) {
        super(props);
        this.state = {
            loading: false,
            loadingError: null,
            articles: {
                currentPage: null,
                pages: null,
                count: null,
                list: [],
                data: {}
            }
        };
    }

    setLoading(state, message = null) {
        this.setState({
            loading: state,
            loadingError: message
        });
    }

    loadArticleList = () => {
        let url = '/api/v1/articles';
        let params = new URLSearchParams(window.location.search);
        if (window.location.search) {
            url += window.location.search;
        }
        this.setLoading(true);
        return fetch(url, {credentials: 'same-origin'})
            .then(response => {
                // console.log(response);
                switch (response.status) {
                    case 200:
                        this.setState({
                            articles: Object.assign({}, this.state.articles, {
                                count: response.headers.get('x-count'),
                                pages: response.headers.get('x-pages')
                            })
                        });
                        return response.json();
                    case 403:
                        return window.location = "/login";
                    default:
                        throw new Error('Network response was not ok');
                }
            })
            .then(data => {
                this.setState({
                    articles: Object.assign({}, this.state.articles, {
                        list: data,
                        currentPage: params.get('page') || 1
                    })
                });
                this.setLoading(false);
            })
            .catch(error => {
                this.setLoading(false, error.message)
                throw error; // TODO: error handling
            })
    }

    render() {
        return (
            <Router basename={baseUrl}>
                <div id="adminapp">
                    <header className="app_header">
                        <span className={"app_header__indicator" + (this.state.loading ? ' loading' : '') + (this.state.loadingError ? ' error' : '')}>{this.state.loadingError}</span>
                        <h2 id="logo">
                            <Link to="/">A Great Admin</Link>
                        </h2>
                        <nav>
                            <NavLink to="/">Articles</NavLink>
                            <a href="/logout">Logout</a>
                        </nav>
                    </header>

                    {(window.location.pathname.indexOf(baseUrl) === -1) &&
                        <Redirect to="/" />
                    }

                    <Switch>
                        <Route exact path="/" render={routeProps => <Articles
                            {...routeProps}
                            data={this.state.articles}
                            loadData={this.loadArticleList}
                            />} />
                        <Route path="/article/:id" render={routeProps => <Article
                            {...routeProps} />} />
                        <Route render={() => <main><h1>Not found</h1></main>} />
                    </Switch>
                </div>
            </Router>
        );
    }
}

export default App;
