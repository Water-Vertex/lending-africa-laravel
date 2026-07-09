import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { CollateralService } from '../../../../../services/collateral.service';
import { ToastService } from '../../../../../services/toast.service';
import { Collateral } from '../../../../../models/collateral.model';

@Component({
  selector: 'app-collaterals-list',
  standalone: true,
  imports: [CommonModule, RouterModule, FormsModule],
  templateUrl: './collaterals-list.html'
})
export class CollateralsList implements OnInit {

  collaterals: Collateral[] = [];
  filteredCollaterals: Collateral[] = [];
  isLoading = false;
  searchTerm = '';

  constructor(
    private collateralService: CollateralService,
    private toast: ToastService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.loadCollaterals();
  }

  loadCollaterals(): void {
    this.isLoading = true;
    this.cdr.detectChanges();

    this.collateralService.getCollaterals().subscribe({
      next: (res) => {
        if (res.success && res.data) {
          this.collaterals = res.data;
          this.filteredCollaterals = [...res.data];
        }
        this.isLoading = false;
        this.cdr.detectChanges();
      },
      error: (err) => {
        this.isLoading = false;
        this.cdr.detectChanges();
        this.toast.error('Error', 'Failed to load collaterals');
        console.error('Error loading collaterals:', err);
      }
    });
  }

  refreshData(): void {
    this.searchTerm = '';
    this.loadCollaterals();
  }

  onSearch(): void {
    const term = this.searchTerm.trim().toLowerCase();
    if (!term) {
      this.filteredCollaterals = [...this.collaterals];
    } else {
      this.filteredCollaterals = this.collaterals.filter(c =>
        c.asset_name.toLowerCase().includes(term) ||
        (c.ownership_document_no || '').toLowerCase().includes(term) ||
        (c.collateralType?.name || '').toLowerCase().includes(term)
      );
    }
    this.cdr.detectChanges();
  }

  deleteCollateral(id: number): void {
    if (!confirm('Are you sure you want to delete this collateral?')) return;

    this.collateralService.deleteCollateral(id).subscribe({
      next: (res) => {
        this.toast.success('Success', res.message || 'Collateral deleted successfully');
        this.collaterals = this.collaterals.filter(c => c.id !== id);
        this.filteredCollaterals = this.filteredCollaterals.filter(c => c.id !== id);
        this.cdr.detectChanges();
      },
      error: (err) => {
        this.toast.error('Error', err.error?.message || 'Failed to delete collateral');
        console.error('Error deleting collateral:', err);
        this.cdr.detectChanges();
      }
    });
  }

  statusClass(status: string): string {
    switch (status) {
      case 'verified': return 'bg-green-100 text-green-700';
      case 'rejected': return 'bg-red-100 text-red-700';
      default: return 'bg-yellow-100 text-yellow-700';
    }
  }

  trackById(index: number, collateral: Collateral): number {
    return collateral.id;
  }
}