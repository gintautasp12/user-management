import React from 'react';
import axios from 'axios';
import {REST_TEAMS, REST_USERS} from '../config';
import ErrorMessages from '../components/ErrorMessage/ErrorMessages';
import TeamList from '../components/List/TeamList';
import UserList from '../components/List/UserList';

class TeamManagementContainer extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            teams: [],
            title: '',
            errors: [],
            selectedTeam: { users: [] },
            users: [],
            filteredUsers: [],
            selectedUser: {},
            userFieldValue: '',
        };
    }

    componentDidMount() {
        this.fetchTeams();
    }

    fetchTeams() {
        axios.get(REST_TEAMS)
            .then(res => this.setState({
                teams: res.data.data
            }))
            .catch(err => console.log(err.response));
    }

    handleAdd() {
        const { title, teams } = this.state;
        this.setState({ errors: [] });
        axios.post(REST_TEAMS, { title })
            .then(res => this.setState({
                teams: [...teams, res.data.data],
                title: '',
            }))
            .catch(err => this.setState({
                errors: err.response.data.errors,
            }));
    }

    handleTitleChange(e) {
        this.setState({
            title: e.target.value
        });
    }

    handleTeamDelete(e, id) {
        e.stopPropagation();
        const { teams } = this.state;
        this.setState({ errors: [] });
        axios.delete(`${REST_TEAMS}/${id}`)
            .then(res => this.setState({
                teams: teams.filter(team => team.id !== id),
                selectedTeam: { users: [] },
            }))
            .catch(err => this.setState({ errors: [err.response.data.errors] }));
    }

    handleTeamSelect(id) {
        const { selectedTeam } = this.state;
        if (selectedTeam.id === id) return;
        this.setState({ errors: [] });
        this.fetchTeam(id);
    }

    fetchTeam(id) {
        axios.get(`${REST_TEAMS}/${id}`)
            .then(res => this.setState({
                selectedTeam: res.data.data,
            }))
            .catch(err => this.setState({ errors: [err.response.data.errors] }));
    }

    handleUserRemove(team, user) {
        this.setState({ errors: [] });
        axios.delete(`${REST_TEAMS}/${team}/users/${user}`)
            .then(res => this.setState({
                    selectedTeam: res.data.data,
                }, () => this.fetchTeams()
            ))
            .catch(err => this.setState({ errors: [err.response.data.errors] }));
    }

    handleUserFieldChange(e) {
        const { users, selectedTeam } = this.state;
        this.setState({
            filteredUsers: users.filter(user =>
                user.name.toLowerCase().includes(e.target.value.toLowerCase())
                && !selectedTeam.users.map(user => user.id).includes(user.id)),
            userFieldValue: e.target.value,
        });
    }

    handleAddUser() {
        const { selectedTeam, selectedUser, users } = this.state;
        if (!selectedUser.id) return;
        this.setState({ errors: [] });
        axios.post(`${REST_TEAMS}/${selectedTeam.id}/users/${selectedUser.id}`)
            .then(res => {
                this.setState({
                    userFieldValue: '',
                    filteredUsers: users.filter(
                        user => user.id !== selectedUser.id
                            && !res.data.data.users.map(user => user.id).includes(user.id)
                    ),
                    selectedUser: {},
                });
                this.fetchTeam(selectedTeam.id);
                this.fetchTeams();
            })
            .catch(err => this.setState({ errors: [err.response.data.errors] }));
    }

    fetchUsers() {
        axios.get(REST_USERS)
            .then(res => this.setState({
                users: res.data.data
            }))
            .catch(err => console.log(err.response.data.errors));
    }

    handleUserSelect(user) {
        this.setState({
            selectedUser: user,
        });
    }

    render() {
        const {
            teams,
            title,
            errors,
            selectedTeam,
            filteredUsers,
            userFieldValue
        } = this.state;

        return (
            <main className="team-container col-lg-8 col-md-10 m-auto mt-md-5">
                <aside className="team-box p-4">
                    <h5>Create new team</h5>
                    <div className="form-group">
                        <div className="input-container">
                            <input
                                onChange={e => this.handleTitleChange(e)}
                                type="text"
                                className="form-control mr-3"
                                value={title}
                                placeholder="Enter team title"
                            />
                            <button
                                onClick={() => this.handleAdd()}
                                className="btn btn-primary">
                                Create
                            </button>
                        </div>
                        <ErrorMessages errors={errors}/>
                    </div>
                    <TeamList
                        teams={teams}
                        onDelete={(e, id) => this.handleTeamDelete(e, id)}
                        onSelect={(id) => this.handleTeamSelect(id)}
                    />
                </aside>
                <aside className="team-box">
                    {selectedTeam.id && (
                        <div className="bg-light p-4 h-100">
                            <div className="team-info">
                                <div>
                                    <h5 className="d-inline">Team: </h5>
                                    <p className="d-inline">{selectedTeam.title}</p>
                                </div>
                                <div className="mb-3">
                                    <p className="d-inline">Members: </p>
                                    <span>{selectedTeam.users.length}</span>
                                </div>
                            </div>
                            <div>
                                <UserList
                                    team={selectedTeam}
                                    onRemove={(team, user) => this.handleUserRemove(team, user)}
                                />
                                <button
                                    onClick={() => this.fetchUsers()}
                                    data-toggle="collapse"
                                    type="button"
                                    data-target="#addForm"
                                    aria-controls="addForm"
                                    aria-expanded="false"
                                    className="btn btn-outline-secondary">
                                    Assign user
                                </button>
                                <div className="collapse" id="addForm">
                                    <input
                                        onChange={e => this.handleUserFieldChange(e)}
                                        value={userFieldValue}
                                        type="text"
                                        className="form-control mt-3"
                                        placeholder="Search for name"
                                    />
                                    <select multiple className="form-control mt-3">
                                        {filteredUsers.map(user => (
                                            <option
                                                onClick={() => this.handleUserSelect(user)}
                                                key={user.id}
                                                value={user.id}>
                                                {user.name}
                                            </option>
                                        ))}
                                    </select>
                                    <button
                                        onClick={() => this.handleAddUser()}
                                        className="btn btn-primary mt-3">
                                        Assign
                                    </button>
                                </div>
                            </div>
                        </div>
                    )}
                </aside>
            </main>
        )
    }
}

export default TeamManagementContainer;
