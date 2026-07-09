import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';
import { environment } from '../../environments/environment';
import { CollateralPayload } from '../models/collateral.model';
import { CollateralTypePayload } from '../models/collateral-type.model';

@Injectable({
  providedIn: 'root'
})
export class CollateralService {

  // Collaterals table ka URL
  private apiUrl = environment.adminApiUrl + '/collaterals';

  // Collateral Types table ka URL
  private typesApiUrl = environment.adminApiUrl + '/collateral-types';

  constructor(private http: HttpClient) {}

  private getHeaders(): HttpHeaders {
    const token = localStorage.getItem('token');
    let headers = new HttpHeaders({
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    });
    if (token) {
      headers = headers.set('Authorization', `Bearer ${token}`);
    }
    return headers;
  }

  /*
  |--------------------------------------------------------------------------
  | COLLATERALS (collaterals table)
  |--------------------------------------------------------------------------
  */

  getCollaterals(): Observable<any> {
    return this.http.get<any>(this.apiUrl, {
      headers: this.getHeaders()
    }).pipe(
      catchError(error => throwError(() => error))
    );
  }

  getCollateral(id: number): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/${id}`, {
      headers: this.getHeaders()
    }).pipe(
      map(response => {
        if (response.success !== undefined) return response;
        else if (response.id) return { success: true, data: response };
        else if (response.data) return { success: true, ...response };
        else throw new Error('Invalid response structure');
      }),
      catchError(error => throwError(() => error))
    );
  }

  createCollateral(data: CollateralPayload): Observable<any> {
    return this.http.post<any>(this.apiUrl, data, {
      headers: this.getHeaders()
    }).pipe(
      catchError(error => throwError(() => error))
    );
  }

  updateCollateral(id: number, data: CollateralPayload): Observable<any> {
    return this.http.put<any>(`${this.apiUrl}/${id}`, data, {
      headers: this.getHeaders()
    }).pipe(
      catchError(error => throwError(() => error))
    );
  }

  deleteCollateral(id: number): Observable<any> {
    return this.http.delete<any>(`${this.apiUrl}/${id}`, {
      headers: this.getHeaders()
    }).pipe(
      catchError(error => throwError(() => error))
    );
  }

  /*
  |--------------------------------------------------------------------------
  | COLLATERAL TYPES (collateral_types table)
  |--------------------------------------------------------------------------
  */

  getCollateralTypes(): Observable<any> {
    return this.http.get<any>(this.typesApiUrl, {
      headers: this.getHeaders()
    }).pipe(
      catchError(error => throwError(() => error))
    );
  }

  getCollateralType(id: number): Observable<any> {
    return this.http.get<any>(`${this.typesApiUrl}/${id}`, {
      headers: this.getHeaders()
    }).pipe(
      map(response => {
        if (response.success !== undefined) return response;
        else if (response.id) return { success: true, data: response };
        else if (response.data) return { success: true, ...response };
        else throw new Error('Invalid response structure');
      }),
      catchError(error => throwError(() => error))
    );
  }

  createCollateralType(data: CollateralTypePayload): Observable<any> {
    return this.http.post<any>(this.typesApiUrl, data, {
      headers: this.getHeaders()
    }).pipe(
      catchError(error => throwError(() => error))
    );
  }

  updateCollateralType(id: number, data: CollateralTypePayload): Observable<any> {
    return this.http.put<any>(`${this.typesApiUrl}/${id}`, data, {
      headers: this.getHeaders()
    }).pipe(
      catchError(error => throwError(() => error))
    );
  }

  deleteCollateralType(id: number): Observable<any> {
    return this.http.delete<any>(`${this.typesApiUrl}/${id}`, {
      headers: this.getHeaders()
    }).pipe(
      catchError(error => throwError(() => error))
    );
  }
}