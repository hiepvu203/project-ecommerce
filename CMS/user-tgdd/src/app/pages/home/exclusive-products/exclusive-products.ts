import { CommonModule, DecimalPipe } from '@angular/common';
import { Component, ViewChild, ElementRef } from '@angular/core';

@Component({
  selector: 'app-exclusive-products',
  imports: [DecimalPipe, CommonModule],
  templateUrl: './exclusive-products.html',
  styleUrls: ['./exclusive-products.scss'],
})
export class ExclusiveProducts {
  @ViewChild('slider', { static: false }) sliderRef!: ElementRef;

  scrollLeft() {
    this.sliderRef.nativeElement.scrollBy({ left: -300, behavior: 'smooth' });
  }

  scrollRight() {
    this.sliderRef.nativeElement.scrollBy({ left: 300, behavior: 'smooth' });
  }
  products = [
    {
      name: 'Samsung Galaxy Z Flip7 FE 5G 8GB/128GB',
      image: 'assets/img/home/samsungdp.png',
      price: 22990000,
      originalPrice: 25990000,
      discount: 12,
      rating: 4.8,
      sold: '1,2k',
    },
    {
      name: 'OPPO Reno14 5G 12GB/512GB',
      image: 'assets/img/home/samsungdp.png',
      price: 16690000,
      originalPrice: 18990000,
      discount: 10,
      rating: 4.9,
      sold: '2,8k',
    },
    {
      name: 'Asus Vivobook Go 15 R5 7520U',
      image: 'assets/img/home/samsungdp.png',
      price: 12590000,
      originalPrice: 14190000,
      discount: 11,
      rating: 4.9,
      sold: '21k',
    },
    {
      name: 'HP i5 1334U (QG969PA)',
      image: 'assets/img/home/samsungdp.png',
      price: 15490000,
      originalPrice: 19300000,
      discount: 20,
      rating: 4.9,
      sold: '9,5k',
    },
    {
      name: 'HP i5 1334U (QG969PA)',
      image: 'assets/img/home/samsungdp.png',
      price: 15490000,
      originalPrice: 19300000,
      discount: 20,
      rating: 4.9,
      sold: '9,5k',
    },
    {
      name: 'HP i5 1334U (QG969PA)',
      image: 'assets/img/home/samsungdp.png',
      price: 15490000,
      originalPrice: 19300000,
      discount: 20,
      rating: 4.9,
      sold: '9,5k',
    },
    {
      name: 'HP i5 1334U (QG969PA)',
      image: 'assets/img/home/samsungdp.png',
      price: 15490000,
      originalPrice: 19300000,
      discount: 20,
      rating: 4.9,
      sold: '9,5k',
    },
    {
      name: 'HP i5 1334U (QG969PA)',
      image: 'assets/img/home/samsungdp.png',
      price: 15490000,
      originalPrice: 19300000,
      discount: 20,
      rating: 4.9,
      sold: '9,5k',
    },
  ];
}
