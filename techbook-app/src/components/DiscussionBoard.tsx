import React, { useState, useEffect } from 'react';

const DiscussionBoard: React.FC = () => {
    const [discussions, setDiscussions] = useState([]);
    const [newDiscussion, setNewDiscussion] = useState('');

    useEffect(() => {
        // Fetch discussions from API (placeholder URL)
        const fetchDiscussions = async () => {
            const response = await fetch('/api/discussions');
            const data = await response.json();
            setDiscussions(data);
        };

        fetchDiscussions();
    }, []);

    const handlePostDiscussion = async () => {
        if (newDiscussion.trim()) {
            const response = await fetch('/api/discussions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ content: newDiscussion }),
            });

            if (response.ok) {
                const addedDiscussion = await response.json();
                setDiscussions([...discussions, addedDiscussion]);
                setNewDiscussion('');
            }
        }
    };

    return (
        <div>
            <h2>Discussion Board</h2>
            <div>
                <textarea
                    value={newDiscussion}
                    onChange={(e) => setNewDiscussion(e.target.value)}
                    placeholder="Share your thoughts..."
                />
                <button onClick={handlePostDiscussion}>Post</button>
            </div>
            <ul>
                {discussions.map((discussion) => (
                    <li key={discussion.id}>{discussion.content}</li>
                ))}
            </ul>
        </div>
    );
};

export default DiscussionBoard;