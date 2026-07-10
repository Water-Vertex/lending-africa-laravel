import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { DashboardService } from '../../../../services/dashboard.service';
import { DashboardStats, DashboardResponse } from '../../../../models/dashboard.model';
import { AuthService } from '../../../../services/auth.service';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './dashboard.html',
})
export class Dashboard implements OnInit {
  private dashboardService = inject(DashboardService);
  private authService      = inject(AuthService);

  loading = true;
  error   = '';

  stats: DashboardStats = {
    total_customers: 0,
    active_customers: 0,
    blacklisted: 0,
    personal_customers: 0,
    sme_customers: 0,
    total_applications: 0,
    pending_applications: 0,
    approved_loans: 0,
    disbursed_loans: 0,
    rejected_loans: 0,
    closed_loans: 0,
    total_disbursed: 0,
    total_repaid: 0,
    pending_repayments: 0,
    total_products: 0,
    total_staff: 0,
  };

  get firstName(): string {
    const user = this.authService.getCurrentUser();
    return user?.first_name || 'Admin';
  }

  ngOnInit(): void {
    this.loadStats();
  }

  loadStats(): void {
    this.loading = true;
    this.error   = '';

    this.dashboardService.getStats().subscribe({
      next: (res: DashboardResponse) => {
        if (res.success) this.stats = res.data;
        this.loading = false;
      },
      error: () => {
        this.error   = 'Could not load dashboard data. Please refresh.';
        this.loading = false;
      },
    });
  }

  formatCurrency(amount: number): string {
    if (amount >= 1_000_000) return '₦' + (amount / 1_000_000).toFixed(1) + 'M';
    if (amount >= 1_000)     return '₦' + (amount / 1_000).toFixed(1) + 'K';
    return '₦' + amount.toLocaleString();
  }
}