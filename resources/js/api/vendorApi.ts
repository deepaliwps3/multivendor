import api from './axios';

export interface Industry {
    id: number;
    name: string;
}

export interface Service {
    id: number;
    name: string;
}

export interface VendorProfile {
    id: number;
    business_name: string;
    contact_person?: string | null;
    approval_status: 'pending' | 'approved' | 'rejected';
    rejection_reason?: string | null;
    // vendor_type: 'originator' | 'executor' | 'both';
    industries: Industry[];
    services: Service[];
}

export interface DashboardAlerts {
    new_assignments: number;
    awaiting_my_assignment: number;
    overdue_stages: number;
    overdue_details?: string; // e.g. "Order #1234 — Polishing, 2 days overdue"
}

export interface DashboardSummary {
    active_orders: number;
    assigned_to_me: number;
    awaiting_my_assignment: number;
    monthly_earnings: string; // pre-formatted, e.g. "₹42,500"
}

export type ActivityType =
    | 'stage_completed'
    | 'payment_received'
    | 'order_assigned'
    | 'stage_assigned';

export interface ActivityItem {
    id: number;
    type: ActivityType;
    title: string;
    order_id: number;
    timestamp: string; // pre-formatted relative time, e.g. "2h ago"
}

export interface AssignedStageItem {
    id: number;
    order_id: number;
    order_reference: string; // e.g. "Order #1234"
    service_name: string; // e.g. "Polishing"
    status: 'assigned' | 'in_progress' | 'completed';
}

export const vendorApi = {
    getMe: async (): Promise<VendorProfile> => {
        const response = await api.get<VendorProfile>('/vendor/me');
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

    getAssigned: async (): Promise<AssignedStageItem[]> => {
        const response = await api.get<AssignedStageItem[]>('/vendor/dashboard/assigned');
        return response.data;
    },
};

export default vendorApi;