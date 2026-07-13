import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CollateralsList } from './collaterals-list';

describe('CollateralsList', () => {
  let component: CollateralsList;
  let fixture: ComponentFixture<CollateralsList>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CollateralsList],
    }).compileComponents();

    fixture = TestBed.createComponent(CollateralsList);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
