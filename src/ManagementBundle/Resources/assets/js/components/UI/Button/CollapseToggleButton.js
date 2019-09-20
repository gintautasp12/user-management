import React from 'react';

const CollapseToggleButton = ({
    onClick,
    children,
    ariaControls,
    target
}) => (
    <button
        onClick={() => onClick()}
        data-toggle="collapse"
        type="button"
        data-target={target}
        aria-controls={ariaControls}
        aria-expanded="false"
        className="btn btn-outline-secondary">
        {children}
    </button>
);

export default React.memo(CollapseToggleButton);
