import { CommonModule } from '@angular/common';
import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-product-gallery',
  imports: [CommonModule],
  templateUrl: './product-gallery.html',
  styleUrl: './product-gallery.scss',
})
export class ProductGallery {
  @Input() images: string[] = [];
  selectedImageIndex = 0;

  selectImage(index: number) {
    this.selectedImageIndex = index;
  }

  nextImage() {
    if (this.selectedImageIndex < this.images.length - 1) {
      this.selectedImageIndex++;
    }
  }

  prevImage() {
    if (this.selectedImageIndex > 0) {
      this.selectedImageIndex--;
    }
  }
}
