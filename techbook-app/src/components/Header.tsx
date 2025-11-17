import React from 'react';
import { Link } from 'react-router-dom';
import './Header.css'; // Assuming you have a CSS file for styling

const Header: React.FC = () => {
    return (
        <header className="header">
            <h1 className="site-title">TechBook Exchange</h1>
            <nav className="navigation">
                <ul>
                    <li><Link to="/">Home</Link></li>
                    <li><Link to="/reviews">Reviews</Link></li>
                    <li><Link to="/discussions">Discussions</Link></li>
                    <li><Link to="/profile">Profile</Link></li>
                    <li><Link to="/about">About</Link></li>
                </ul>
            </nav>
        </header>
    );
};

export default Header;