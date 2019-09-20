import React from 'react';

const UserAssignForm = ({
    inputValue,
    users,
    onSubmit,
    onSelect,
    onInputChange,
}) => (
    <div className="collapse" id="addForm">
        <input
            onChange={e => onInputChange(e)}
            value={inputValue}
            type="text"
            className="form-control mt-3"
            placeholder="Search for name"
        />
        <select multiple className="form-control mt-3">
            {users.map(user => (
                <option
                    onClick={() => onSelect(user)}
                    key={user.id}
                    value={user.id}>
                    {user.name}
                </option>
            ))}
        </select>
        <button
            onClick={() => onSubmit()}
            className="btn btn-primary mt-3">
            Assign
        </button>
    </div>
);

export default React.memo(UserAssignForm);
