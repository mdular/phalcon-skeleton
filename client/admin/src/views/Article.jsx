import React, { Component } from 'react';

export default class Article extends Component {
    constructor(props) {
        super(props);
        this.state = {submitDisabled: false};
    }

    componentDidMount() {
        if (!this.props.data) {
            this.props.loadData(this.props.match.params.id);
        }
    }

    onSubmit = (event) => {
        console.log("submit", this.state);
        event.preventDefault();

        let state = this.state;
        delete state.submitDisabled;

        this.props.updateArticle(this.props.data.id, state)
            .then(result => {
                this.setState({submitDisabled: false});
            })
            .catch(err => {
                this.setState({submitDisabled: false});
                throw err;
            });

        // this.setState(null);
        this.setState({submitDisabled: true});
    }

    onChange = (event) => {
        this.props.handleArticleFormChange(this.props.data.id, event);
    }

    render() {
        if (!this.props.data) {
            return null;
        }

        let messages = this.props.data.messages || [];

        // console.log(this.props);

        return (
            <main>
                {this.props.data.messages &&
                <p className="form__errorheader">
                    Validation failed. Please resolve the errors below and try again.
                </p>
                }

                <form id="article-form" onSubmit={this.onSubmit} onChange={this.onChange}>
                    <TextInput name="title" label="Title" value={this.props.data.title} message={messages['title']} />

                    <TextInput name="url" label="Url" value={this.props.data.url} message={messages['url']} />

                    <TextArea name="excerpt" label="Excerpt" value={this.props.data.excerpt} message={messages['excerpt']} />

                    <TextArea name="content" label="Content" value={this.props.data.content} message={messages['content']} />

                    <Select name="content_type" label="Content type"
                        value={this.props.data.content_type}
                        options={[
                            {value: "markdown", label: "Markdown"},
                            {value: "html", label: "HTML"}
                        ]}
                        message={messages['content_type']} />

                    <TextInput name="tags" label="Tags" value={this.props.data.tags} message={messages['tags']} />

                    <Select name="state" label="State"
                        value={this.props.data.state}
                        options={[
                            {value: "unpublished", label: "Unpublished"},
                            {value: "published", label: "Published"}
                        ]}
                        message={messages['state']} />

                    <DateInput name="published_at" label="Published at" value={this.props.data.published_at} message={messages['published_at']} />

                    <Submit value="Submit" disabled={this.props.data.submitDisabled} />

                    <pre>
                    {/*JSON.stringify(this.props.data, 0, 2)*/}
                    </pre>
                </form>
            </main>
        )
    }
}

class TextInput extends Component {
    render () {
        return (
            <FormElement name={this.props.name} label={this.props.label} message={this.props.message}>
                <input id={this.props.name} type="text" name={this.props.name} value={this.props.value || ''} onChange={() => {return true;}} />
            </FormElement>
        )
    }
}

class TextArea extends Component {
    render () {
        return (
            <FormElement name={this.props.name} label={this.props.label} message={this.props.message}>
                <textarea id={this.props.name} name={this.props.name} value={this.props.value || ''} onChange={() => {return true;}} />
            </FormElement>
        )
    }
}

class Select extends Component {
    renderOptions (options) {
        return options.map(item => {
            return (
                <option key={item.value} value={item.value}>{item.label}</option>
            )
        })
    }

    render () {
        return (
            <FormElement name={this.props.name} label={this.props.label} message={this.props.message}>
                <select id={this.props.name} name={this.props.name} value={this.props.value} onChange={() => {return true;}}>
                    {this.renderOptions(this.props.options)}
                </select>
            </FormElement>
        )
    }
}

class DateInput extends Component {
    render () {
        return (
            <FormElement name={this.props.name} label={this.props.label} message={this.props.message}>
                <input id={this.props.name} type="text" name={this.props.name} value={this.props.value || ''} onChange={() => {return true;}} />
            </FormElement>
        )
    }
}

class Submit extends Component {
    render () {
        return (
            <FormElement>
                <input type="submit" value={this.props.value} disabled={this.props.disabled} />
            </FormElement>
        )
    }
}

class FormElement extends Component {
    render () {
        return (
            <div className={'form__element' + (this.props.message ? ' form__error' : '')}>
                {this.props.label && <label htmlFor={this.props.name}>{this.props.label}</label>}
                {this.props.children}
                {this.props.message && <div className="form__element__message">{this.props.message}</div>}
            </div>
        )
    }
}
