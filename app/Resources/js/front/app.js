import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router } from "react-router-dom";

import Navigation from './navigation';

class App extends React.Component {
    constructor() {
        super();
    }

    componentDidMount() {

    }

    render() {
        return (
            <Router>
                <div className="container">
                    <Navigation />
                </div>
            </Router>
        );
    }
}

ReactDOM.render(<App />, document.getElementById('root'));
