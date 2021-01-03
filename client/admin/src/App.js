import React, { Component } from 'react';
import { BrowserRouter as Router, Route, NavLink, Link, Switch, Redirect } from "react-router-dom";
import './App.css';
import Articles from './views/Articles.jsx';
import Article from './views/Article.jsx';

const baseUrl = '/adminapp';
const apiBaseUrl = '/api/v1';

const articleDraft = {
    url: null,
    title: null,
    excerpt: null,
    content: null,
    content_type: "markdown",
    tags: null,
    state: "unpublished",
    published_at: null
};

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
                data: {draft: articleDraft}
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
                                count: parseInt(response.headers.get('x-count'), 10),
                                pages: parseInt(response.headers.get('x-pages'), 10)
                            })
                        });
                        return response.json();
                    case 403:
                        throw new Error('Not authorized');
                    case 404:
                        // TODO: not found handling
                        throw new Error('Not found');
                    default:
                        throw new Error('Network response was not ok');
                }
            })
            .then(data => {
                this.setState({
                    articles: Object.assign({}, this.state.articles, {
                        list: data,
                        currentPage: parseInt(params.get('page'), 10) || 1
                    })
                });
                this.setLoading(false);
            })
            .catch(error => {
                this.setLoading(false, error.message);
                throw error;
            })
    };

    loadArticle = (id) => {
        let url = apiBaseUrl + '/article/' + id;
        this.setLoading(true);
        return fetch(url, {credentials: 'same-origin'})
            .then(response => {
                switch (response.status) {
                    case 200:
                    case 400:
                        return response.json();
                    case 403:
                        throw new Error('Not authorized');
                    case 404:
                        // TODO: not found handling
                        throw new Error('Not found');
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
    };

    handleArticleFormChange = (id, event) => {
        let target, value;

        if (typeof event.target === 'undefined') {
            target = 'content';
            value = event;
        } else {
            target = event.target.name;
            value = event.target.type === 'checkbox' ? event.target.checked : event.target.value;
        }

        let articleData = this.state.articles.data;
        articleData[id][target] = value;

        this.setState({
            articles: Object.assign({}, this.state.articles, {
                data: articleData
            })
        });
    };

    updateArticle = (id) => {
        let url = apiBaseUrl + '/article/' + id;
        this.setLoading(true);
        return fetch(url, {credentials: 'same-origin', method: 'PUT', body: JSON.stringify(this.state.articles.data[id])})
            .then(response => {
                switch (response.status) {
                    case 200:
                    case 400:
                        return response.json();
                    case 403:
                        throw new Error('Not authorized');
                    case 404:
                        // TODO: not found handling
                        throw new Error('Not found');
                    default:
                        throw new Error('Network response was not ok');
                }
            })
            .then(response => {
                this.setState({
                    articles: Object.assign({}, this.state.articles, {
                        data: {[id]: response}
                    })
                });
                this.setLoading(false);
            })
            .catch(error => {
                this.setLoading(false, error.message);
                throw error;
            })
    };

    createArticle = (id) => {
        let url = apiBaseUrl + '/article';
        this.setLoading(true);
        return fetch(url, {credentials: 'same-origin', method: 'POST', body: JSON.stringify(this.state.articles.data[id])})
            .then(response => {
                switch (response.status) {
                    case 201:
                        this.setState({articles: Object.assign({}, this.state.articles, {data: {draft: articleDraft}})});
                        return window.location = baseUrl + '/article/' + new URL(response.headers.get('Location')).pathname.split('/').pop();
                    case 400:
                        return response.json();
                    case 403:
                        throw new Error('Not authorized');
                    default:
                        throw new Error('Network response was not ok');
                }
            })
            .then(response => {
                this.setState({articles: Object.assign({}, this.state.articles, {data: {[id]: response}})});
            })
            .catch(error => {
                this.setLoading(false, error.message);
                throw error;
            })
    };

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
                            <a href={"/logout"}>Logout</a>
                            <Link to="/article/draft">Create new</Link>
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
                        <Route exact path="/article/:id(\d+)" render={routeProps => <Article
                            {...routeProps}
                            data={this.state.articles.data[routeProps.match.params.id]}
                            loadData={this.loadArticle}
                            updateArticle={this.updateArticle}
                            handleArticleFormChange={this.handleArticleFormChange}
                        />} />
                        <Route exact path="/article/:id(draft)" render={routeProps => <Article
                            {...routeProps}
                            data={this.state.articles.data.draft}
                            updateArticle={this.createArticle}
                            handleArticleFormChange={this.handleArticleFormChange}
                        />} />
                        }
                        <Route render={() => <main><h1>Not found</h1></main>} />
                    </Switch>
                </div>
            </Router>
        );
    }
}

export default App;
