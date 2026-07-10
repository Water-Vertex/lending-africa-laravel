// src/app/models/customer.model.ts

export interface Customer {
    id?: number;
    customer_code: string;
    customer_type: 'personal' | 'sme';
    first_name: string;
    last_name: string;
    middle_name?: string | null;
    date_of_birth?: string | null;
    gender?: 'male' | 'female' | 'other' | null;
    national_id?: string | null;
    email?: string | null;
    phone_primary: string;
    phone_secondary?: string | null;
    occupation?: string | null;
    monthly_income?: number | null;
    country: string;
    state?: string | null;
    city?: string | null;
    local_government_area?: string | null;
    address?: string | null;
    status: 'active' | 'inactive' | 'blacklisted';
    documents?: CustomerDocument[];
    created_at?: string;
    updated_at?: string;
}

export interface CustomerDocument {
    id?: number;
    customer_id?: number;
    document_type: 'national_id' | 'passport' | 'driver_license' | 'other';
    file_path: string;
    verification_status: 'pending' | 'verified' | 'rejected';
    created_at?: string;
    updated_at?: string;
}

export interface CustomerStats {
    total_customers: number;
    active_customers: number;
    inactive_customers: number;
    blacklisted_customers: number;
    personal_customers: number;
    sme_customers: number;
    total_documents: number;
    pending_verification: number;
}

export interface PaginatedResponse<T> {
    success: boolean;
    data: {
        current_page: number;
        data: T[];
        first_page_url: string;
        from: number;
        last_page: number;
        last_page_url: string;
        links: any[];
        next_page_url: string | null;
        path: string;
        per_page: number;
        prev_page_url: string | null;
        to: number;
        total: number;
    };
    message: string;
}

export interface ApiResponse<T> {
    success: boolean;
    data: T;
    message: string;
}
