import React from 'react';
import axios from 'axios';
import InputForm from '../components/Form/InputForm';
import {REST_USERS} from '../config';
import UserList from '../components/List/UserList';

class UserManagementContainer extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            errors: [],
            users: [],
            name: '',
        }
    }

    componentDidMount() {
        this.fetchUsers();
    }

    fetchUsers() {
        axios.get(REST_USERS)
            .then(res => this.setState({
                users: res.data.data.filter(user =>
                    user.id !== Number(document.getElementById('root').dataset.adminId))
            }))
            .catch(err => this.setState({ errors: [err.response.data.errors] }));
    }

    handleCreateUser() {
        this.resetErrors();
        const { name, users } = this.state;
        axios.post(REST_USERS, { name })
            .then(res => this.setState({
                users: [...users, res.data.data],
                name: '',
            }))
            .catch(err => this.setState({ errors: err.response.data.errors }));
    }

    handleNameChange(e) {
        this.setState({ name: e.target.value });
    }

    handleUserDelete(id) {
        const { users } = this.state;
        this.resetErrors();
        axios.delete(`${REST_USERS}/${id}`)
            .then(res => this.setState({
                users: users.filter(user => user.id !== id),
            }))
            .catch(err => this.setState({ errors: [err.response.data.errors] }));
    }

    resetErrors() {
        this.setState({ errors: [] });
    }

    render() {
        const { errors, name, users } = this.state;

        return (
            <main className="d-flex w-100 mt-md-5 p-md-2 col-lg-8 col-md-10 m-auto mt-md-5 team-container">
                <aside className="d-flex flex-column p-4 aside">
                    <h5>Add new user</h5>
                    <InputForm
                        errors={errors}
                        inputValue={name}
                        submitText="Add"
                        placeholder="Enter user's name"
                        onSubmit={() => this.handleCreateUser()}
                        onChange={(e) => this.handleNameChange(e)}/>
                    <UserList
                        users={users}
                        onDelete={(id) => this.handleUserDelete(id)}
                    />
                </aside>
                <aside className="d-flex flex-column aside">
                </aside>
            </main>
        );
    }
}

export default UserManagementContainer;
