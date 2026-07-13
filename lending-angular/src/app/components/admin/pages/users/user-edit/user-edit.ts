// src/app/components/admin/pages/users/user-edit/user-edit.ts
import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
import { UserService } from '../../../../../services/user.service';
import { RoleService } from '../../../../../services/role.service';
import { ToastService } from '../../../../../services/toast.service';
import { UserPayload } from '../../../../../models/user.model';
import { Role } from '../../../../../models/role.model';

@Component({
  selector: 'app-user-edit',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterModule],
  templateUrl: './user-edit.html'
})
export class UserEdit implements OnInit {

  userId!: number;
  isLoading = false;
  isLoadingRoles = false;
  isSubmitting = false;
  errors: { [key: string]: string[] } = {};

  roles: Role[] = [];

  form: UserPayload = {
    role_id: null,
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    password: '',
    status: 'active'
  };

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private userService: UserService,
    private roleService: RoleService,
    private toast: ToastService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.userId = Number(this.route.snapshot.paramMap.get('id'));
    this.loadRoles();
    this.loadUser();
  }

  loadRoles(): void {
    this.isLoadingRoles = true;
    this.roleService.getRoles().subscribe({
      next: (res) => {
        if (res.success && res.data) {
          this.roles = res.data;
        }
        this.isLoadingRoles = false;
        this.cdr.detectChanges();
      },
      error: () => {
        this.isLoadingRoles = false;
        this.toast.error('Error', 'Failed to load roles');
        this.cdr.detectChanges();
      }
    });
  }

  loadUser(): void {
    this.isLoading = true;
    this.userService.getUser(this.userId).subscribe({
      next: (res) => {
        const user = res.data;
        this.form.role_id = user.role_id;
        this.form.first_name = user.first_name;
        this.form.last_name = user.last_name;
        this.form.email = user.email;
        this.form.phone = user.phone;
        this.form.status = user.status;
        this.isLoading = false;
        this.cdr.detectChanges();
      },
      error: () => {
        this.isLoading = false;
        this.toast.error('Error', 'Failed to load user data');
        this.router.navigate(['/admin/users']);
      }
    });
  }

  onSubmit(): void {
    if (!this.form.role_id) {
      this.toast.error('Error', 'Please select a role');
      return;
    }

    this.isSubmitting = true;
    this.errors = {};

    // Agar password field khali hai to payload se remove kar dein
    const payload: UserPayload = { ...this.form };
    if (!payload.password) {
      delete payload.password;
    }

    this.userService.updateUser(this.userId, payload).subscribe({
      next: (res) => {
        this.isSubmitting = false;
        this.toast.success('Success', res.message || 'User updated successfully');
        this.router.navigate(['/admin/users']);
      },
      error: (err) => {
        this.isSubmitting = false;
        if (err.status === 422) {
          this.errors = err.error.errors || {};
          this.toast.error('Validation Error', err.error.message || 'Please check the form');
        } else {
          this.toast.error('Error', err.error?.message || 'Failed to update user');
        }
        this.cdr.detectChanges();
      }
    });
  }

  cancel(): void {
    this.router.navigate(['/admin/users']);
  }
}