import React from 'react';
import ReactDOM from 'react-dom';
import 'jquery';
import 'bootstrap';
import TeamManagementContainer from './containers/TeamManagementContainer';
import {BrowserRouter, Route} from 'react-router-dom';
import UserManagementContainer from './containers/UserManagementContainer';
import Layout from './components/Layout/Layout';

const App = () => (
    <BrowserRouter>
        <Layout>
            <Route path="/admin" component={TeamManagementContainer}/>
            <Route path="/users" component={UserManagementContainer}/>
        </Layout>
    </BrowserRouter>
);

ReactDOM.render(<App/>, document.getElementById('root'));
