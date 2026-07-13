import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CollateralsTypesList } from './collaterals-types-list';

describe('CollateralsTypesList', () => {
  let component: CollateralsTypesList;
  let fixture: ComponentFixture<CollateralsTypesList>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CollateralsTypesList],
    }).compileComponents();

    fixture = TestBed.createComponent(CollateralsTypesList);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
