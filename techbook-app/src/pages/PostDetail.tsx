import React from 'react';
import { useParams } from 'react-router-dom';
import ReviewCard from '../components/ReviewCard';
import DiscussionBoard from '../components/DiscussionBoard';
import { fetchPostDetail } from '../services/api';

const PostDetail: React.FC = () => {
    const { id } = useParams<{ id: string }>();
    const [postDetail, setPostDetail] = React.useState<any>(null);
    const [loading, setLoading] = React.useState(true);
    
    React.useEffect(() => {
        const getPostDetail = async () => {
            const data = await fetchPostDetail(id);
            setPostDetail(data);
            setLoading(false);
        };
        getPostDetail();
    }, [id]);

    if (loading) {
        return <div>Loading...</div>;
    }

    if (!postDetail) {
        return <div>Post not found.</div>;
    }

    return (
        <div>
            <h1>{postDetail.title}</h1>
            <ReviewCard review={postDetail.review} />
            <DiscussionBoard postId={id} />
        </div>
    );
};

export default PostDetail;