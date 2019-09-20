import React from 'react';
import ReactDOM from 'react-dom';
import 'jquery';
import 'bootstrap';
import TeamManagementContainer from "./containers/TeamManagementContainer";

const App = () => (
    <TeamManagementContainer/>
);

ReactDOM.render(<App/>, document.getElementById('root'));
