import { CommonModule } from '@angular/common';
import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-product-specs',
  imports: [CommonModule],
  templateUrl: './product-specs.html',
  styleUrl: './product-specs.scss',
})
export class ProductSpecs {
  @Input() specs: any;

  objectKeys(obj: any): string[] {
    return Object.keys(obj);
  }
  otherGroups = [
    { title: 'Camera & Màn hình', id: 'cameraScreen' },
    { title: 'Pin & Sạc', id: 'battery' },
    { title: 'Tiện ích', id: 'features' },
    { title: 'Kết nối', id: 'connection' },
    { title: 'Thiết kế & Chất liệu', id: 'design' },
  ];
}
