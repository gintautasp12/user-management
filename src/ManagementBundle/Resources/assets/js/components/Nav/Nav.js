import React from 'react';
import {Link} from 'react-router-dom';

const Nav = () => (
    <nav className="d-flex justify-content-around bg-light align-items-center p-4">
        <div>Admin Panel</div>
        <div>
            <Link to="/users">Users</Link>
            <Link to="/admin">Teams</Link>
        </div>
        <div>
            <a className="text-decoration-none text-danger" href="/logout">Logout</a>
        </div>
    </nav>
);

export default React.memo(Nav);
