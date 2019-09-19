import React from 'react';
import axios from 'axios';
import {REST_TEAMS} from '../config';
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
            }))
            .catch(err => this.setState({ errors: [err.response.data.errors] }));
    }

    handleTeamSelect(id) {
        this.setState({ errors: [] });
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

    render() {
        const { teams, title, errors, selectedTeam } = this.state;

        return (
            <main className="team-container">
                <aside className="team-container--half p-4">
                    <h5>Add new team</h5>
                    <div className="form-group">
                        <div className="input-container">
                            <input
                                onChange={e => this.handleTitleChange(e)}
                                type="text"
                                className="form-control"
                                value={title}
                                placeholder="Enter title"
                            />
                            <button
                                onClick={() => this.handleAdd()}
                                className="btn btn-primary">
                                Add
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
                <aside className="team-container--half p-4">
                    <div className="team-info">
                        <div>
                            <h5 className="d-inline">Team: </h5>
                            <p className="d-inline">{selectedTeam.title}</p>
                        </div>
                        <div>
                            <p className="d-inline">Members: </p>
                            <span>{selectedTeam.users.length}</span>
                        </div>
                    </div>
                    <div>
                        <UserList team={selectedTeam} onRemove={(team, user) => this.handleUserRemove(team, user)}/>
                        <button className="btn btn-outline-secondary">Add a member</button>
                    </div>
                </aside>
            </main>
        )
    }
}

export default TeamManagementContainer;
