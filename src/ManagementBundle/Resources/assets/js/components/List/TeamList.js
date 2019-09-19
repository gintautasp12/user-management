import React from 'react';

const TeamList = ({ teams, onDelete }) => (
    <div className="list">
        <ul className="list-group-flush">
            {teams.map(team => (
                <li key={team.id} className="list-group-item d-flex justify-content-between align-items-center">
                    <p className="m-0">{team.title}</p>
                    <span>Members: {team.users.length}</span>
                    <button
                        disabled={team.users.length}
                        onClick={() => onDelete(team.id)}
                        className="btn btn-sm btn-outline-danger">
                        Delete
                    </button>
                </li>
            ))}
        </ul>
    </div>
);

export default React.memo(TeamList);
