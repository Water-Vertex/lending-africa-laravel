export interface DashboardStats {
  // Customers
  total_customers: number;
  active_customers: number;
  blacklisted: number;
  personal_customers: number;
  sme_customers: number;

  // Applications
  total_applications: number;
  pending_applications: number;
  approved_loans: number;
  disbursed_loans: number;
  rejected_loans: number;
  closed_loans: number;

  // Financial
  total_disbursed: number;
  total_repaid: number;
  pending_repayments: number;

  // Products & Staff
  total_products: number;
  total_staff: number;
}

export interface DashboardResponse {
  success: boolean;
  data: DashboardStats;
}