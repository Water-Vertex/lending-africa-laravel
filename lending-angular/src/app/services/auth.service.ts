import { Injectable, Inject, PLATFORM_ID } from '@angular/core';
import { HttpClient, HttpHeaders  } from '@angular/common/http';
import { Observable, BehaviorSubject } from 'rxjs';
import { tap } from 'rxjs/operators';
import { Router } from '@angular/router'; // Add Router
import { LoginRequest, LoginResponse, AuthResponse, User } from '../models/user.model';
import { environment } from '../../environments/environment';
import { isPlatformBrowser } from '@angular/common';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
private adminApiUrl = environment.adminApiUrl;  
  private currentUserSubject = new BehaviorSubject<User | null>(null);
  currentUser$ = this.currentUserSubject.asObservable();
  private isInitialized = false;

  constructor(
    private http: HttpClient,
    private router: Router, // Inject Router
    @Inject(PLATFORM_ID) private platformId: Object
  ) {
    // Initialize immediately
    this.initialize();
  }

  private initialize(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.loadUserFromStorage();
      this.isInitialized = true;
    }
  }

  private storeAuth(token: string, userData: any) {
  if (isPlatformBrowser(this.platformId)) {
    localStorage.setItem('token', token);
    localStorage.setItem('user', JSON.stringify(userData));
    this.currentUserSubject.next(userData);
  }
}

login(credentials: LoginRequest) {
    return this.http.post<any>(
        `${this.adminApiUrl}/login`,
        credentials
    ).pipe(
        tap(res => {
            if (res.success) {
                this.storeAuth(res.token, res.user);
            }
        })
    );
}





  logout(): Observable<any> {
    const token = this.getToken();

    // Prepare headers
    let headers = new HttpHeaders({
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    });

    if (token) {
      headers = headers.set('Authorization', `Bearer ${token}`);
    }

    return this.http.post(`${this.adminApiUrl}/logout`, {}, { headers }).pipe(
      tap({
        next: (response: any) => {
          console.log('Logout successful:', response);
          this.clearAuthData();
          this.router.navigate(['/login']); // Redirect after successful logout
        },
        error: (error) => {
          console.error('Logout error:', error);
          // Even if API call fails, clear local data
          this.clearAuthData();
          this.router.navigate(['/login']);
        },
        complete: () => {
          // Fallback - ensure data is cleared
          this.clearAuthData();
        }
      })
    );
  }



  // Clear auth data and redirect
  clearAuthData(): void {
    this.clearStorage();
    this.currentUserSubject.next(null);

  }

  isLoggedIn(): boolean {
    if (isPlatformBrowser(this.platformId)) {
      return !!this.getToken();
    }
    return false;
  }

  getCurrentUser(): User | null {
    return this.currentUserSubject.value;
  }

  // Wait for initialization
  waitForInitialization(): Promise<boolean> {
    return new Promise((resolve) => {
      if (this.isInitialized) {
        resolve(this.isLoggedIn());
      } else {
        // Check every 100ms until initialized
        const interval = setInterval(() => {
          if (this.isInitialized) {
            clearInterval(interval);
            resolve(this.isLoggedIn());
          }
        }, 100);
      }
    });
  }

  // Token management
  private setToken(token: string): void {
    if (isPlatformBrowser(this.platformId)) {
      localStorage.setItem('token', token);
    }
  }

  private getToken(): string | null {
    if (isPlatformBrowser(this.platformId)) {
      return localStorage.getItem('token');
    }
    return null;
  }

  private setUser(user: User): void {
    if (isPlatformBrowser(this.platformId)) {
      localStorage.setItem('user', JSON.stringify(user));
    }
  }

  private getUser(): User | null {
  if (!isPlatformBrowser(this.platformId)) return null;

  const userStr = localStorage.getItem('user');

  if (!userStr || userStr === 'undefined' || userStr === 'null') {
    return null;
  }

  try {
    return JSON.parse(userStr);
  } catch (error) {
    console.error('Invalid JSON in storage:', error);
    localStorage.removeItem('user');
    return null;
  }
}


  private loadUserFromStorage(): void {
    const token = this.getToken();
    const user = this.getUser();
    if (token && user) {
      this.currentUserSubject.next(user);
    } else {
      this.currentUserSubject.next(null);
    }
  }

  private clearStorage(): void {
    if (isPlatformBrowser(this.platformId)) {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
    }
  }


}


// import { Injectable, inject } from '@angular/core';
// import { HttpClient } from '@angular/common/http';
// import { BehaviorSubject, Observable, tap } from 'rxjs';
// import { User } from '../models/user.model';
// import { environment } from '../../environments/environment';

// @Injectable({ providedIn: 'root' })
// export class AuthService {
//   private http = inject(HttpClient);
//   private apiUrl = environment.apiUrl;

//   private currentUserSubject = new BehaviorSubject<User | null>(this.loadUserFromStorage());
//   currentUser$ = this.currentUserSubject.asObservable();

//   // ── Helpers ──────────────────────────────────────────────────
//   private loadUserFromStorage(): User | null {
//     try {
//       const u = localStorage.getItem('aip_user');
//       return u ? JSON.parse(u) : null;
//     } catch {
//       return null;
//     }
//   }

//   getCurrentUser(): User | null {
//     return this.currentUserSubject.value;
//   }

//   getToken(): string | null {
//     return localStorage.getItem('aip_token');
//   }

//   isLoggedIn(): boolean {
//     return !!this.getToken();
//   }

//   // ── API calls ─────────────────────────────────────────────────
//   login(credentials: { email: string; password: string; remember?: boolean }): Observable<any> {
//     return this.http.post(`${this.apiUrl}/admin/login`, credentials).pipe(
//       tap((res: any) => {
//         if (res.success) {
//           localStorage.setItem('aip_token', res.token);
//           localStorage.setItem('aip_user', JSON.stringify(res.user));
//           this.currentUserSubject.next(res.user);
//         }
//       })
//     );
//   }

//   logout(): Observable<any> {
//     return this.http.post(`${this.apiUrl}/admin/logout`, {}).pipe(
//       tap(() => this.clearSession())
//     );
//   }

//   clearSession(): void {
//     localStorage.removeItem('aip_token');
//     localStorage.removeItem('aip_user');
//     this.currentUserSubject.next(null);
//   }
// }