import React from 'react';

const TeamDescription = ({ team }) => (
    <div className="team-info">
        <div>
            <h5 className="d-inline">Team: </h5>
            <p className="d-inline">{team.title}</p>
        </div>
        <div className="mb-3">
            <p className="d-inline">Members: </p>
            <span>{team.users.length}</span>
        </div>
    </div>
);

export default React.memo(TeamDescription);
