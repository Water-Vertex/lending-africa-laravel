import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterModule } from '@angular/router';
import { AuthService } from '../../../../services/auth.service';
import { User } from '../../../../models/user.model';

@Component({
  selector: 'app-admin-layout',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './admin-layout.html',
})
export class AdminLayout implements OnInit {
  private authService = inject(AuthService);
  private router      = inject(Router);

  currentUser: User | null = null;
  userInitials = 'A';
  sidebarOpen  = false;
  userMenuOpen = false;

  ngOnInit(): void {
    this.authService.currentUser$.subscribe(user => {
      this.currentUser = user;
      this.setUserInitials();
    });

    if (!this.currentUser) {
      this.currentUser = this.authService.getCurrentUser();
      this.setUserInitials();
    }
  }

  setUserInitials(): void {
    if (this.currentUser?.first_name) {
      this.userInitials = this.currentUser.first_name.charAt(0).toUpperCase();
    } else if (this.currentUser?.email) {
      this.userInitials = this.currentUser.email.charAt(0).toUpperCase();
    }
  }

  getFullName(): string {
    if (this.currentUser?.first_name && this.currentUser?.last_name) {
      return `${this.currentUser.first_name} ${this.currentUser.last_name}`;
    }
    return 'Admin';
  }

  getEmail(): string {
    return this.currentUser?.email || '';
  }

  toggleSidebar(): void {
    this.sidebarOpen = !this.sidebarOpen;
  }

  closeSidebar(): void {
    this.sidebarOpen = false;
  }

  toggleUserMenu(): void {
    this.userMenuOpen = !this.userMenuOpen;
  }

  logout(): void {
    this.authService.logout().subscribe({
      next: () => this.router.navigate(['/admin/login']),
      error: () => {
        // Even if API call fails, clear local session
        this.authService.clearAuthData();
        this.router.navigate(['/admin/login']);
      }
    });
  }
}