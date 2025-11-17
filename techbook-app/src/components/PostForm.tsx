import React, { useState } from 'react';

const PostForm: React.FC = () => {
    const [storeName, setStoreName] = useState('');
    const [rating, setRating] = useState(0);
    const [comments, setComments] = useState('');
    const [image, setImage] = useState<File | null>(null);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        // Handle form submission logic here
        console.log({ storeName, rating, comments, image });
    };

    return (
        <form onSubmit={handleSubmit}>
            <div>
                <label htmlFor="storeName">Store Name:</label>
                <input
                    type="text"
                    id="storeName"
                    value={storeName}
                    onChange={(e) => setStoreName(e.target.value)}
                    required
                />
            </div>
            <div>
                <label htmlFor="rating">Rating:</label>
                <select
                    id="rating"
                    value={rating}
                    onChange={(e) => setRating(Number(e.target.value))}
                    required
                >
                    <option value="">Select a rating</option>
                    <option value="1">1 Star</option>
                    <option value="2">2 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="5">5 Stars</option>
                </select>
            </div>
            <div>
                <label htmlFor="comments">Comments:</label>
                <textarea
                    id="comments"
                    value={comments}
                    onChange={(e) => setComments(e.target.value)}
                    required
                />
            </div>
            <div>
                <label htmlFor="image">Upload Image:</label>
                <input
                    type="file"
                    id="image"
                    onChange={(e) => setImage(e.target.files?.[0] || null)}
                />
            </div>
            <button type="submit">Submit Review</button>
        </form>
    );
};

export default PostForm;