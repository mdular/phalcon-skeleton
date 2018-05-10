import React, { Component } from 'react';
import { Link } from "react-router-dom";

export default class Articles extends Component {
    render() {
        return (
            <main>
                {this.props.data.list.map(item => {
                    return (
                        <article key={item.id}>
                            <Link to={"/article/" + item.id}>View</Link>
                            <h3>{item.title}</h3>
                            {item.content}
                        </article>
                    );
                })}
            </main>
        )
    }
}
