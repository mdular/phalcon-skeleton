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
        // console.log(this.props)
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
                {this.props.data.pages > 1 &&
                    <nav className="pager">
                        Pages: {pages}
                    </nav>
                }
                {this.props.data.list.map(item => {
                    return (
                        <article key={item.id}>
                            <Link to={"/article/" + item.id}>View</Link>
                            <h3>{item.title}</h3>
                            {item.state}
                            {/*JSON.stringify(item, 0, 2)*/}
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
