import api from './axios';

export interface VendorProfile {
    id: number | null;
    business_name: string;
    contact_person?: string;
    address?: string;
    gst_number?: string;
    approval_status: 'pending' | 'approved' | 'rejected';
    kyc_status: 'pending' | 'verified' | 'rejected';
    vendor_type?: 'originator' | 'executor' | 'both';
    industries: { id: number; name: string }[];
    services: { id: number; name: string }[];
}

export interface UpdateProfilePayload {
    business_name: string;
    contact_person?: string;
    address?: string;
    gst_number?: string;
    industry_ids?: number[];
    service_ids?: number[];
}

export interface DashboardAlerts {
    new_assignments: number;
    awaiting_my_assignment: number;
    overdue_stages: number;
    overdue_details?: string;
}

export interface DashboardSummary {
    active_orders: number;
    assigned_to_me: number;
    awaiting_my_assignment: number;
    monthly_earnings: string;
}

export interface ActivityItem {
    id: number;
    type: string;
    title: string;
    timestamp: string;
    icon: string;
}

export const vendorApi = {
    getMe: async (): Promise<VendorProfile> => {
        const response = await api.get<VendorProfile>('/vendor/me');
        return response.data;
    },

    updateProfile: async (payload: UpdateProfilePayload): Promise<{ message: string; profile: VendorProfile }> => {
        const response = await api.put<{ message: string; profile: VendorProfile }>('/vendor/profile', payload);
        return response.data;
    },

    getAlerts: async (): Promise<DashboardAlerts> => {
        const response = await api.get<DashboardAlerts>('/vendor/dashboard/alerts');
        return response.data;
    },

    getSummary: async (): Promise<DashboardSummary> => {
        const response = await api.get<DashboardSummary>('/vendor/dashboard/summary');
        return response.data;
    },

    getActivity: async (): Promise<ActivityItem[]> => {
        const response = await api.get<ActivityItem[]>('/vendor/dashboard/activity');
        return response.data;
    },
};

export default vendorApi;
