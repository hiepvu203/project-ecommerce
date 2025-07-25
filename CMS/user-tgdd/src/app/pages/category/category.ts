import { Component, OnInit } from '@angular/core';
import { Header } from '../../components/header/header';
import { Footer } from '../../components/footer/footer';
import { CommonModule } from '@angular/common';
import { ProductCard } from '../../components/product-card/product-card';

@Component({
  selector: 'app-category',
  imports: [CommonModule, Header, Footer, ProductCard],
  templateUrl: './category.html',
  styleUrl: './category.scss',
})
export class Category implements OnInit {
  categoryName: string = 'Điện thoại';
  currentIndex = 0;

  slides = [
    ['assets/img/category/bannerdt1.png', 'assets/img/category/bannerdt2.png'],
    ['assets/img/category/bannerdt3.png', 'assets/img/category/bannerdt4.png'],
    ['assets/img/category/bannerdt5.png', 'assets/img/category/bannerdt6.png'],
  ];

  nextSlide() {
    this.currentIndex = (this.currentIndex + 1) % this.slides.length;
  }

  prevSlide() {
    this.currentIndex =
      (this.currentIndex - 1 + this.slides.length) % this.slides.length;
  }

  brandList: string[] = [
    'SAMSUNG',
    'iPhone',
    'OPPO',
    'XIAOMI',
    'VIVO',
    'realme',
    'HONOR',
    'Điện thoại AI',
    'Pin trên 5000mAh',
    'NOKIA',
  ];

  selectedBrand: string = '';
  selectBrand(brand: string) {
    this.selectedBrand = brand;
  }

  sortOptions: string[] = ['Nổi bật', 'Bán chạy', 'Giảm giá', 'Mới', 'Giá'];
  selectedSort: string = 'Nổi bật';

  selectSort(option: string): void {
    this.selectedSort = option;
  }

