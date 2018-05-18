import React, { Component } from 'react';

export default class Article extends Component {
    componentDidMount() {
        console.log('mount!');
        if (!this.props.data) {
            this.props.loadData(this.props.match.params.id);
        }
    }

    render() {
        return (
            <main>
                <article>
                    <pre>
                    {JSON.stringify(this.props.data, 0, 2)}
                    </pre>
                </article>
            </main>
        )
    }
}
