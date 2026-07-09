// src/app/components/admin/pages/customers/customer-detail/customer-detail.component.ts

import { Component, OnInit, ChangeDetectorRef, ChangeDetectionStrategy } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, ActivatedRoute, Router } from '@angular/router';
import { CustomerService } from '../../../../../services/customer.service';
import { Customer } from '../../../../../models/customer.model';

@Component({
    selector: 'app-customer-detail',
    standalone: true,
    imports: [CommonModule, RouterModule],
    templateUrl: './customer-detail.html',
    styleUrl: './customer-detail.css',
    changeDetection: ChangeDetectionStrategy.OnPush
})
export class CustomerDetail implements OnInit {
    customer: Customer | null = null;
    isLoading = false;
    error: string | null = null;
    showDeleteModal = false;

    constructor(
        private customerService: CustomerService,
        private route: ActivatedRoute,
        private router: Router,
        private cdr: ChangeDetectorRef
    ) {}

    ngOnInit(): void {
        this.route.params.subscribe(params => {
            if (params['id']) {
                this.loadCustomer(+params['id']);
            }
        });
    }

    loadCustomer(id: number): void {
        this.isLoading = true;
        this.error = null;
        this.cdr.detectChanges();

        this.customerService.getCustomer(id).subscribe({
            next: (response) => {
                this.customer = response.data;
                this.isLoading = false;
                this.cdr.detectChanges();
            },
            error: (error) => {
                this.error = 'Failed to load customer details. Please try again.';
                this.isLoading = false;
                console.error('Error loading customer:', error);
                this.cdr.detectChanges();
            }
        });
    }

    openDeleteModal(): void {
        this.showDeleteModal = true;
        this.cdr.detectChanges();
    }

    closeDeleteModal(): void {
        this.showDeleteModal = false;
        this.cdr.detectChanges();
    }

    deleteCustomer(): void {
        if (!this.customer) return;

        this.isLoading = true;
        this.cdr.detectChanges();

        this.customerService.deleteCustomer(this.customer.id!).subscribe({
            next: () => {
                this.closeDeleteModal();
                this.router.navigate(['/customers']);
                this.isLoading = false;
                this.cdr.detectChanges();
            },
            error: (error) => {
                console.error('Error deleting customer:', error);
                alert('Failed to delete customer. Please try again.');
                this.closeDeleteModal();
                this.isLoading = false;
                this.cdr.detectChanges();
            }
        });
    }

    getStatusClass(status: string): string {
        const classes = {
            'active': 'bg-green-100 text-green-800',
            'inactive': 'bg-yellow-100 text-yellow-800',
            'blacklisted': 'bg-red-100 text-red-800'
        };
        return classes[status as keyof typeof classes] || 'bg-gray-100 text-gray-800';
    }

    getStatusBadge(status: string): string {
        const labels = {
            'active': 'Active',
            'inactive': 'Inactive',
            'blacklisted': 'Blacklisted'
        };
        return labels[status as keyof typeof labels] || status;
    }

    getTypeLabel(type: string): string {
        return type === 'personal' ? 'Personal' : 'SME';
    }

    getTypeClass(type: string): string {
        return type === 'personal' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800';
    }

    getDocumentTypeLabel(type: string): string {
        const labels = {
            'national_id': 'National ID',
            'passport': 'Passport',
            'driver_license': 'Driver License',
            'other': 'Other'
        };
        return labels[type as keyof typeof labels] || type;
    }

    getVerificationStatusLabel(status: string): string {
        const labels = {
            'pending': 'Pending',
            'verified': 'Verified',
            'rejected': 'Rejected'
        };
        return labels[status as keyof typeof labels] || status;
    }

    getVerificationClass(status: string): string {
        const classes = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'verified': 'bg-green-100 text-green-800',
            'rejected': 'bg-red-100 text-red-800'
        };
        return classes[status as keyof typeof classes] || 'bg-gray-100 text-gray-800';
    }

    formatDate(date: string | null | undefined): string {
        if (!date) return 'N/A';
        try {
            return new Date(date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        } catch (e) {
            return 'N/A';
        }
    }

    formatCurrency(amount: number | null | undefined): string {
        if (!amount) return 'N/A';
        try {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'NGN',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        } catch (e) {
            return 'N/A';
        }
    }
}
