import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CollateralsEdit } from './collaterals-edit';

describe('CollateralsEdit', () => {
  let component: CollateralsEdit;
  let fixture: ComponentFixture<CollateralsEdit>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CollateralsEdit],
    }).compileComponents();

    fixture = TestBed.createComponent(CollateralsEdit);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
