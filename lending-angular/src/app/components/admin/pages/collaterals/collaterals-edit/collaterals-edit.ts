import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
import { CollateralService } from '../../../../../services/collateral.service';
import { ToastService } from '../../../../../services/toast.service';
import { CollateralPayload } from '../../../../../models/collateral.model';
import { CollateralType } from '../../../../../models/collateral-type.model';

@Component({
  selector: 'app-collaterals-edit',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterModule],
  templateUrl: './collaterals-edit.html'
})
export class CollateralsEdit implements OnInit {

  collateralId!: number;
  isLoading = false;
  isLoadingTypes = false;
  isSubmitting = false;
  errors: { [key: string]: string[] } = {};

  types: CollateralType[] = [];

  form: CollateralPayload = {
    application_id: null,
    collateral_type_id: null,
    asset_name: '',
    estimated_value: null,
    ownership_document_no: '',
    verification_status: 'pending'
  };

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private collateralService: CollateralService,
    private toast: ToastService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.collateralId = Number(this.route.snapshot.paramMap.get('id'));
    this.loadTypes();
    this.loadCollateral();
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

  loadCollateral(): void {
    this.isLoading = true;
    this.collateralService.getCollateral(this.collateralId).subscribe({
      next: (res) => {
        const c = res.data;
        this.form.application_id = c.application_id;
        this.form.collateral_type_id = c.collateral_type_id;
        this.form.asset_name = c.asset_name;
        this.form.estimated_value = c.estimated_value;
        this.form.ownership_document_no = c.ownership_document_no;
        this.form.verification_status = c.verification_status;
        this.isLoading = false;
        this.cdr.detectChanges();
      },
      error: () => {
        this.isLoading = false;
        this.toast.error('Error', 'Failed to load collateral data');
        this.router.navigate(['/admin/collaterals']);
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

    this.collateralService.updateCollateral(this.collateralId, this.form).subscribe({
      next: (res) => {
        this.isSubmitting = false;
        this.toast.success('Success', res.message || 'Collateral updated successfully');
        this.router.navigate(['/admin/collaterals']);
      },
      error: (err) => {
        this.isSubmitting = false;
        if (err.status === 422) {
          this.errors = err.error.errors || {};
          this.toast.error('Validation Error', err.error.message || 'Please check the form');
        } else {
          this.toast.error('Error', err.error?.message || 'Failed to update collateral');
        }
        this.cdr.detectChanges();
      }
    });
  }

  cancel(): void {
    this.router.navigate(['/admin/collaterals']);
  }
}