// import { Component, OnInit, inject } from '@angular/core';
// import { CommonModule } from '@angular/common';
// import { RouterModule } from '@angular/router';
// import { FormsModule } from '@angular/forms';
// import { LoanProductService } from '../../../../../services/loan-product.service';
// import { LoanProduct } from '../../../../../models/loan-product.model';

// @Component({
//   selector: 'app-loan-product-list',
//   standalone: true,
//   imports: [CommonModule, RouterModule, FormsModule],
//   templateUrl: './loan-products-list.html',
// })
// export class LoanProductList implements OnInit {
//   private svc = inject(LoanProductService);

//   products: LoanProduct[] = [];
//   loading  = true;
//   deleting: number | null = null;
//   toggling: number | null = null;
//   error    = '';

//   // Filters
//   search    = '';
//   status    = '';
//   loanType  = '';

//   // Delete confirm
//   confirmDeleteId: number | null = null;

//   ngOnInit(): void { this.load(); }

//   load(): void {
//     this.loading = true;
//     this.error   = '';
//     this.svc.getAll({ search: this.search, status: this.status, loan_type: this.loanType }).subscribe({
//       next : res  => { this.products = res.data; this.loading = false; },
//       error: ()   => { this.error = 'Failed to load loan products.'; this.loading = false; },
//     });
//   }

//   toggleStatus(p: LoanProduct): void {
//     this.toggling = p.id!;
//     this.svc.toggleStatus(p.id!).subscribe({
//       next : res => { const idx = this.products.findIndex(x => x.id === p.id); if (idx > -1) this.products[idx] = res.data; this.toggling = null; },
//       error: ()  => this.toggling = null,
//     });
//   }

//   confirmDelete(id: number): void { this.confirmDeleteId = id; }
//   cancelDelete(): void            { this.confirmDeleteId = null; }

//   doDelete(): void {
//     if (!this.confirmDeleteId) return;
//     this.deleting = this.confirmDeleteId;
//     this.confirmDeleteId = null;
//     this.svc.delete(this.deleting).subscribe({
//       next : () => { this.products = this.products.filter(p => p.id !== this.deleting); this.deleting = null; },
//       error: (e) => { this.error = e.error?.message || 'Delete failed.'; this.deleting = null; },
//     });
//   }

//   formatCurrency(v: number): string {
//     return '₦' + Number(v).toLocaleString();
//   }
// }


import { Component, OnInit, inject, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { LoanProductService } from '../../../../../services/loan-product.service';
import { LoanProduct } from '../../../../../models/loan-product.model';

@Component({
  selector: 'app-loan-product-list',
  standalone: true,
  imports: [CommonModule, RouterModule, FormsModule],
  templateUrl: './loan-products-list.html',
})
export class LoanProductList implements OnInit {
  private svc = inject(LoanProductService);
  private cdr = inject(ChangeDetectorRef);   // 👈 added

  products: LoanProduct[] = [];
  loading  = true;
  deleting: number | null = null;
  toggling: number | null = null;
  error    = '';

  // Filters
  search    = '';
  status    = '';
  loanType  = '';

  // Delete confirm
  confirmDeleteId: number | null = null;

  ngOnInit(): void { this.load(); }

  load(): void {
    this.loading = true;
    this.error   = '';
    this.svc.getAll({ search: this.search, status: this.status, loan_type: this.loanType }).subscribe({
      next : res  => {
        this.products = res.data;
        this.loading = false;
        this.cdr.markForCheck();   // 👈 added
        this.cdr.detectChanges();  // 👈 added
      },
      error: () => {
        this.error = 'Failed to load loan products.';
        this.loading = false;
        this.cdr.markForCheck();   // 👈 added
      },
    });
  }

  toggleStatus(p: LoanProduct): void {
    this.toggling = p.id!;
    this.svc.toggleStatus(p.id!).subscribe({
      next : res => {
        const idx = this.products.findIndex(x => x.id === p.id);
        if (idx > -1) this.products[idx] = res.data;
        this.toggling = null;
        this.cdr.markForCheck();   // 👈 added
      },
      error: () => {
        this.toggling = null;
        this.cdr.markForCheck();   // 👈 added
      },
    });
  }

  confirmDelete(id: number): void { this.confirmDeleteId = id; }
  cancelDelete(): void            { this.confirmDeleteId = null; }

  doDelete(): void {
    if (!this.confirmDeleteId) return;
    this.deleting = this.confirmDeleteId;
    this.confirmDeleteId = null;
    this.svc.delete(this.deleting).subscribe({
      next : () => {
        this.products = this.products.filter(p => p.id !== this.deleting);
        this.deleting = null;
        this.cdr.markForCheck();   // 👈 added
      },
      error: (e) => {
        this.error = e.error?.message || 'Delete failed.';
        this.deleting = null;
        this.cdr.markForCheck();   // 👈 added
      },
    });
  }

  formatCurrency(v: number): string {
    return '₦' + Number(v).toLocaleString();
  }
}