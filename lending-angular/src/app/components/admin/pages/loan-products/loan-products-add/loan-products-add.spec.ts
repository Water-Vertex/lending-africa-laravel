import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LoanProductsAdd } from './loan-products-add';

describe('LoanProductsAdd', () => {
  let component: LoanProductsAdd;
  let fixture: ComponentFixture<LoanProductsAdd>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [LoanProductsAdd],
    }).compileComponents();

    fixture = TestBed.createComponent(LoanProductsAdd);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
