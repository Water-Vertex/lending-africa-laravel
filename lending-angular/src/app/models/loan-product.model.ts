export interface LoanProduct {
  id?: number;
  name: string;
  loan_type: 'personal' | 'sme';
  minimum_amount: number;
  maximum_amount: number;
  interest_rate: number;
  processing_fee: number;
  late_fee: number;
  duration_months: number;
  description?: string;
  status: 'active' | 'inactive';
  created_at?: string;
  updated_at?: string;
}

export interface LoanProductResponse {
  success: boolean;
  data: LoanProduct[];
  total: number;
}

export interface LoanProductSingleResponse {
  success: boolean;
  data: LoanProduct;
  message?: string;
}