import api from './axios';

export interface User {
    id: number;
    name: string;
    email: string;
    phone?: string | null;
    role?: {
        id: number;
        name: string;
    } | null;
}

export interface LoginCredentials {
    email: string;
    password: string;
    device_name?: string;
}

export interface RegisterCredentials {
    name: string;
    email: string;
    password: string;
    phone?: string;
    address?: string;
    gst_number?: string;
    business_name?: string;
    role?: string;
    industry_ids?: number[];
    service_ids?: number[];
}

export interface AuthResponse {
    message: string;
    user: User;
    token: string;
}

export interface Industry {
    id: number;
    name: string;
}

export interface Service {
    id: number;
    name: string;
    industry_id: number;
}

export const authApi = {
    login: async (credentials: LoginCredentials): Promise<AuthResponse> => {
        const response = await api.post<AuthResponse>('/auth/login', {
            ...credentials,
            device_name: credentials.device_name || 'mobile-native-app',
        });
        return response.data;
    },

    register: async (credentials: RegisterCredentials): Promise<AuthResponse> => {
        const response = await api.post<AuthResponse>('/auth/register', credentials);
        return response.data;
    },

    logout: async (): Promise<{ message: string }> => {
        const response = await api.post<{ message: string }>('/auth/logout');
        return response.data;
    },

    getMe: async (): Promise<User> => {
        const response = await api.get<User>('/auth/me');
        return response.data;
    },   
    getIndustries: async (): Promise<Industry[]> => {
        const response = await api.get<Industry[]>('/industries');
        return response.data;
    },

    getServices: async (industryIds?: number[]): Promise<Service[]> => {
        const response = await api.get<Service[]>('/services', {
            params: industryIds && industryIds.length > 0 ? { industry_ids: industryIds.join(',') } : {},
        });
        return response.data;
    },   
};

export default authApi;
