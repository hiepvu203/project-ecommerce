import { DecimalPipe } from '@angular/common';
import { Component } from '@angular/core';

@Component({
  selector: 'app-product-used-suggestion',
  imports: [DecimalPipe],
  templateUrl: './product-used-suggestion.html',
  styleUrl: './product-used-suggestion.scss',
})
export class ProductUsedSuggestion {
  usedProduct = {
    name: 'Điện thoại Iphone 16 Pro Max 256GB',
    price: 27100000,
    discount: '20%',
    warranty: 'Chính hãng đến 01/05/2026',
    image:
      'assets/img/product-detail/iphone-16-pro-max-sa-mac-thumb-1-200x200.png',
  };
}
