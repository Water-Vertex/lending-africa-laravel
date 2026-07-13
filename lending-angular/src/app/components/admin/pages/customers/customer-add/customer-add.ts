// src/app/components/customer-add/customer-add.component.ts

import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router, RouterModule } from '@angular/router';
import { CustomerService } from '../../../../../services/customer.service';
import { Customer, CustomerDocument } from '../../../../../models/customer.model';

@Component({
    selector: 'app-customer-add',
    standalone: true,
    imports: [CommonModule, FormsModule, RouterModule],
    templateUrl: './customer-add.html',
    styleUrl: './customer-add.css'
})
export class CustomerAdd {
    customer: Partial<Customer> = {
        customer_type: 'personal',
        status: 'active',
        country: 'Nigeria',
        documents: []
    };

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

    constructor(
        private customerService: CustomerService,
        private router: Router
    ) {}

    onSubmit(): void {
        // Validate required fields
        if (!this.customer.first_name || !this.customer.last_name || !this.customer.phone_primary) {
            this.error = 'Please fill in all required fields.';
            return;
        }

        this.isSubmitting = true;
        this.error = null;
        this.successMessage = null;

        const submitData = {
            ...this.customer,
            documents: this.customer.documents || []
        };

        this.customerService.createCustomer(submitData).subscribe({
            next: (response) => {
                this.successMessage = 'Customer created successfully!';
                this.isSubmitting = false;
                setTimeout(() => {
                    this.router.navigate(['/customers', response.data.id]);
                }, 2000);
            },
            error: (error) => {
                this.error = this.extractErrorMessage(error);
                this.isSubmitting = false;
            }
        });
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

        // Reset form
        this.newDocument = {
            document_type: 'other',
            verification_status: 'pending',
            file_path: ''
        };
    }

    removeDocument(index: number): void {
        if (this.customer.documents) {
            this.customer.documents.splice(index, 1);
        }
    }

    onFileSelected(event: any): void {
        const file = event.target.files[0];
        if (file) {
            const filePath = `/uploads/${file.name}`;
            this.newDocument.file_path = filePath;
            this.uploadedFiles.push({ name: file.name, path: filePath });
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
        this.router.navigate(['/customers/list']);
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
}
