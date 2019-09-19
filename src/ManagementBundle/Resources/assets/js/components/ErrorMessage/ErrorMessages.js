import React from 'react';

const ErrorMessages = ({ errors }) =>
    errors.map(error => (
        <span key={error.property} className="text-danger">{error.message}</span>
    ));

export default React.memo(ErrorMessages);
