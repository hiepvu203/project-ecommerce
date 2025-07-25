import { CommonModule, DecimalPipe } from '@angular/common';
import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-product-price-box',
  imports: [DecimalPipe, CommonModule],
  templateUrl: './product-price-box.html',
  styleUrl: './product-price-box.scss',
})
export class ProductPriceBox {
  @Input() product: any;
}
