import React from 'react';
import axios from 'axios';
import {REST_TEAMS} from '../config';

class TeamManagementContainer extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            teams: [],
            title: '',
            errors: [],
        };
    }

    componentDidMount() {
        axios.get(REST_TEAMS)
            .then(res => this.setState({
                teams: res.data.data
            }))
            .catch(err => console.log(err.response));
    }

    handleAdd() {
        const { title, teams } = this.state;
        axios.post(REST_TEAMS, { title })
            .then(res => this.setState({
                teams: [...teams, res.data.data],
                title: '',
            }))
            .catch(err => console.log(err.response));
    }

    handleTitleChange(e) {
        this.setState({
            title: e.target.value
        });
    }

    render() {
        const { teams, title } = this.state;

        return (
            <main className="team-container">
                <aside className="team-container--half">
                    <h5>Add new team</h5>
                    <div className="form-group">
                        <div className="input-container">
                            <input
                                onChange={e => this.handleTitleChange(e)}
                                type="text"
                                className="form-control"
                                value={title}
                                placeholder="Enter title"/>
                            <button onClick={() => this.handleAdd()} className="btn btn-primary">Add</button>
                        </div>
                    </div>
                    <div className="list">
                        <ul className="list-group-flush">
                            {teams.map(team => (
                                <li key={team.id} className="list-group-item">{team.title}</li>
                            ))}
                        </ul>
                    </div>
                </aside>
                <aside className="team-container--half"></aside>
            </main>
        )
    }
}

export default TeamManagementContainer;
