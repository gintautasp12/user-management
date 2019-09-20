import React from 'react';
import axios from 'axios';
import {REST_TEAMS, REST_USERS} from '../config';
import TeamList from '../components/List/TeamList';
import UserList from '../components/List/UserList';
import TeamDescription from '../components/TeamInfo/TeamDescription';
import UserAssignForm from '../components/Form/UserAssignForm';
import CollapseToggleButton from '../components/UI/Button/CollapseToggleButton';
import InputForm from '../components/Form/InputForm';

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
            <main className="d-flex w-100 mt-md-5 p-md-2 col-lg-8 col-md-10 m-auto mt-md-5 team-container">
                <aside className="d-flex flex-column p-4 aside">
                    <h5>Create new team</h5>
                    <InputForm
                        errors={errors}
                        inputValue={title}
                        submitText="Create"
                        placeholder="Enter team title"
                        onSubmit={() => this.handleAdd()}
                        onChange={(e) => this.handleTitleChange(e)}/>
                    <TeamList
                        teams={teams}
                        onDelete={(e, id) => this.handleTeamDelete(e, id)}
                        onSelect={(id) => this.handleTeamSelect(id)}
                    />
                </aside>
                <aside className="d-flex flex-column aside">
                    {selectedTeam.id && (
                        <div className="bg-light h-100 p-4">
                            <TeamDescription team={selectedTeam}/>
                            <div>
                                <UserList
                                    team={selectedTeam}
                                    onRemove={(team, user) => this.handleUserRemove(team, user)}/>
                                <CollapseToggleButton
                                    onClick={() => this.fetchUsers()}
                                    target="#addForm"
                                    ariaControls="addForm">Assign user
                                </CollapseToggleButton>
                                <UserAssignForm
                                    users={filteredUsers}
                                    onInputChange={(e) => this.handleUserFieldChange(e)}
                                    onSelect={(user) => this.handleUserSelect(user)}
                                    onSubmit={() => this.handleAddUser()}
                                    inputValue={userFieldValue}/>
                            </div>
                        </div>
                    )}
                </aside>
            </main>
        )
    }
}

export default TeamManagementContainer;
