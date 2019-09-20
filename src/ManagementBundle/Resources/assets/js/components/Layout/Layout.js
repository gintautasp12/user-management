import React from 'react';
import Nav from '../Nav/Nav';

const Layout = ({ children }) => (
    <>
        <Nav/>
        {children}
    </>
);

export default React.memo(Layout);
