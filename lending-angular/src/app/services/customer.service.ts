// src/app/services/customer.service.ts

import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import {
    Customer,
    CustomerDocument,
    CustomerStats,
    PaginatedResponse,
    ApiResponse
} from '../models/customer.model';

@Injectable({
    providedIn: 'root'
})
export class CustomerService {
    private apiUrl = 'http://localhost:8000/api/customers';

    constructor(private http: HttpClient) {}

    /**
     * Get all customers with pagination and filters
     */
    getCustomers(params?: {
        page?: number;
        per_page?: number;
        status?: string;
        customer_type?: string;
        search?: string;
    }): Observable<PaginatedResponse<Customer>> {
        let httpParams = new HttpParams();

        if (params) {
            Object.keys(params).forEach(key => {
                const value = params[key as keyof typeof params];
                if (value !== undefined && value !== null && value !== '') {
                    httpParams = httpParams.set(key, value.toString());
                }
            });
        }

        return this.http.get<PaginatedResponse<Customer>>(this.apiUrl, { params: httpParams });
    }

    /**
     * Get single customer by ID
     */
    getCustomer(id: number): Observable<ApiResponse<Customer>> {
        return this.http.get<ApiResponse<Customer>>(`${this.apiUrl}/${id}`);
    }

    /**
     * Create new customer with documents
     */
    createCustomer(customerData: Partial<Customer>): Observable<ApiResponse<Customer>> {
        return this.http.post<ApiResponse<Customer>>(this.apiUrl, customerData);
    }

    /**
     * Update customer with documents
     */
    updateCustomer(id: number, customerData: Partial<Customer>): Observable<ApiResponse<Customer>> {
        return this.http.put<ApiResponse<Customer>>(`${this.apiUrl}/${id}`, customerData);
    }

    /**
     * Delete customer
     */
    deleteCustomer(id: number): Observable<ApiResponse<any>> {
        return this.http.delete<ApiResponse<any>>(`${this.apiUrl}/${id}`);
    }

    /**
     * Get customer statistics
     */
    getStats(): Observable<ApiResponse<CustomerStats>> {
        return this.http.get<ApiResponse<CustomerStats>>(`${this.apiUrl}/stats`);
    }

    /**
     * Update customer status
     */
    updateStatus(id: number, status: string): Observable<ApiResponse<Customer>> {
        return this.http.patch<ApiResponse<Customer>>(`${this.apiUrl}/${id}/status`, { status });
    }

    /**
     * Bulk update customer status
     */
    bulkUpdateStatus(customerIds: number[], status: string): Observable<ApiResponse<any>> {
        return this.http.post<ApiResponse<any>>(`${this.apiUrl}/bulk-status`, {
            customer_ids: customerIds,
            status
        });
    }

    /**
     * Add documents to existing customer
     */
    addDocuments(customerId: number, documents: Partial<CustomerDocument>[]): Observable<ApiResponse<Customer>> {
        return this.http.post<ApiResponse<Customer>>(`${this.apiUrl}/${customerId}/documents`, { documents });
    }
}
