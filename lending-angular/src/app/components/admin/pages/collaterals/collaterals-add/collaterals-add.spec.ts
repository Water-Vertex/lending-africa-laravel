import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CollateralsAdd } from './collaterals-add';

describe('CollateralsAdd', () => {
  let component: CollateralsAdd;
  let fixture: ComponentFixture<CollateralsAdd>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CollateralsAdd],
    }).compileComponents();

    fixture = TestBed.createComponent(CollateralsAdd);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
