import React from 'react';

const Footer: React.FC = () => {
    return (
        <footer style={{ textAlign: 'center', padding: '1rem', background: '#f1f1f1' }}>
            <p>&copy; {new Date().getFullYear()} TechBook. All rights reserved.</p>
            <p>
                <a href="/about">About Us</a> | <a href="/contact">Contact</a> | <a href="/privacy">Privacy Policy</a>
            </p>
        </footer>
    );
};

export default Footer;