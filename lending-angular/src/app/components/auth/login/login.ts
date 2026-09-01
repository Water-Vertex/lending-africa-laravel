import { Component, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { AuthService } from '../../../services/auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './login.html',
})
export class Login {
  private fb          = inject(FormBuilder);
  private authService = inject(AuthService);
  private router      = inject(Router);


  loginForm: FormGroup;
  isLoading    = false;
  showPassword = false;
  currentYear  = new Date().getFullYear();
  successMsg   = '';
  errorMsg     = '';

  constructor() {
    this.loginForm = this.fb.group({
      email:    ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(6)]],
      remember: [false],
    });
  }

  onSubmit(): void {
    this.successMsg = '';
    this.errorMsg   = '';

    if (this.loginForm.valid) {
      this.isLoading = true;

      this.authService.login(this.loginForm.value).subscribe({
        next: (response) => {
          this.isLoading  = false;
          // this.successMsg = 'Login successful! Dashboard coming soon.';
          this.router.navigate(['/admin/dashboard']);
        },
        error: (error) => {
          this.isLoading = false;
          this.errorMsg  = error.error?.message || 'Login failed. Please check your credentials.';
        },
      });
    } else {
      Object.keys(this.loginForm.controls).forEach(key =>
        this.loginForm.get(key)?.markAsTouched()
      );
    }
  }
}
