// src/app/components/admin/pages/roles/role-edit/role-edit.ts
import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { RoleService } from '../../../../../services/role.service';
import { ToastService } from '../../../../../services/toast.service';
import { RolePayload } from '../../../../../models/role.model';

@Component({
  selector: 'app-role-edit',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink],
  templateUrl: './role-edit.html'
})
export class RoleEdit implements OnInit {

  roleId!: number;
  isLoading = false;
  isSubmitting = false;
  errors: { [key: string]: string[] } = {};

  form: RolePayload = { name: '', description: '' };

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private roleService: RoleService,
    private toast: ToastService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.roleId = Number(this.route.snapshot.paramMap.get('id'));
    this.loadRole();
  }

  loadRole(): void {
    this.isLoading = true;
    this.cdr.detectChanges();
    
    this.roleService.getRole(this.roleId).subscribe({
      next: (res) => {
        if (res.success && res.data) {
          this.form.name = res.data.name || '';
          this.form.description = res.data.description || '';
        }
        this.isLoading = false;
        this.cdr.detectChanges();
      },
      error: (err) => {
        this.isLoading = false;
        this.cdr.detectChanges();
        this.toast.error('Error', 'Failed to load role data');
        console.error('Error loading role:', err);
        this.router.navigate(['/admin/roles']);
      }
    });
  }

  onSubmit(): void {
    if (!this.form.name.trim()) {
      this.toast.error('Error', 'Role name is required');
      return;
    }

    this.isSubmitting = true;
    this.errors = {};
    this.cdr.detectChanges();

    this.roleService.updateRole(this.roleId, this.form).subscribe({
      next: (res) => {
        this.isSubmitting = false;
        this.cdr.detectChanges();
        this.toast.success('Success', res.message || 'Role updated successfully');
        this.router.navigate(['/admin/roles']);
      },
      error: (err) => {
        this.isSubmitting = false;
        this.cdr.detectChanges();
        if (err.status === 422) {
          this.errors = err.error.errors || {};
          this.toast.error('Validation Error', err.error.message || 'Please check the form');
        } else {
          this.toast.error('Error', err.error?.message || 'Failed to update role');
        }
        console.error('Error updating role:', err);
      }
    });
  }

  cancel(): void {
    this.router.navigate(['/admin/roles']);
  }
}