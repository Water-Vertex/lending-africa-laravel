// src/app/components/admin/pages/customers/customer-edit/customer-edit.component.ts

import { Component, OnInit, ChangeDetectorRef, ChangeDetectionStrategy } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
import { CustomerService } from '../../../../../services/customer.service';
import { Customer, CustomerDocument } from '../../../../../models/customer.model';

@Component({
    selector: 'app-customer-edit',
    standalone: true,
    imports: [CommonModule, FormsModule, RouterModule],
    templateUrl: './customer-edit.html',
    styleUrl: './customer-edit.css',
    changeDetection: ChangeDetectionStrategy.OnPush
})
export class CustomerEdit implements OnInit {
    customer: Partial<Customer> = {
        customer_type: 'personal',
        status: 'active',
        country: 'Nigeria',
        documents: []
    };

    customerId: number | null = null;
    isLoading = false;
    isSubmitting = false;
    error: string | null = null;
    successMessage: string | null = null;

    // Document management
    newDocument: Partial<CustomerDocument> = {
        document_type: 'other',
        verification_status: 'pending',
        file_path: ''
    };

    uploadedFiles: { name: string; path: string }[] = [];
    replaceDocuments = false;

    constructor(
        private customerService: CustomerService,
        private route: ActivatedRoute,
        private router: Router,
        private cdr: ChangeDetectorRef
    ) {}

    ngOnInit(): void {
        this.route.params.subscribe(params => {
            if (params['id']) {
                this.customerId = +params['id'];
                this.loadCustomer();
            }
        });
    }

    loadCustomer(): void {
        if (!this.customerId) return;

        this.isLoading = true;
        this.error = null;
        this.cdr.detectChanges();

        this.customerService.getCustomer(this.customerId).subscribe({
            next: (response) => {
                this.customer = response.data;
                this.isLoading = false;
                this.cdr.detectChanges();
            },
            error: (error) => {
                this.error = 'Failed to load customer data.';
                this.isLoading = false;
                console.error('Error loading customer:', error);
                this.cdr.detectChanges();
            }
        });
    }

    onSubmit(): void {
        if (!this.customer.first_name || !this.customer.last_name || !this.customer.phone_primary) {
            this.error = 'Please fill in all required fields.';
            this.cdr.detectChanges();
            return;
        }

        this.isSubmitting = true;
        this.error = null;
        this.successMessage = null;
        this.cdr.detectChanges();

        const submitData = {
            ...this.customer,
            documents: this.customer.documents || [],
            replace_documents: this.replaceDocuments
        };

        if (this.customerId) {
            this.customerService.updateCustomer(this.customerId, submitData).subscribe({
                next: (response) => {
                    this.successMessage = 'Customer updated successfully!';
                    this.isSubmitting = false;
                    this.cdr.detectChanges();
                    setTimeout(() => {
                        this.router.navigate(['/admin/customers', this.customerId]);
                        this.cdr.detectChanges();
                    }, 2000);
                },
                error: (error) => {
                    this.error = this.extractErrorMessage(error);
                    this.isSubmitting = false;
                    this.cdr.detectChanges();
                }
            });
        }
    }

    addDocument(): void {
        if (!this.newDocument.document_type || !this.newDocument.file_path) {
            alert('Please fill in document type and file path.');
            return;
        }

        if (!this.customer.documents) {
            this.customer.documents = [];
        }

        this.customer.documents.push({
            document_type: this.newDocument.document_type,
            file_path: this.newDocument.file_path,
            verification_status: this.newDocument.verification_status || 'pending'
        });

        this.newDocument = {
            document_type: 'other',
            verification_status: 'pending',
            file_path: ''
        };
        this.cdr.detectChanges();
    }

    removeDocument(index: number): void {
        if (this.customer.documents) {
            this.customer.documents.splice(index, 1);
            this.cdr.detectChanges();
        }
    }

    onFileSelected(event: any): void {
        const file = event.target.files[0];
        if (file) {
            const filePath = `/uploads/${file.name}`;
            this.newDocument.file_path = filePath;
            this.uploadedFiles.push({ name: file.name, path: filePath });
            this.cdr.detectChanges();
        }
    }

    private extractErrorMessage(error: any): string {
        if (error.error && error.error.errors) {
            const errors = error.error.errors;
            const messages = Object.values(errors).flat();
            return messages.join('. ');
        }
        return error.error?.message || 'An error occurred. Please try again.';
    }

    cancel(): void {
        if (this.customerId) {
            this.router.navigate(['/admin/customers', this.customerId]);
        } else {
            this.router.navigate(['/admin/customers/list']);
        }
        this.cdr.detectChanges();
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

    getStatusLabel(status: string): string {
        const labels = {
            'active': 'Active',
            'inactive': 'Inactive',
            'blacklisted': 'Blacklisted'
        };
        return labels[status as keyof typeof labels] || status;
    }
}
