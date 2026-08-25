// api/dashboardApi.ts
import api from './axios';

export interface VendorProfile {
    id: number;
    business_name: string;
    approval_status: 'pending' | 'approved' | 'rejected';
    rejection_reason: string | null;
    vendor_type: 'originator' | 'executor' | 'both';
    industries: { id: number; name: string }[];
    services: { id: number; name: string }[];
}

export interface DashboardAlerts {
    new_assignments: number;
    awaiting_my_assignment: number;
    overdue_stages: number;
}

export interface DashboardSummary {
    active_orders: number;
    assigned_to_me: number;
    awaiting_assignment: number;
    earnings_this_month: number;
}

export interface ActivityItem {
    id: number;
    type: 'stage_completed' | 'payment_received' | 'order_assigned' | 'stage_assigned';
    message: string;
    order_id: number;
    timestamp: string;
}

export interface DashboardResponse {
    vendor: VendorProfile;
    alerts: DashboardAlerts;
    summary: DashboardSummary;
    recent_activity: ActivityItem[];
}

export const dashboardApi = {
    getDashboard: async (): Promise<DashboardResponse> => {
        const response = await api.get<DashboardResponse>('/vendor/dashboard');
        return response.data;
    },
};

export default dashboardApi;