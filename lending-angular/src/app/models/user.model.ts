
import { Role } from './role.model';

export interface UserPayload {
  role_id: number | null;
  first_name: string;
  last_name: string;
  email: string;
  phone?: string;
  password?: string;
  status: string;
} 

export interface LoginResponse {
  success: boolean;
  user: User;
  token: string;
  message?: string;
}

export interface LoginRequest {
  email: string;
  password: string;
}

// Add this interface
export interface LogoutResponse {
  success: boolean;
  message: string;
}

// Optional: Add Auth Response base interface
export interface AuthResponse {
  success: boolean;
  message?: string;
}

export interface User {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  phone?: string;
  role?: Role;
  status: string;
    created_at?: string;
  updated_at?: string;
}