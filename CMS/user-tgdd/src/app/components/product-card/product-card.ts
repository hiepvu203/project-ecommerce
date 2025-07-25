import { CommonModule } from '@angular/common';
import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-product-card',
  imports: [CommonModule],
  templateUrl: './product-card.html',
  styleUrl: './product-card.scss',
})
export class ProductCard {
  @Input() product: any;

  getDiscount(price: number, originalPrice: number): number {
    const percent = 100 - (price / originalPrice) * 100;
    return Math.round(percent);
  }
}
