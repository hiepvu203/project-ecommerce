import { DecimalPipe } from '@angular/common';
import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-product-title-rating',
  imports: [DecimalPipe],
  templateUrl: './product-title-rating.html',
  styleUrl: './product-title-rating.scss',
})
export class ProductTitleRating {
  @Input() name!: string;
  @Input() sold!: string;
  @Input() rating!: number;
  @Input() likes: number = 7000;
}
