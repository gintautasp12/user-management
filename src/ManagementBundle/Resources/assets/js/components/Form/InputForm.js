import React from 'react';
import ErrorMessages from '../ErrorMessage/ErrorMessages';

const InputForm = ({
    onChange,
    inputValue,
    onSubmit,
    errors,
    placeholder,
    submitText
}) => (
    <div className="form-group">
        <div className="d-flex justify-content-between">
            <input
                onChange={e => onChange(e)}
                type="text"
                className="form-control mr-3 w-75"
                value={inputValue}
                placeholder={placeholder}
            />
            <button
                onClick={() => onSubmit()}
                className="btn btn-primary">
                {submitText}
            </button>
        </div>
        <ErrorMessages errors={errors}/>
    </div>
);

export default React.memo(InputForm);