  allProducts = [
    {
      name: 'OPPO Reno 14 F 5G 12GB/256GB',
      image: 'assets/img/category/oppo-reno14-f-5g-blue-thumb-600x600.png',
      price: 11290000,
      // originalPrice:,
      promo: 'Quà 300.000₫',
      rating: 5,
      sold: 17000,
      variants: ['Full HD+', '6.57"'],
    },
    {
      name: 'Samsung Galaxy Z Flip7 FE 5G 8GB/128GB',
      image:
        'assets/img/category/samsung-galaxy-z-flip7-fe-white-thumb-600x600.png',
      price: 23000000,
      // originalPrice: 28390000,
      promo: 'Nhận ưu dãi đến 6 triệu',
      rating: 4.9,
      sold: 24800,
      variants: ['128GB'],
    },
    {
      name: 'iPhone 16 Pro 256GB',
      image: 'assets/img/category/iphone-16-pro-max-sa-mac-thumb-1-600x600.png',
      price: 30090000,
      originalPrice: 34290000,
      promo: 'Quà 500.000₫',
      rating: 4.9,
      sold: 24800,
      variants: ['256GB'],
    },
    {
      name: 'iPhone 16 Pro 128GB',
      image: 'assets/img/category/iphone-16-pro-max-sa-mac-thumb-1-600x600.png',
      price: 25090000,
      originalPrice: 28390000,
      promo: 'Quà 500.000₫',
      rating: 4.9,
      sold: 24800,
      variants: ['128GB'],
    },
    {
      name: 'Samsung Galaxy Z Flip7 FE 5G 12GB/256GB',
      image:
        'assets/img/category/samsung-galaxy-z-flip7-fe-white-thumb-600x600.png',
      price: 46990000,
      // originalPrice: 28390000,
      promo: 'Nhận ưu đãi đến 13 triệu',
      rating: 4.9,
      sold: 24800,
      variants: ['128GB', '256GB'],
    },
    {
      name: 'OPPO Reno 14 F 5G 12GB/256GB',
      image: 'assets/img/category/oppo-reno14-f-5g-blue-thumb-600x600.png',
      price: 11290000,
      // originalPrice:,
      promo: 'Quà 300.000₫',
      rating: 5,
      sold: 17000,
      variants: ['Full HD+', '6.57"'],
    },
    {
      name: 'Samsung Galaxy Z Flip7 FE 5G 8GB/128GB',
      image:
        'assets/img/category/samsung-galaxy-z-flip7-fe-white-thumb-600x600.png',
      price: 23000000,
      // originalPrice: 28390000,
      promo: 'Nhận ưu dãi đến 6 triệu',
      rating: 4.9,
      sold: 24800,
      variants: ['128GB'],
    },
    {
      name: 'iPhone 16 Pro 256GB',
      image: 'assets/img/category/iphone-16-pro-max-sa-mac-thumb-1-600x600.png',
      price: 30090000,
      originalPrice: 34290000,
      promo: 'Quà 500.000₫',
      rating: 4.9,
      sold: 24800,
      variants: ['256GB'],
    },
    {
      name: 'iPhone 16 Pro 128GB',
      image: 'assets/img/category/iphone-16-pro-max-sa-mac-thumb-1-600x600.png',
      price: 25090000,
      originalPrice: 28390000,
      promo: 'Quà 500.000₫',
      rating: 4.9,
      sold: 24800,
      variants: ['128GB'],
    },
    {
      name: 'Samsung Galaxy Z Flip7 FE 5G 12GB/256GB',
      image:
        'assets/img/category/samsung-galaxy-z-flip7-fe-white-thumb-600x600.png',
      price: 46990000,
      // originalPrice: 28390000,
      promo: 'Nhận ưu đãi đến 13 triệu',
      rating: 4.9,
      sold: 24800,
      variants: ['128GB', '256GB'],
    },
    {
      name: 'OPPO Reno 14 F 5G 12GB/256GB',
      image: 'assets/img/category/oppo-reno14-f-5g-blue-thumb-600x600.png',
      price: 11290000,
      // originalPrice:,
      promo: 'Quà 300.000₫',
      rating: 5,
      sold: 17000,
      variants: ['Full HD+', '6.57"'],
    },
    {
      name: 'Samsung Galaxy Z Flip7 FE 5G 8GB/128GB',
      image:
        'assets/img/category/samsung-galaxy-z-flip7-fe-white-thumb-600x600.png',
      price: 23000000,
      // originalPrice: 28390000,
      promo: 'Nhận ưu dãi đến 6 triệu',
      rating: 4.9,
      sold: 24800,
      variants: ['128GB'],
    },
    {
      name: 'iPhone 16 Pro 256GB',
      image: 'assets/img/category/iphone-16-pro-max-sa-mac-thumb-1-600x600.png',
      price: 30090000,
      originalPrice: 34290000,
      promo: 'Quà 500.000₫',
      rating: 4.9,
      sold: 24800,
      variants: ['256GB'],
    },
    {
      name: 'iPhone 16 Pro 128GB',
      image: 'assets/img/category/iphone-16-pro-max-sa-mac-thumb-1-600x600.png',
      price: 25090000,
      originalPrice: 28390000,
      promo: 'Quà 500.000₫',
      rating: 4.9,
      sold: 24800,
      variants: ['128GB'],
    },
    {
      name: 'Samsung Galaxy Z Flip7 FE 5G 12GB/256GB',
      image:
        'assets/img/category/samsung-galaxy-z-flip7-fe-white-thumb-600x600.png',
      price: 46990000,
      // originalPrice: 28390000,
      promo: 'Nhận ưu đãi đến 13 triệu',
      rating: 4.9,
      sold: 24800,
      variants: ['128GB', '256GB'],
    },
  ];
  products: any[] = [];
  itemsPerPage: number = 10;
  currentPage: number = 1;

  ngOnInit(): void {
    this.loadMore();
  }

  loadMore(): void {
    const nextItems = this.allProducts.slice(
      0,
      this.itemsPerPage * this.currentPage
    );
    this.products = nextItems;
    this.currentPage++;
  }

  get totalProducts(): number {
    return this.allProducts.length;
  }

  get remaining(): number {
    return this.totalProducts - this.products.length;
  }

  feedbackSubmitted: boolean = false;
  submitFeedback(result: boolean): void {
    this.feedbackSubmitted = true;
    console.log('Feedback:', result ? 'Hài lòng' : 'Không hài lòng');
  }

  tocOpen: boolean = true;

  toggleToc() {
    this.tocOpen = !this.tocOpen;
  }
  isExpanded = false;

  toggleDescription() {
    this.isExpanded = !this.isExpanded;
  }
  scrollToSection(sectionId: string) {
    const element = document.getElementById(sectionId);
    if (element) {
      element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }
}
