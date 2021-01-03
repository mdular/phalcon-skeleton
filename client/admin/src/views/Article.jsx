/*eslint no-undef: 0 */

import React, { Component } from 'react';
import SimpleMDE from "react-simplemde-editor";
import "easymde/dist/easymde.min.css";

export default class Article extends Component {
    constructor(props) {
        super(props);
        this.state = {
            submitDisabled: false,
        };

        this.mdeInstance = null;

        if (!this.props.data) {
            this.props.loadData(this.props.match.params.id);
        }
    }

    componentDidUpdate(prevProps, prevState, snapshot) {
        if(this.props.match.params.id !== prevProps.match.params.id && !this.props.data) {
            this.props.loadData(this.props.match.params.id);
        }

        if(this.mdeInstance !== null && this.props.data && this.props.match.params.id !== prevProps.match.params.id && this.props.data.content) {
            this.mdeInstance.value(this.props.data.content);
        }
    }

    onSubmit = (event) => {
        event.preventDefault();

        let state = this.state;
        delete state.submitDisabled;

        this.props.updateArticle(this.props.match.params.id, state)
            .then(result => {
            })
            .catch(err => {
                throw err;
            })
            .finally(() => {
                this.setState({submitDisabled: false});
            });

        this.setState({submitDisabled: true});
    }

    onChange = (event) => {
        this.props.handleArticleFormChange(this.props.match.params.id, event);
    }

    getMdeInstance = instance => {
        this.mdeInstance = instance;
    }

    render() {
        if (!this.props.data) {
            return null;
        }

        const messages = this.props.data.messages || [];

        return (
            <React.Fragment>
                <main className="clearfix">
                    {this.props.data.messages &&
                    <p className="form__errorheader">
                        Validation failed. Please resolve the errors below and try again.
                    </p>
                    }

                    <form id="article-form" className="clearfix" onSubmit={this.onSubmit} onChange={this.onChange}>
                        <TextInput name="title" label="Title" value={this.props.data.title} message={messages['title']} />

                        <TextInput name="url" label="Url" value={this.props.data.url} message={messages['url']} />

                        <TextArea name="excerpt" label="Excerpt" value={this.props.data.excerpt} message={messages['excerpt']} />

                        {this.props.data.content_type === 'markdown' &&
                            <MarkdownEditor name="content" label="Content"
                                            value={this.props.data.content}
                                            message={messages['content']}
                                            onChange={this.onChange}
                                            getMdeInstance={this.getMdeInstance}
                            />
                        }
                        {this.props.data.content_type === 'html' &&
                            <TextArea name="content" label="Content" value={this.props.data.content} message={messages['content']} />
                        }

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

                    </form>
                </main>
            </React.Fragment>
        )
    }
}

class MarkdownEditor extends Component {
    render () {
        return (
            <FormElement name={this.props.name} label={this.props.label} message={this.props.message}>
                <SimpleMDE id={this.props.name} name={this.props.name} value={this.props.value || ''} onChange={this.props.onChange} getMdeInstance={this.props.getMdeInstance} />
            </FormElement>
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
            <div id={'form__element_' + this.props.name} className={'form__element' + (this.props.message ? ' form__error' : '')}>
                {this.props.label && <label htmlFor={this.props.name}>{this.props.label}</label>}
                {this.props.children}
                {this.props.message && <div className="form__element__message">{this.props.message}</div>}
            </div>
        )
    }
}
