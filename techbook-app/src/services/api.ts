import axios from 'axios';

const API_BASE_URL = 'https://api.example.com'; // Replace with your actual API base URL

export const fetchServices = async () => {
    try {
        const response = await axios.get(`${API_BASE_URL}/services`);
        return response.data;
    } catch (error) {
        throw new Error('Error fetching services: ' + error.message);
    }
};

export const fetchReviews = async (serviceId) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/services/${serviceId}/reviews`);
        return response.data;
    } catch (error) {
        throw new Error('Error fetching reviews: ' + error.message);
    }
};

export const postReview = async (serviceId, reviewData) => {
    try {
        const response = await axios.post(`${API_BASE_URL}/services/${serviceId}/reviews`, reviewData);
        return response.data;
    } catch (error) {
        throw new Error('Error posting review: ' + error.message);
    }
};

export const fetchDiscussions = async (serviceId) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/services/${serviceId}/discussions`);
        return response.data;
    } catch (error) {
        throw new Error('Error fetching discussions: ' + error.message);
    }
};

export const postDiscussion = async (serviceId, discussionData) => {
    try {
        const response = await axios.post(`${API_BASE_URL}/services/${serviceId}/discussions`, discussionData);
        return response.data;
    } catch (error) {
        throw new Error('Error posting discussion: ' + error.message);
    }
};