import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router, RouterModule } from '@angular/router';
import { CollateralService } from '../../../../../services/collateral.service';
import { ToastService } from '../../../../../services/toast.service';
import { CollateralPayload } from '../../../../../models/collateral.model';
import { CollateralType } from '../../../../../models/collateral-type.model';

@Component({
  selector: 'app-collaterals-add',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterModule],
  templateUrl: './collaterals-add.html'
})
export class CollateralsAdd implements OnInit {

  form: CollateralPayload = {
    application_id: null,
    collateral_type_id: null,
    asset_name: '',
    estimated_value: null,
    ownership_document_no: '',
    verification_status: 'pending'
  };

  types: CollateralType[] = [];
  isSubmitting = false;
  isLoadingTypes = false;
  errors: { [key: string]: string[] } = {};

  constructor(
    private collateralService: CollateralService,
    private toast: ToastService,
    private router: Router,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.loadTypes();
  }

  loadTypes(): void {
    this.isLoadingTypes = true;
    this.collateralService.getCollateralTypes().subscribe({
      next: (res) => {
        if (res.success && res.data) {
          this.types = res.data;
        }
        this.isLoadingTypes = false;
        this.cdr.detectChanges();
      },
      error: () => {
        this.isLoadingTypes = false;
        this.toast.error('Error', 'Failed to load collateral types');
        this.cdr.detectChanges();
      }
    });
  }

  onSubmit(): void {
    if (!this.form.collateral_type_id) {
      this.toast.error('Error', 'Please select a collateral type');
      return;
    }

    this.isSubmitting = true;
    this.errors = {};

    this.collateralService.createCollateral(this.form).subscribe({
      next: (res) => {
        this.isSubmitting = false;
        this.toast.success('Success', res.message || 'Collateral created successfully');
        this.router.navigate(['/admin/collaterals']);
      },
      error: (err) => {
        this.isSubmitting = false;
        if (err.status === 422) {
          this.errors = err.error.errors || {};
          this.toast.error('Validation Error', err.error.message || 'Please check the form');
        } else {
          this.toast.error('Error', err.error?.message || 'Failed to create collateral');
        }
        this.cdr.detectChanges();
      }
    });
  }
}