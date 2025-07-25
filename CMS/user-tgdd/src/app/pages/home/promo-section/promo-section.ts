import { CommonModule, DecimalPipe } from '@angular/common';
import { Component } from '@angular/core';

interface Product {
  name: string;
  image: string;
  price: number;
  oldPrice: number;
  discount: number;
  category: string;
  label?: string; // icon đặc quyền, giảm sốc
  gift?: number; // giá trị quà tặng
  rating?: number;
  sold?: string;
  configs?: string[]; // RAM, pin
}

@Component({
  selector: 'app-promo-section',
  standalone: true,
  imports: [DecimalPipe, CommonModule],
  templateUrl: './promo-section.html',
  styleUrls: ['./promo-section.scss'],
})
export class PromoSection {
  categories = [
    { key: 'flash', label: 'Flash Sale' },
    { key: 'apple', label: 'Apple' },
    { key: 'laptop', label: 'Laptop' },
    { key: 'phone', label: 'Điện Thoại' },
    { key: 'watch', label: 'Đồng Hồ' },
    { key: 'accessory', label: 'Phụ Kiện' },
  ];

  selectedCategory = 'flash';

  products: Product[] = [
    {
      name: 'Xiaomi Redmi 13 8GB/128GB',
      price: 4110000,
      oldPrice: 4610000,
      discount: 10,
      image: 'assets/img/home/samsungdp.png',
      category: 'flash',
      label: 'assets/img/label-exclusive.png',
      gift: 200000,
      rating: 4.7,
      sold: '3,2k',
      configs: ['RAM 8GB', 'Pin 5000mAh'],
    },
    {
      name: 'Xiaomi Redmi 13 8GB/128GB',
      price: 4110000,
      oldPrice: 4610000,
      discount: 10,
      image: 'assets/img/home/samsungdp.png',
      category: 'flash',
      label: 'assets/img/label-exclusive.png',
      gift: 200000,
      rating: 4.7,
      sold: '3,2k',
      configs: ['RAM 8GB', 'Pin 5000mAh'],
    },
    {
      name: 'Xiaomi Redmi 13 8GB/128GB',
      price: 4110000,
      oldPrice: 4610000,
      discount: 10,
      image: 'assets/img/home/samsungdp.png',
      category: 'flash',
      label: 'assets/img/label-exclusive.png',
      gift: 200000,
      rating: 4.7,
      sold: '3,2k',
      configs: ['RAM 8GB', 'Pin 5000mAh'],
    },
    {
      name: 'Xiaomi Redmi 13 8GB/128GB',
      price: 4110000,
      oldPrice: 4610000,
      discount: 10,
      image: 'assets/img/home/samsungdp.png',
      category: 'flash',
      label: 'assets/img/label-exclusive.png',
      gift: 200000,
      rating: 4.7,
      sold: '3,2k',
      configs: ['RAM 8GB', 'Pin 5000mAh'],
    },
    {
      name: 'Xiaomi Redmi 13 8GB/128GB',
      price: 4110000,
      oldPrice: 4610000,
      discount: 10,
      image: 'assets/img/home/samsungdp.png',
      category: 'flash',
      label: 'assets/img/label-exclusive.png',
      gift: 200000,
      rating: 4.7,
      sold: '3,2k',
      configs: ['RAM 8GB', 'Pin 5000mAh'],
    },
    {
      name: 'Xiaomi Redmi 13 8GB/128GB',
      price: 4110000,
      oldPrice: 4610000,
      discount: 10,
      image: 'assets/img/home/samsungdp.png',
      category: 'flash',
      label: 'assets/img/label-exclusive.png',
      gift: 200000,
      rating: 4.7,
      sold: '3,2k',
      configs: ['RAM 8GB', 'Pin 5000mAh'],
    },
    {
      name: 'Xiaomi Redmi 13 8GB/128GB',
      price: 4110000,
      oldPrice: 4610000,
      discount: 10,
      image: 'assets/img/home/samsungdp.png',
      category: 'flash',
      label: 'assets/img/label-exclusive.png',
      gift: 200000,
      rating: 4.7,
      sold: '3,2k',
      configs: ['RAM 8GB', 'Pin 5000mAh'],
    },
    {
      name: 'Xiaomi Redmi 13 8GB/128GB',
      price: 4110000,
      oldPrice: 4610000,
      discount: 10,
      image: 'assets/img/home/samsungdp.png',
      category: 'flash',
      label: 'assets/img/label-exclusive.png',
      gift: 200000,
      rating: 4.7,
      sold: '3,2k',
      configs: ['RAM 8GB', 'Pin 5000mAh'],
    },
    {
      name: 'Xiaomi Redmi 13 8GB/128GB',
      price: 4110000,
      oldPrice: 4610000,
      discount: 10,
      image: 'assets/img/home/samsungdp.png',
      category: 'flash',
      label: 'assets/img/label-exclusive.png',
      gift: 200000,
      rating: 4.7,
      sold: '3,2k',
      configs: ['RAM 8GB', 'Pin 5000mAh'],
    },
    {
      name: 'Xiaomi Redmi 13 8GB/128GB',
      price: 4110000,
      oldPrice: 4610000,
      discount: 10,
      image: 'assets/img/home/samsungdp.png',
      category: 'flash',
      label: 'assets/img/label-exclusive.png',
      gift: 200000,
      rating: 4.7,
      sold: '3,2k',
      configs: ['RAM 8GB', 'Pin 5000mAh'],
    },
    {
      name: 'Xiaomi Redmi 13 8GB/128GB',
      price: 4110000,
      oldPrice: 4610000,
      discount: 10,
      image: 'assets/img/home/samsungdp.png',
      category: 'flash',
      label: 'assets/img/label-exclusive.png',
      gift: 200000,
      rating: 4.7,
      sold: '3,2k',
      configs: ['RAM 8GB', 'Pin 5000mAh'],
    },
    {
      name: 'Xiaomi Redmi 13 8GB/128GB',
      price: 4110000,
      oldPrice: 4610000,
      discount: 10,
      image: 'assets/img/home/samsungdp.png',
      category: 'flash',
      label: 'assets/img/label-exclusive.png',
      gift: 200000,
      rating: 4.7,
      sold: '3,2k',
      configs: ['RAM 8GB', 'Pin 5000mAh'],
    },

    {
      name: 'MacBook Air M2 13"',
      price: 22490000,
      oldPrice: 29390000,
      discount: 23,
      image: 'assets/img/macbook-air.jpg',
      category: 'apple',
      gift: 2190000,
      rating: 5.0,
      sold: '1,4k',
      configs: ['RAM 8GB', 'SSD 256GB'],
    },
    {
      name: 'Dell Inspiron 5440 Core i5',
      price: 23990000,
      oldPrice: 26000000,
      discount: 8,
      image: 'assets/img/dell-inspiron.jpg',
      category: 'laptop',
      rating: 4.6,
      sold: '2,9k',
      configs: ['Core i5', 'RAM 16GB'],
    },
    {
      name: 'Apple Watch S9',
      price: 8660000,
      oldPrice: 9990000,
      discount: 13,
      image: 'assets/img/watch-s9.jpg',
      category: 'watch',
      gift: 500000,
      rating: 4.9,
      sold: '5,5k',
      configs: ['GPS', 'Màn hình OLED'],
    },
    {
      name: 'Tai nghe Bluetooth TWS',
      price: 660000,
      oldPrice: 940000,
      discount: 30,
      image: 'assets/img/home/samsungdp.png',
      category: 'accessory',
      rating: 4.3,
      sold: '9,8k',
      configs: ['Chống ồn', 'Bluetooth 5.3'],
    },
  ];

  get filteredProducts() {
    return this.products.filter((p) => p.category === this.selectedCategory);
  }

  selectCategory(key: string) {
    this.selectedCategory = key;
  }
}
