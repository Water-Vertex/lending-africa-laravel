import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { LoanProduct, LoanProductResponse, LoanProductSingleResponse } from '../models/loan-product.model';
import { environment } from '../../environments/environment';

@Injectable({ providedIn: 'root' })
export class LoanProductService {
  private http   = inject(HttpClient);
  private base   = `${environment.apiUrl}/admin/loan-products`;

  getAll(filters?: { search?: string; status?: string; loan_type?: string }): Observable<LoanProductResponse> {
    let params = new HttpParams();
    if (filters?.search)    params = params.set('search',    filters.search);
    if (filters?.status)    params = params.set('status',    filters.status);
    if (filters?.loan_type) params = params.set('loan_type', filters.loan_type);
    return this.http.get<LoanProductResponse>(this.base, { params });
  }

  getOne(id: number): Observable<LoanProductSingleResponse> {
    return this.http.get<LoanProductSingleResponse>(`${this.base}/${id}`);
  }

  create(data: LoanProduct): Observable<LoanProductSingleResponse> {
    return this.http.post<LoanProductSingleResponse>(this.base, data);
  }

  update(id: number, data: LoanProduct): Observable<LoanProductSingleResponse> {
    return this.http.put<LoanProductSingleResponse>(`${this.base}/${id}`, data);
  }

  delete(id: number): Observable<any> {
    return this.http.delete(`${this.base}/${id}`);
  }

  toggleStatus(id: number): Observable<LoanProductSingleResponse> {
    return this.http.patch<LoanProductSingleResponse>(`${this.base}/${id}/toggle-status`, {});
  }
}