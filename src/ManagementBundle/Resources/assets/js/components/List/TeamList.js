import React from 'react';

const TeamList = ({ teams, onDelete, onSelect }) => (
    <div className="list">
        <ul className="list-group-flush">
            {teams.map(team => (
                <li
                    key={team.id}
                    onClick={() => onSelect(team.id)}
                    className="list-group-item d-flex justify-content-between align-items-center hoverable"
                >
                    <p className="m-0">{team.title}</p>
                    <span>Members: {team.users.length}</span>
                    <button
                        disabled={team.users.length}
                        onClick={(e) => onDelete(e, team.id)}
                        className="btn btn-sm btn-outline-danger">
                        Delete
                    </button>
                </li>
            ))}
        </ul>
    </div>
);

export default React.memo(TeamList);
