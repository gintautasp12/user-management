import React from 'react';
import {Link} from 'react-router-dom';
import clsx from 'clsx';
import {ROUTES} from '../../config';

class Nav extends React.PureComponent {
    constructor(props) {
        super(props);
        this.state = {
            activePage: '/admin',
        }
    }

    handlePageChange(page) {
        this.setState({ activePage: page });
    }

    render() {
        const { activePage } = this.state;

        return (
            <nav className="d-flex justify-content-around bg-light align-items-center p-4">
                <div>Admin Panel</div>
                <div className="d-flex">
                    {ROUTES.map(route => (
                        <div
                            key={route.link}
                            className={clsx('mr-4', activePage === route.link ? 'bold' : '')}>
                            <Link
                                onClick={() => this.handlePageChange(route.link)}
                                to={route.link}>
                                {route.title}
                            </Link>
                        </div>
                    ))}
                </div>
                <div>
                    <a className="text-decoration-none text-danger" href="/logout">Logout</a>
                </div>
            </nav>
        )
    }
}

export default Nav;
