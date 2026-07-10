import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router, RouterModule } from '@angular/router';
import { CollateralService } from '../../../../../services/collateral.service';
import { ToastService } from '../../../../../services/toast.service';
import { CollateralTypePayload } from '../../../../../models/collateral-type.model';

@Component({
  selector: 'app-collateral-types-add',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterModule],
  templateUrl: './collaterals-types-add.html'
})
export class CollateralTypesAdd {

  form: CollateralTypePayload = { name: '' };
  isSubmitting = false;
  errors: { [key: string]: string[] } = {};

  constructor(
    private collateralService: CollateralService,
    private toast: ToastService,
    private router: Router
  ) {}

  onSubmit(): void {
    if (!this.form.name.trim()) {
      this.toast.error('Error', 'Collateral type name is required');
      return;
    }

    this.isSubmitting = true;
    this.errors = {};

    this.collateralService.createCollateralType(this.form).subscribe({
      next: (res) => {
        this.isSubmitting = false;
        this.toast.success('Success', res.message || 'Collateral type created successfully');
        this.router.navigate(['/admin/collateral-types']);
      },
      error: (err) => {
        this.isSubmitting = false;
        if (err.status === 422) {
          this.errors = err.error.errors || {};
          this.toast.error('Validation Error', err.error.message || 'Please check the form');
        } else {
          this.toast.error('Error', err.error?.message || 'Failed to create collateral type');
        }
      }
    });
  }
}