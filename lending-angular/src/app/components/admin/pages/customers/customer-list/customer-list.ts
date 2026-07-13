// src/app/components/admin/pages/customers/customer-list/customer-list.component.ts

import { Component, OnInit, ChangeDetectorRef, ChangeDetectionStrategy } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { CustomerService } from '../../../../../services/customer.service';
import { Customer } from '../../../../../models/customer.model';

@Component({
    selector: 'app-customer-list',
    standalone: true,
    imports: [CommonModule, RouterModule, FormsModule],
    templateUrl: './customer-list.html',
    styleUrl: './customer-list.css',
    changeDetection: ChangeDetectionStrategy.OnPush
})
export class CustomerList implements OnInit {
    customers: Customer[] = [];
    isLoading = false;
    error: string | null = null;

    // Pagination
    currentPage = 1;
    perPage = 10;
    totalItems = 0;
    totalPages = 0;

    // Filters
    searchTerm = '';
    selectedStatus = '';
    selectedType = '';

    // Bulk actions
    selectedCustomers: number[] = [];
    bulkStatus = '';
    showBulkStatusModal = false;

    // Stats
    stats: any = null;
    showStats = false;

    // Expose Math to template
    Math = Math;

    constructor(
        private customerService: CustomerService,
        private cdr: ChangeDetectorRef
    ) {}

    ngOnInit(): void {
        this.loadCustomers();
        this.loadStats();
    }

    loadCustomers(): void {
        this.isLoading = true;
        this.error = null;
        this.cdr.detectChanges();

        this.customerService.getCustomers({
            page: this.currentPage,
            per_page: this.perPage,
            status: this.selectedStatus || undefined,
            customer_type: this.selectedType || undefined,
            search: this.searchTerm || undefined
        }).subscribe({
            next: (response) => {
                this.customers = response.data.data;
                this.currentPage = response.data.current_page;
                this.perPage = response.data.per_page;
                this.totalItems = response.data.total;
                this.totalPages = response.data.last_page;
                this.isLoading = false;
                this.cdr.detectChanges();
            },
            error: (error) => {
                this.error = 'Failed to load customers. Please try again.';
                this.isLoading = false;
                console.error('Error loading customers:', error);
                this.cdr.detectChanges();
            }
        });
    }

    loadStats(): void {
        this.customerService.getStats().subscribe({
            next: (response) => {
                this.stats = response.data;
                this.cdr.detectChanges();
            },
            error: (error) => {
                console.error('Error loading stats:', error);
                this.cdr.detectChanges();
            }
        });
    }

    onSearch(): void {
        this.currentPage = 1;
        this.loadCustomers();
    }

    applyFilters(): void {
        this.currentPage = 1;
        this.loadCustomers();
    }

    resetFilters(): void {
        this.searchTerm = '';
        this.selectedStatus = '';
        this.selectedType = '';
        this.applyFilters();
    }

    refreshData(): void {
        this.loadCustomers();
        this.loadStats();
    }

    goToPage(page: number): void {
        if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
            this.loadCustomers();
        }
    }

    prevPage(): void {
        if (this.currentPage > 1) {
            this.currentPage--;
            this.loadCustomers();
        }
    }

    nextPage(): void {
        if (this.currentPage < this.totalPages) {
            this.currentPage++;
            this.loadCustomers();
        }
    }

    toggleSelectAll(event: any): void {
        if (event.target.checked) {
            this.selectedCustomers = this.customers.map(c => c.id!).filter(id => id !== undefined);
        } else {
            this.selectedCustomers = [];
        }
        this.cdr.detectChanges();
    }

    toggleSelect(customerId: number, event: any): void {
        if (event.target.checked) {
            this.selectedCustomers.push(customerId);
        } else {
            this.selectedCustomers = this.selectedCustomers.filter(id => id !== customerId);
        }
        this.cdr.detectChanges();
    }

    isSelected(customerId: number): boolean {
        return this.selectedCustomers.includes(customerId);
    }

    openBulkStatusModal(): void {
        if (this.selectedCustomers.length === 0) {
            alert('Please select at least one customer.');
            return;
        }
        this.showBulkStatusModal = true;
        this.cdr.detectChanges();
    }

    closeBulkStatusModal(): void {
        this.showBulkStatusModal = false;
        this.bulkStatus = '';
        this.cdr.detectChanges();
    }

    applyBulkStatus(): void {
        if (!this.bulkStatus) {
            alert('Please select a status.');
            return;
        }

        this.isLoading = true;
        this.cdr.detectChanges();

        this.customerService.bulkUpdateStatus(this.selectedCustomers, this.bulkStatus)
            .subscribe({
                next: () => {
                    this.loadCustomers();
                    this.selectedCustomers = [];
                    this.closeBulkStatusModal();
                    this.isLoading = false;
                    this.cdr.detectChanges();
                },
                error: (error) => {
                    console.error('Error updating bulk status:', error);
                    alert('Failed to update statuses. Please try again.');
                    this.isLoading = false;
                    this.cdr.detectChanges();
                }
            });
    }

    deleteCustomer(id: number): void {
        if (confirm('Are you sure you want to delete this customer?')) {
            this.isLoading = true;
            this.cdr.detectChanges();

            this.customerService.deleteCustomer(id).subscribe({
                next: () => {
                    this.loadCustomers();
                    this.isLoading = false;
                    this.cdr.detectChanges();
                },
                error: (error) => {
                    console.error('Error deleting customer:', error);
                    alert('Failed to delete customer. Please try again.');
                    this.isLoading = false;
                    this.cdr.detectChanges();
                }
            });
        }
    }

    getStatusClass(status: string): string {
        const classes = {
            'active': 'bg-green-100 text-green-800',
            'inactive': 'bg-yellow-100 text-yellow-800',
            'blacklisted': 'bg-red-100 text-red-800'
        };
        return classes[status as keyof typeof classes] || 'bg-gray-100 text-gray-800';
    }

    getTypeClass(type: string): string {
        return type === 'personal' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800';
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

    trackById(index: number, customer: Customer): number {
        return customer.id!;
    }

    toggleStats(): void {
        this.showStats = !this.showStats;
        this.cdr.detectChanges();
    }

    // Helper function for pagination calculation
    getStartItem(): number {
        return (this.currentPage - 1) * this.perPage + 1;
    }

    getEndItem(): number {
        return Math.min(this.currentPage * this.perPage, this.totalItems);
    }
}
