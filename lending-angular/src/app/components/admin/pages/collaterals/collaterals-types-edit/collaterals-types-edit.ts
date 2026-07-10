import { Component, OnInit, ChangeDetectorRef, OnDestroy } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
import { CollateralService } from '../../../../../services/collateral.service';
import { ToastService } from '../../../../../services/toast.service';
import { CollateralTypePayload } from '../../../../../models/collateral-type.model';
import { Subject } from 'rxjs';
import { takeUntil, finalize } from 'rxjs/operators';

@Component({
  selector: 'app-collateral-types-edit',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterModule],
  templateUrl: './collaterals-types-edit.html'
})
export class CollateralTypesEdit implements OnInit, OnDestroy {

  typeId!: number;
  isLoading = false;
  isSubmitting = false;
  errors: { [key: string]: string[] } = {};

  form: CollateralTypePayload = { name: '' };

  private destroy$ = new Subject<void>();

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private collateralService: CollateralService,
    private toast: ToastService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.typeId = Number(this.route.snapshot.paramMap.get('id'));
    
    if (!this.typeId || isNaN(this.typeId)) {
      this.toast.error('Error', 'Invalid collateral type ID');
      this.router.navigate(['/admin/collateral-types']);
      return;
    }
    
    this.loadType();
  }

  ngOnDestroy(): void {
    this.destroy$.next();
    this.destroy$.complete();
  }

  loadType(): void {
    this.isLoading = true;
    this.cdr.detectChanges(); // Force detect changes

    this.collateralService.getCollateralType(this.typeId)
      .pipe(
        takeUntil(this.destroy$),
        finalize(() => {
          this.isLoading = false;
          this.cdr.detectChanges(); // Update UI after loading
        })
      )
      .subscribe({
        next: (res) => {
          if (res.success && res.data) {
            this.form.name = res.data.name;
          }
        },
        error: () => {
          this.toast.error('Error', 'Failed to load collateral type');
          this.router.navigate(['/admin/collateral-types']);
        }
      });
  }

  onSubmit(): void {
    if (!this.form.name?.trim()) {
      this.toast.error('Error', 'Collateral type name is required');
      return;
    }

    this.isSubmitting = true;
    this.errors = {};
    this.cdr.detectChanges();

    this.collateralService.updateCollateralType(this.typeId, this.form)
      .pipe(
        takeUntil(this.destroy$),
        finalize(() => {
          this.isSubmitting = false;
          this.cdr.detectChanges();
        })
      )
      .subscribe({
        next: (res) => {
          this.toast.success('Success', res.message || 'Collateral type updated successfully');
          this.router.navigate(['/admin/collateral-types']);
        },
        error: (err) => {
          if (err.status === 422) {
            this.errors = err.error.errors || {};
            this.toast.error('Validation Error', err.error.message || 'Please check the form');
          } else {
            this.toast.error('Error', err.error?.message || 'Failed to update collateral type');
          }
          this.cdr.detectChanges();
        }
      });
  }

  cancel(): void {
    this.router.navigate(['/admin/collateral-types']);
  }
}