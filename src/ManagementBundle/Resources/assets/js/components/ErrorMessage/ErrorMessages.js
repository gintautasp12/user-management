import React from 'react';

const ErrorMessages = ({ errors }) =>
    errors.map(error => (
        <span key={error.toString()} className="text-danger">{error.message}</span>
    ));

export default React.memo(ErrorMessages);
