// src/app/components/admin/pages/users/user-add/user-add.ts
import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router, RouterModule } from '@angular/router';
import { UserService } from '../../../../../services/user.service';
import { RoleService } from '../../../../../services/role.service';
import { ToastService } from '../../../../../services/toast.service';
import { UserPayload } from '../../../../../models/user.model';
import { Role } from '../../../../../models/role.model';

@Component({
  selector: 'app-user-add',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterModule],
  templateUrl: './user-add.html'
})
export class UserAdd implements OnInit {

  form: UserPayload = {
    role_id: null,
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    password: '',
    status: 'active'
  };

  roles: Role[] = [];
  isSubmitting = false;
  isLoadingRoles = false;
  errors: { [key: string]: string[] } = {};

  constructor(
    private userService: UserService,
    private roleService: RoleService,
    private toast: ToastService,
    private router: Router,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.loadRoles();
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

  onSubmit(): void {
    if (!this.form.role_id) {
      this.toast.error('Error', 'Please select a role');
      return;
    }

    this.isSubmitting = true;
    this.errors = {};

    this.userService.createUser(this.form).subscribe({
      next: (res) => {
        this.isSubmitting = false;
        this.toast.success('Success', res.message || 'User created successfully');
        this.router.navigate(['/admin/users']);
      },
      error: (err) => {
        this.isSubmitting = false;
        if (err.status === 422) {
          this.errors = err.error.errors || {};
          this.toast.error('Validation Error', err.error.message || 'Please check the form');
        } else {
          this.toast.error('Error', err.error?.message || 'Failed to create user');
        }
        this.cdr.detectChanges();
      }
    });
  }
}