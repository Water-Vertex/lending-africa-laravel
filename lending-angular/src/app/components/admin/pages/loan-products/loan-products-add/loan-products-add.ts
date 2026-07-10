// import { Component, OnInit, inject } from '@angular/core';
// import { CommonModule } from '@angular/common';
// import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
// import { Router, ActivatedRoute, RouterModule } from '@angular/router';
// import { LoanProductService } from '../../../../../services/loan-product.service';

// @Component({
//   selector: 'app-loan-product-form',
//   standalone: true,
//   imports: [CommonModule, ReactiveFormsModule, RouterModule],
//   templateUrl: './loan-products-add.html',
// })
// export class LoanProductForm implements OnInit {
//   private fb      = inject(FormBuilder);
//   private svc     = inject(LoanProductService);
//   private router  = inject(Router);
//   private route   = inject(ActivatedRoute);

//   form!: FormGroup;
//   isEdit   = false;
//   editId: number | null = null;
//   loading  = false;   // page loading (edit mode)
//   saving   = false;
//   errorMsg = '';

//   ngOnInit(): void {
//     this.buildForm();

//     const id = this.route.snapshot.paramMap.get('id');
//     if (id) {
//       this.isEdit = true;
//       this.editId = +id;
//       this.loadProduct(this.editId);
//     }
//   }

//   buildForm(): void {
//     this.form = this.fb.group({
//       name:            ['', [Validators.required, Validators.maxLength(150)]],
//       loan_type:       ['personal', Validators.required],
//       minimum_amount:  [null, [Validators.required, Validators.min(0)]],
//       maximum_amount:  [null, [Validators.required, Validators.min(0)]],
//       interest_rate:   [null, [Validators.required, Validators.min(0), Validators.max(100)]],
//       processing_fee:  [0],
//       late_fee:        [0],
//       duration_months: [null, [Validators.required, Validators.min(1), Validators.max(360)]],
//       description:     [''],
//       status:          ['active', Validators.required],
//     });
//   }

//   loadProduct(id: number): void {
//     this.loading = true;
//     this.svc.getOne(id).subscribe({
//       next : res => { this.form.patchValue(res.data); this.loading = false; },
//       error: ()  => { this.errorMsg = 'Failed to load product.'; this.loading = false; },
//     });
//   }

//  submit(): void {
//   if (this.form.invalid) {
//     this.form.markAllAsTouched();
//     return;
//   }

//   this.saving  = true;
//   this.errorMsg = '';

//   const payload = this.form.value;
//   const call    = this.isEdit
//     ? this.svc.update(this.editId!, payload)
//     : this.svc.create(payload);

//   call.subscribe({
//     next : () => {
//       this.saving = false;
//       // 👇 success toast + sahi path par navigate
//       this.showToast(this.isEdit ? 'Loan product updated successfully!' : 'Loan product created successfully!');
//       setTimeout(() => {
//         this.router.navigate(['/admin/loan-products/list']);
//       }, 900);
//     },
//     error: (e) => {
//       console.error('Validation errors:', e.error?.errors);
//       this.errorMsg = e.error?.message || 'Save failed.';
//       this.saving = false;
//     },
//   });
// }

// toastMsg = '';
// showToast(msg: string): void {
//   this.toastMsg = msg;
//   setTimeout(() => this.toastMsg = '', 3000);
// }

//   // Helpers for validation display
//   f(name: string) { return this.form.get(name); }
//   err(name: string) { return this.f(name)?.invalid && this.f(name)?.touched; }
// }

import { Component, OnInit, inject, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router, ActivatedRoute, RouterModule } from '@angular/router';
import { LoanProductService } from '../../../../../services/loan-product.service';

@Component({
  selector: 'app-loan-product-form',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, RouterModule],
  templateUrl: './loan-products-add.html',
})
export class LoanProductForm implements OnInit {
  private fb      = inject(FormBuilder);
  private svc     = inject(LoanProductService);
  private router  = inject(Router);
  private route   = inject(ActivatedRoute);
  private cdr     = inject(ChangeDetectorRef);   // 👈 added

  form!: FormGroup;
  isEdit   = false;
  editId: number | null = null;
  loading  = false;
  saving   = false;
  errorMsg = '';
  toastMsg = '';

  ngOnInit(): void {
    this.buildForm();

    const id = this.route.snapshot.paramMap.get('id');
    if (id) {
      this.isEdit = true;
      this.editId = +id;
      this.loadProduct(this.editId);
    }
  }

  buildForm(): void {
    this.form = this.fb.group({
      name:            ['', [Validators.required, Validators.maxLength(150)]],
      loan_type:       ['personal', Validators.required],
      minimum_amount:  [null, [Validators.required, Validators.min(0)]],
      maximum_amount:  [null, [Validators.required, Validators.min(0)]],
      interest_rate:   [null, [Validators.required, Validators.min(0), Validators.max(100)]],
      processing_fee:  [0],
      late_fee:        [0],
      duration_months: [null, [Validators.required, Validators.min(1), Validators.max(360)]],
      description:     [''],
      status:          ['active', Validators.required],
    });
  }

  loadProduct(id: number): void {
    this.loading = true;
    this.svc.getOne(id).subscribe({
      next : res => {
        this.form.patchValue(res.data);
        this.loading = false;
        this.cdr.markForCheck();   // 👈 added
      },
      error: () => {
        this.errorMsg = 'Failed to load product.';
        this.loading = false;
        this.cdr.markForCheck();   // 👈 added
      },
    });
  }

  submit(): void {
    if (this.form.invalid) {
      this.form.markAllAsTouched();
      return;
    }

    this.saving  = true;
    this.errorMsg = '';

    const payload = this.form.value;
    const call    = this.isEdit
      ? this.svc.update(this.editId!, payload)
      : this.svc.create(payload);

    call.subscribe({
      next : () => {
        this.saving = false;
        this.showToast(this.isEdit ? 'Loan product updated successfully!' : 'Loan product created successfully!');
        this.cdr.markForCheck();   // 👈 added
        setTimeout(() => {
          this.router.navigate(['/admin/loan-products/list']);
        }, 900);
      },
      error: (e) => {
        console.error('Validation errors:', e.error?.errors);
        this.errorMsg = e.error?.message || 'Save failed.';
        this.saving = false;
        this.cdr.markForCheck();   // 👈 added
      },
    });
  }

  showToast(msg: string): void {
    this.toastMsg = msg;
    this.cdr.markForCheck();       // 👈 added
    setTimeout(() => {
      this.toastMsg = '';
      this.cdr.markForCheck();     // 👈 added
    }, 3000);
  }

  // Helpers for validation display
  f(name: string) { return this.form.get(name); }
  err(name: string) { return this.f(name)?.invalid && this.f(name)?.touched; }
}