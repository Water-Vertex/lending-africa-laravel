import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CollateralsTypesAdd } from './collaterals-types-add';

describe('CollateralsTypesAdd', () => {
  let component: CollateralsTypesAdd;
  let fixture: ComponentFixture<CollateralsTypesAdd>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CollateralsTypesAdd],
    }).compileComponents();

    fixture = TestBed.createComponent(CollateralsTypesAdd);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
