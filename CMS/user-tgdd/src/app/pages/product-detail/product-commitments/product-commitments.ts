import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';

@Component({
  selector: 'app-product-commitments',
  imports: [CommonModule],
  templateUrl: './product-commitments.html',
  styleUrl: './product-commitments.scss',
})
export class ProductCommitments {
  commitments = [
    {
      icon: 'bi-box',
      text: 'Sản phẩm mới (Cần thanh toán trước khi mở hộp).',
    },
    {
      icon: 'bi-box',
      text: 'Bộ sản phẩm gồm: Hộp, Sách hướng dẫn, Cáp, Cây lấy sim',
    },
    {
      icon: 'bi-arrow-repeat',
      text: `Hư gì đổi nấy <strong>12 tháng</strong> tại 2965 siêu thị toàn quốc (miễn phí tháng đầu) <a href="#">Xem chi tiết</a>`,
    },
    {
      icon: 'bi-shield-check',
      text: `Bảo hành <strong>chính hãng điện thoại 1 năm</strong> tại các trung tâm bảo hành hãng <a href="#">Xem địa chỉ bảo hành</a>`,
    },
  ];
}
