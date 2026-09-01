import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { RoleService } from '../../../../../services/role.service';
import { ToastService } from '../../../../../services/toast.service';
import { RolePayload } from '../../../../../models/role.model';

@Component({
  selector: 'app-role-add',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink],
  templateUrl: './role-add.html'
})
export class RoleAdd {

  form: RolePayload = { name: '', description: '' };
  isSubmitting = false;
  errors: { [key: string]: string[] } = {};

  constructor(
    private roleService: RoleService,
    private toast: ToastService,
    private router: Router
  ) {}

  onSubmit(): void {
    if (!this.form.name.trim()) {
      this.toast.error('Error', 'Fill the fields');
      return;
    }

    this.isSubmitting = true;
    this.errors = {};

    this.roleService.createRole(this.form).subscribe({
      next: (res) => {
        this.isSubmitting = false;
        this.toast.success('Success', res.message || 'Role added');
        this.router.navigate(['/admin/roles']);
      },
      error: (err) => {
        this.isSubmitting = false;
        if (err.status === 422) {
          this.errors = err.error.errors || {};
          this.toast.error('Validation Error', err.error.message || 'Form check karein');
        } else {
          this.toast.error('Error', 'no role found');
        }
      }
    });
  }
}