import React, { Component } from 'react';
import { BrowserRouter as Router, Route, NavLink, Link, Switch, Redirect } from "react-router-dom";
import './App.css';
import Articles from './views/Articles.jsx';
import Article from './views/Article.jsx';

const baseUrl = '/adminapp';
const apiBaseUrl = '/api/v1';

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
        let url = apiBaseUrl + '/articles';
        let params = new URLSearchParams(window.location.search);
        if (window.location.search) {
            url += window.location.search;
        }
        this.setLoading(true);
        return fetch(url, {credentials: 'same-origin'})
            .then(response => {
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
                        // TODO: not found handling
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
                this.setLoading(false, error.message);
                throw error;
            })
    }

    loadArticle = (id) => {
        let url = apiBaseUrl + '/article/' + id;
        this.setLoading(true);
        return fetch(url, {credentials: 'same-origin'})
            .then(response => {
                switch (response.status) {
                    case 200:
                        return response.json();
                    case 403:
                        return window.location = "/login";
                        // TODO: not found handling
                    default:
                        throw new Error('Network response was not ok');
                }
            })
            .then(data => {
                let articleData = this.state.articles.data;
                articleData[id] = data;

                this.setState({
                    articles: Object.assign({}, this.state.articles, {
                        data: articleData
                    })
                });
                this.setLoading(false);
            })
            .catch(error => {
                this.setLoading(false, error.message);
                throw error;
            })
    }

    handleArticleFormChange = (id, event) => {
        // console.log('change', id, event.target);

        const value = event.target.type === 'checkbox' ? event.target.checked : event.target.value;

        let articleData = this.state.articles.data;
        articleData[id][event.target.name] = value;

        this.setState({
            articles: Object.assign({}, this.state.articles, {
                data: articleData
            })
        });
    }

    updateArticle = (id, data) => {
        let url = apiBaseUrl + '/article/' + id;
        this.setLoading(true);
        return fetch(url, {credentials: 'same-origin', method: 'PUT', body: JSON.stringify(this.state.articles.data[id])})
            .then(response => {
                switch (response.status) {
                    case 200:
                    case 401:
                        return response.json();
                    case 403:
                        return window.location = "/login";
                        // TODO: not found handling
                    default:
                        throw new Error('Network response was not ok');
                }
            })
            .then(response => {
                console.log('update', response);
                let articleData = this.state.articles.data;
                articleData[id] = response;

                this.setState({
                    articles: Object.assign({}, this.state.articles, {
                        data: articleData
                    })
                });
                this.setLoading(false);
            })
            .catch(error => {
                this.setLoading(false, error.message);
                throw error;
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
                            <NavLink to="/?page=2">Articles page 2</NavLink>
                            <NavLink to="/article/create">Create new</NavLink>
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
                            {...routeProps}
                            data={this.state.articles.data[routeProps.match.params.id]}
                            loadData={this.loadArticle}
                            updateArticle={this.updateArticle}
                            handleArticleFormChange={this.handleArticleFormChange}
                            />} />
                        <Route render={() => <main><h1>Not found</h1></main>} />
                    </Switch>
                </div>
            </Router>
        );
    }
}

export default App;
