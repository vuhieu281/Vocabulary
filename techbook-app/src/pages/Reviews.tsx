import React, { useEffect, useState } from 'react';
import ReviewList from '../components/ReviewList';
import { fetchReviews } from '../services/api';

const Reviews: React.FC = () => {
    const [reviews, setReviews] = useState([]);

    useEffect(() => {
        const getReviews = async () => {
            const data = await fetchReviews();
            setReviews(data);
        };

        getReviews();
    }, []);

    return (
        <div>
            <h1>User Reviews</h1>
            <ReviewList reviews={reviews} />
        </div>
    );
};

export default Reviews;