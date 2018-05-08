import React, { Component } from 'react';
import { BrowserRouter as Router, Route, NavLink, Link, Switch } from "react-router-dom";
import './App.css';

class App extends Component {
    render() {
        return (
            <Router>
                <div id="adminapp">
                    <header className="app_header">
                        <h2 id="logo">
                            <Link to="/">A Great Admin</Link>
                        </h2>
                        <nav>
                            <NavLink to="/">Articles</NavLink>
                            <NavLink to="/logout">Logout</NavLink>
                        </nav>
                    </header>

                    <hr />

                    <Switch>
                        <Route render={() => <h1>Not found</h1>} />
                    </Switch>
                </div>
            </Router>
        );
    }
}

export default App;
