import React from 'react';

const TeamUserList = ({ team, onRemove }) => (
    <div className="list">
        <ul className="list-group-flush">
            {team.users.map(user => (
                <li
                    key={user.id}
                    className="list-group-item d-flex justify-content-between align-items-center"
                >
                    <p className="m-0">{user.name}</p>
                    <button
                        onClick={() => onRemove(team.id, user.id)}
                        className="btn btn-sm btn-outline-danger">
                        Remove
                    </button>
                </li>
            ))}
        </ul>
    </div>
);

export default React.memo(TeamUserList);
