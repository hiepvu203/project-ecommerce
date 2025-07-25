import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';

@Component({
  selector: 'app-sub-banner-carousel',
  imports: [CommonModule],
  templateUrl: './sub-banner-carousel.html',
  styleUrls: ['./sub-banner-carousel.scss'],
})
export class SubBannerCarousel {
  slides = [
    ['assets/img/home/banner1.png', 'assets/img/home/banner2.png'],
    ['assets/img/home/banner3.png', 'assets/img/home/banner4.png'],
    ['assets/img/home/banner5.png', 'assets/img/home/banner6.png'],
  ];

  currentIndex = 0;

  nextSlide() {
    this.currentIndex = (this.currentIndex + 1) % this.slides.length;
  }

  prevSlide() {
    this.currentIndex =
      (this.currentIndex - 1 + this.slides.length) % this.slides.length;
  }
}
