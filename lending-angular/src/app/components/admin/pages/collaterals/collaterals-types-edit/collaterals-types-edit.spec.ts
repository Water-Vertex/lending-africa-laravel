import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CollateralsTypesEdit } from './collaterals-types-edit';

describe('CollateralsTypesEdit', () => {
  let component: CollateralsTypesEdit;
  let fixture: ComponentFixture<CollateralsTypesEdit>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CollateralsTypesEdit],
    }).compileComponents();

    fixture = TestBed.createComponent(CollateralsTypesEdit);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
