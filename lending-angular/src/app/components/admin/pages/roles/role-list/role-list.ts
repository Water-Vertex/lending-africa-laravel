// src/app/components/admin/pages/roles/role-list/role-list.ts
import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink, RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { RoleService } from '../../../../../services/role.service';
import { ToastService } from '../../../../../services/toast.service';
import { Role } from '../../../../../models/role.model';

@Component({
  selector: 'app-role-list',
  standalone: true,
  imports: [CommonModule, RouterModule, FormsModule],
  templateUrl: './role-list.html'
})
export class RoleList implements OnInit {

  roles: Role[] = [];
  filteredRoles: Role[] = [];
  isLoading = false;
  searchTerm = '';

  constructor(
    private roleService: RoleService,
    private toast: ToastService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.loadRoles();
  }

  loadRoles(): void {
    this.isLoading = true;
    this.cdr.detectChanges();
    
    this.roleService.getRoles().subscribe({
      next: (res) => {
        if (res.success && res.data) {
          this.roles = res.data;
          this.filteredRoles = [...res.data];
        }
        this.isLoading = false;
        this.cdr.detectChanges();
      },
      error: (err) => {
        this.isLoading = false;
        this.cdr.detectChanges();
        this.toast.error('Error', 'Failed to load roles');
        console.error('Error loading roles:', err);
      }
    });
  }

  refreshData(): void {
    this.searchTerm = '';
    this.loadRoles();
  }

  onSearch(): void {
    const term = this.searchTerm.trim().toLowerCase();
    if (!term) {
      this.filteredRoles = [...this.roles];
    } else {
      this.filteredRoles = this.roles.filter(r =>
        r.name.toLowerCase().includes(term) ||
        (r.description || '').toLowerCase().includes(term)
      );
    }
    this.cdr.detectChanges();
  }

  deleteRole(id: number): void {
    if (!confirm('Are you sure you want to delete this role?')) return;

    this.roleService.deleteRole(id).subscribe({
      next: (res) => {
        this.toast.success('Success', res.message || 'Role deleted successfully');
        this.roles = this.roles.filter(r => r.id !== id);
        this.filteredRoles = this.filteredRoles.filter(r => r.id !== id);
        this.cdr.detectChanges();
      },
      error: (err) => {
        this.toast.error('Error', err.error?.message || 'Failed to delete role');
        console.error('Error deleting role:', err);
        this.cdr.detectChanges();
      }
    });
  }

  trackById(index: number, role: Role): number {
    return role.id;
  }
}