export interface User {
    id: string;
    username: string;
    email: string;
    profilePicture?: string;
    reviews: Review[];
}

export interface Review {
    id: string;
    userId: string;
    serviceName: string;
    rating: number;
    comments: string;
    createdAt: Date;
    updatedAt: Date;
}

export interface Discussion {
    id: string;
    userId: string;
    title: string;
    content: string;
    createdAt: Date;
    updatedAt: Date;
}