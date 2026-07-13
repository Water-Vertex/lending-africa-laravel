export interface CollateralType {
  id: number;
  name: string;
  created_at?: string;
  updated_at?: string;
}

export interface CollateralTypePayload {
  name: string;
}