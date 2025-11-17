import React from 'react';

interface ReviewCardProps {
    reviewerName: string;
    rating: number;
    comments: string;
}

const ReviewCard: React.FC<ReviewCardProps> = ({ reviewerName, rating, comments }) => {
    return (
        <div className="review-card">
            <h3>{reviewerName}</h3>
            <div className="rating">{'★'.repeat(rating)}{'☆'.repeat(5 - rating)}</div>
            <p>{comments}</p>
        </div>
    );
};

export default ReviewCard;