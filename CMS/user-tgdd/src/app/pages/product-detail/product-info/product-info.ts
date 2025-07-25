import { CommonModule } from '@angular/common';
import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-product-info',
  imports: [CommonModule],
  templateUrl: './product-info.html',
  styleUrl: './product-info.scss',
})
export class ProductInfo {
  @Input() product: any;

  selectedStorage: string = '';
  selectedColor: string = '';

  ngOnInit() {
    this.selectedStorage =
      this.product.selectedStorage || this.product.storageOptions[0];
    this.selectedColor = this.product.selectedColor || this.product.colors[0];
  }

  selectStorage(option: string) {
    this.selectedStorage = option;
  }

  selectColor(option: string) {
    this.selectedColor = option;
  }

  getColorCode(colorName: string): string {
    const colorMap: any = {
      'Titan đen': '#000',
      'Titan trắng': '#f1f1f1',
      'Titan tự nhiên': '#c5bdb3',
      'Titan Sa Mạc': '#a78b6d',
      Đen: '#000',
      Trắng: '#fff',
      Hồng: '#fcd3e1',
      Xanh: '#007bff',
    };
    return colorMap[colorName] || '#ccc';
  }
}
