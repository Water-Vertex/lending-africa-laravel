// src/app/components/admin/pages/users/user-list/user-list.ts
import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { UserService } from '../../../../../services/user.service';
import { ToastService } from '../../../../../services/toast.service';
import { User } from '../../../../../models/user.model';

@Component({
  selector: 'app-user-list',
  standalone: true,
  imports: [CommonModule, RouterModule, FormsModule],
  templateUrl: './user-list.html'
})
export class UserList implements OnInit {

  users: User[] = [];
  filteredUsers: User[] = [];
  isLoading = false;
  searchTerm = '';

  constructor(
    private userService: UserService,
    private toast: ToastService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.loadUsers();
  }

  loadUsers(): void {
    this.isLoading = true;
    this.cdr.detectChanges();

    this.userService.getUsers().subscribe({
      next: (res) => {
        if (res.success && res.data) {
          this.users = res.data;
          this.filteredUsers = [...res.data];
        }
        this.isLoading = false;
        this.cdr.detectChanges();
      },
      error: (err) => {
        this.isLoading = false;
        this.cdr.detectChanges();
        this.toast.error('Error', 'Failed to load users');
        console.error('Error loading users:', err);
      }
    });
  }

  refreshData(): void {
    this.searchTerm = '';
    this.loadUsers();
  }

  onSearch(): void {
    const term = this.searchTerm.trim().toLowerCase();
    if (!term) {
      this.filteredUsers = [...this.users];
    } else {
      this.filteredUsers = this.users.filter(u =>
        u.first_name.toLowerCase().includes(term) ||
        u.last_name.toLowerCase().includes(term) ||
        u.email.toLowerCase().includes(term) ||
        (u.role?.name || '').toLowerCase().includes(term)
      );
    }
    this.cdr.detectChanges();
  }

  deleteUser(id: number): void {
    if (!confirm('Are you sure you want to delete this user?')) return;

    this.userService.deleteUser(id).subscribe({
      next: (res) => {
        this.toast.success('Success', res.message || 'User deleted successfully');
        this.users = this.users.filter(u => u.id !== id);
        this.filteredUsers = this.filteredUsers.filter(u => u.id !== id);
        this.cdr.detectChanges();
      },
      error: (err) => {
        this.toast.error('Error', err.error?.message || 'Failed to delete user');
        console.error('Error deleting user:', err);
        this.cdr.detectChanges();
      }
    });
  }

  trackById(index: number, user: User): number {
    return user.id;
  }
}