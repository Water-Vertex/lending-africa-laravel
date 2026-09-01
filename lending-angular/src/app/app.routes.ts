import { Routes } from '@angular/router';

import { Login } from './components/auth/login/login';
import { Logout } from './components/auth/logout/logout';

import { AdminLayout } from './components/admin/layouts/admin-layout/admin-layout';
import { Dashboard } from './components/admin/pages/dashboard/dashboard';

import { LoanProductList } from './components/admin/pages/loan-products/loan-products-list/loan-products-list';
import { LoanProductForm } from './components/admin/pages/loan-products/loan-products-add/loan-products-add';

import { authGuard } from './guards/auth-guard';
import { RoleList } from './components/admin/pages/roles/role-list/role-list';
import { RoleAdd } from './components/admin/pages/roles/role-add/role-add';
import { RoleEdit } from './components/admin/pages/roles/role-edit/role-edit';
import { UserList } from './components/admin/pages/users/user-list/user-list';
import { UserAdd } from './components/admin/pages/users/user-add/user-add';
import { UserEdit } from './components/admin/pages/users/user-edit/user-edit';
import { CollateralTypesList } from './components/admin/pages/collaterals/collaterals-types-list/collaterals-types-list';
import { CollateralTypesAdd } from './components/admin/pages/collaterals/collaterals-types-add/collaterals-types-add';
import { CollateralTypesEdit } from './components/admin/pages/collaterals/collaterals-types-edit/collaterals-types-edit';
import { CollateralsList } from './components/admin/pages/collaterals/collaterals-list/collaterals-list';
import { CollateralsAdd } from './components/admin/pages/collaterals/collaterals-add/collaterals-add';
import { CollateralsEdit } from './components/admin/pages/collaterals/collaterals-edit/collaterals-edit';
import { CustomerList } from './components/admin/pages/customers/customer-list/customer-list';
import { CustomerAdd } from './components/admin/pages/customers/customer-add/customer-add';
import { CustomerEdit } from './components/admin/pages/customers/customer-edit/customer-edit';
import { CustomerDetail } from './components/admin/pages/customers/customer-detail/customer-detail';

export const routes: Routes = [

  {
    path: '',
    redirectTo: 'login',
    pathMatch: 'full'
  },

  {
    path: 'login',
    component: Login
  },

  {
    path: 'logout',
    component: Logout
  },

  {
    path: 'admin',
    component: AdminLayout,
    canActivate: [authGuard],
    children: [

      {
        path: '',
        redirectTo: 'dashboard',
        pathMatch: 'full'
      },

      {
        path: 'dashboard',
        component: Dashboard
      },

      // Loan Products routes
      { path: 'loan-products/list', component: LoanProductList },
      { path: 'loan-products/add', component: LoanProductForm },
      { path: 'loan-products/edit/:id', component: LoanProductForm },

      { path: 'roles', component: RoleList},
      { path: 'roles/add', component: RoleAdd},
      { path: 'roles/edit/:id', component: RoleEdit},

      { path: 'users', component: UserList},
      { path: 'users/add', component: UserAdd},
      { path: 'users/edit/:id', component: UserEdit},
       { path: 'collateral-types', component: CollateralTypesList },
      { path: 'collateral-types/add', component: CollateralTypesAdd },
      { path: 'collateral-types/edit/:id', component: CollateralTypesEdit },

      { path: 'collaterals', component: CollateralsList },
      { path: 'collaterals/add', component: CollateralsAdd },
      { path: 'collaterals/edit/:id', component: CollateralsEdit },

      { path: 'customers/list', component: CustomerList },
      { path: 'customers/add', component: CustomerAdd },
      { path: 'customers/:id', component: CustomerDetail },
      { path: 'customers/edit/:id', component: CustomerEdit },

    ]
  },

  {
    path: '**',
    redirectTo: 'login'
  }

];
