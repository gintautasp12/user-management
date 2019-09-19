import React from 'react';

const UserList = ({ users, onRemove }) => (
    <div className="list">
        <ul className="list-group-flush">
            {users.map(user => (
                <li
                    key={user.id}
                    className="list-group-item d-flex justify-content-between align-items-center"
                >
                    <p className="m-0">{user.name}</p>
                    <button
                        onClick={() => onRemove(user.id)}
                        className="btn btn-sm btn-outline-danger">
                        Remove
                    </button>
                </li>
            ))}
        </ul>
    </div>
);

export default React.memo(UserList);
