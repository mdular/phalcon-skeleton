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
                list: [
                    {id: 1, title: "Totally an article", content: "Full of content"},
                    {id: 2, title: "Another one", content: "Full of other content"}
                ]
            }
        };
    }

    setLoading(state, message = null) {
        this.setState({
            loading: state,
            loadingError: message
        });
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
