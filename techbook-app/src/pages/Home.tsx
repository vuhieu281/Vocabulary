import React from 'react';
import Header from '../components/Header';
import Footer from '../components/Footer';
import ReviewList from '../components/ReviewList';
import DiscussionBoard from '../components/DiscussionBoard';

const Home: React.FC = () => {
    return (
        <div>
            <Header />
            <main>
                <h1>Welcome to the TechBook Exchange</h1>
                <section>
                    <h2>Featured Services</h2>
                    {/* Placeholder for featured services */}
                </section>
                <section>
                    <h2>Recent Reviews</h2>
                    <ReviewList />
                </section>
                <section>
                    <h2>Discussion Board</h2>
                    <DiscussionBoard />
                </section>
            </main>
            <Footer />
        </div>
    );
};

export default Home;