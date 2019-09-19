import React from 'react';
import axios from 'axios';

class TeamManagementContainer extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            teams: []
        }
    }

    componentDidMount() {
        axios.get('/rest/v1/teams')
            .then(res => {
                this.setState({
                    teams: res.data.data
                });
            })
            .catch(err => console.log(err));
    }

    render() {
        return (
            <main className="team-container">
                <aside className="team-container--half">
                    <h5>Add new team</h5>
                    <div className="form-group">
                        <div className="input-container">
                            <input type="text" className="form-control" placeholder="Enter title"/>
                            <button className="btn btn-primary">Add</button>
                        </div>
                    </div>
                    <div className="list">
                        <ul className="list-group-flush">
                            {this.state.teams.map(team => (
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
