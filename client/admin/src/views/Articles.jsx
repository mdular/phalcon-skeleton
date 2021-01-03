import React, { Component } from 'react';
import {Link, NavLink} from "react-router-dom";

export default class Articles extends Component {
    componentDidMount() {
        if (this.props.data.currentPage === null) {
            this.props.loadData();
        }
    }

    componentDidUpdate(prevProps) {
        if(prevProps.location.search !== this.props.location.search) {
            this.props.loadData();
        }
    }

    render() {
        if (this.props.data.currentPage === null) return null;

        if (this.props.data.count === 0) return (
            <main>no articles yet!</main>
        );

        let pages = [];
        for(let page = 1; page <= this.props.data.pages; page++){
            pages.push(<NavLink key={page} to={"/?page=" + page} isActive={() => page===this.props.data.currentPage}>{page}</NavLink>);
        }

        return (
            <main>
                <Link to="/article/draft">Create new</Link>
                {this.props.data.pages > 1 &&
                    <nav className="pager">
                        Pages: {pages}
                    </nav>
                }
                {this.props.data.list.map(item => {
                    return (
                        <article key={item.id}>
                            <h3>
                                <Link to={"/article/" + item.id}>{item.title}</Link>
                            </h3>
                            {item.state} {item.published_at}
                        </article>
                    );
                })}
                {this.props.data.pages > 1 &&
                    <nav className="pager">
                        Pages: {pages}
                    </nav>
                }
            </main>
        );
    }
}
