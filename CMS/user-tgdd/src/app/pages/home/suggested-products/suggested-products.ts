import { CommonModule, DecimalPipe } from '@angular/common';
import { Component, OnInit } from '@angular/core';
@Component({
  selector: 'app-suggested-products',
  imports: [DecimalPipe, CommonModule],
  templateUrl: './suggested-products.html',
  styleUrls: ['./suggested-products.scss'],
})
export class SuggestedProducts implements OnInit {
  visibleCount = 12;

  loadMore() {
    this.visibleCount += 12;
  }
  ngOnInit() {}

  suggestedProducts = [
    {
      name: 'Kidcare S88 43.4mm dây silicone',
      image: 'assets/img/home/hp.png',
      price: 2390000,
      originalPrice: 2640000,
      discount: 9,
      rating: 4.9,
      sold: '13,1k',
    },
    {
      name: 'Kidcare S88 43.4mm dây silicone',
      image: 'assets/img/home/hp.png',
      price: 2390000,
      originalPrice: 2640000,
      discount: 9,
      rating: 4.9,
      sold: '13,1k',
    },

    {
      name: 'Kidcare S88 43.4mm dây silicone',
      image: 'assets/img/home/hp.png',
      price: 2390000,
      originalPrice: 2640000,
      discount: 9,
      rating: 4.9,
      sold: '13,1k',
    },

    {
      name: 'Kidcare S88 43.4mm dây silicone',
      image: 'assets/img/home/hp.png',
      price: 2390000,
      originalPrice: 2640000,
      discount: 9,
      rating: 4.9,
      sold: '13,1k',
    },

    {
      name: 'Kidcare S88 43.4mm dây silicone',
      image: 'assets/img/home/hp.png',
      price: 2390000,
      originalPrice: 2640000,
      discount: 9,
      rating: 4.9,
      sold: '13,1k',
    },

    {
      name: 'Kidcare S88 43.4mm dây silicone',
      image: 'assets/img/home/hp.png',
      price: 2390000,
      originalPrice: 2640000,
      discount: 9,
      rating: 4.9,
      sold: '13,1k',
    },

    {
      name: 'Kidcare S88 43.4mm dây silicone',
      image: 'assets/img/home/hp.png',
      price: 2390000,
      originalPrice: 2640000,
      discount: 9,
      rating: 4.9,
      sold: '13,1k',
    },

    {
      name: 'Kidcare S88 43.4mm dây silicone',
      image: 'assets/img/home/hp.png',
      price: 2390000,
      originalPrice: 2640000,
      discount: 9,
      rating: 4.9,
      sold: '13,1k',
    },

    {
      name: 'Kidcare S88 43.4mm dây silicone',
      image: 'assets/img/home/hp.png',
      price: 2390000,
      originalPrice: 2640000,
      discount: 9,
      rating: 4.9,
      sold: '13,1k',
    },

    {
      name: 'Kidcare S88 43.4mm dây silicone',
      image: 'assets/img/home/hp.png',
      price: 2390000,
      originalPrice: 2640000,
      discount: 9,
      rating: 4.9,
      sold: '13,1k',
    },

    {
      name: 'Kidcare S88 43.4mm dây silicone',
      image: 'assets/img/home/hp.png',
      price: 2390000,
      originalPrice: 2640000,
      discount: 9,
      rating: 4.9,
      sold: '13,1k',
    },

    {
      name: 'Kidcare S88 43.4mm dây silicone',
      image: 'assets/img/home/hp.png',
      price: 2390000,
      originalPrice: 2640000,
      discount: 9,
      rating: 4.9,
      sold: '13,1k',
    },
    {
      name: 'Kidcare S88 43.4mm dây silicone',
      image: 'assets/img/home/hp.png',
      price: 2390000,
      originalPrice: 2640000,
      discount: 9,
      rating: 4.9,
      sold: '13,1k',
    },
  ];
}
