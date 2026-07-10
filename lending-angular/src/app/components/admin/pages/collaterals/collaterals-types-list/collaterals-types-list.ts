import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { CollateralService } from '../../../../../services/collateral.service';
import { ToastService } from '../../../../../services/toast.service';
import { CollateralType } from '../../../../../models/collateral-type.model';

@Component({
  selector: 'app-collateral-types-list',
  standalone: true,
  imports: [CommonModule, RouterModule, FormsModule],
  templateUrl: './collaterals-types-list.html'
})
export class CollateralTypesList implements OnInit {

  types: CollateralType[] = [];
  filteredTypes: CollateralType[] = [];
  isLoading = false;
  searchTerm = '';

  constructor(
    private collateralService: CollateralService,
    private toast: ToastService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.loadTypes();
  }

  loadTypes(): void {
    this.isLoading = true;
    this.cdr.detectChanges();

    this.collateralService.getCollateralTypes().subscribe({
      next: (res) => {
        if (res.success && res.data) {
          // ✅ Sort by ID in ascending order (1, 2, 3, ...)
          this.types = res.data.sort((a: any, b: any) => a.id - b.id);
          this.filteredTypes = [...this.types];
        }
        this.isLoading = false;
        this.cdr.detectChanges();
      },
      error: (err) => {
        this.isLoading = false;
        this.cdr.detectChanges();
        this.toast.error('Error', 'Failed to load collateral types');
        console.error('Error loading collateral types:', err);
      }
    });
  }

  refreshData(): void {
    this.searchTerm = '';
    this.loadTypes();
  }

  onSearch(): void {
    const term = this.searchTerm.trim().toLowerCase();
    if (!term) {
      this.filteredTypes = [...this.types];
    } else {
      this.filteredTypes = this.types.filter(t =>
        t.name.toLowerCase().includes(term)
      );
    }
    this.cdr.detectChanges();
  }

  deleteType(id: number): void {
    if (!confirm('Are you sure you want to delete this collateral type?')) return;

    this.collateralService.deleteCollateralType(id).subscribe({
      next: (res) => {
        this.toast.success('Success', res.message || 'Collateral type deleted successfully');
        this.types = this.types.filter(t => t.id !== id);
        this.filteredTypes = this.filteredTypes.filter(t => t.id !== id);
        this.cdr.detectChanges();
      },
      error: (err) => {
        this.toast.error('Error', err.error?.message || 'Failed to delete collateral type');
        console.error('Error deleting collateral type:', err);
        this.cdr.detectChanges();
      }
    });
  }

  trackById(index: number, type: CollateralType): number {
    return type.id;
  }
}