import { CollateralType } from './collateral-type.model';

export interface Collateral {
  id: number;
  application_id: number;
  collateral_type_id: number;
  asset_name: string;
  estimated_value?: number | null;
  ownership_document_no?: string;
  verification_status: 'pending' | 'verified' | 'rejected';
  collateralType?: CollateralType;
  created_at?: string;
  updated_at?: string;
}

export interface CollateralPayload {
  application_id: number | null;
  collateral_type_id: number | null;
  asset_name: string;
  estimated_value?: number | null;
  ownership_document_no?: string;
  verification_status: string;
}