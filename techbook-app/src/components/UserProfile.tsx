import React from 'react';
import { User } from '../types';

interface UserProfileProps {
  user: User;
}

const UserProfile: React.FC<UserProfileProps> = ({ user }) => {
  return (
    <div className="user-profile">
      <h2>{user.name}</h2>
      <p>Email: {user.email}</p>
      <h3>Submitted Reviews</h3>
      <ul>
        {user.reviews.map(review => (
          <li key={review.id}>
            <h4>{review.title}</h4>
            <p>{review.comment}</p>
            <p>Rating: {review.rating}</p>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default UserProfile;