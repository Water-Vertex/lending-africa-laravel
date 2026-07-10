import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LoanProductsList } from './loan-products-list';

describe('LoanProductsList', () => {
  let component: LoanProductsList;
  let fixture: ComponentFixture<LoanProductsList>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [LoanProductsList],
    }).compileComponents();

    fixture = TestBed.createComponent(LoanProductsList);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
