import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';

@Component({
  selector: 'app-social-section',
  imports: [CommonModule],
  templateUrl: './social-section.html',
  styleUrls: ['./social-section.scss'],
})
export class SocialSection {
  socialSections = [
    {
      image: 'assets/img/home/social1.png',
      title:
        'Hướng dẫn cách theo dõi đường đi của bão số 3 - Bão Wipha để bạn có phương án phòng bị kịp thời',
    },
    {
      image: 'assets/img/home/social2.png',
      title: 'Tablet là gì? TOP thương hiệu máy tính bảng đáng mua nhất',
    },
    {
      image: 'assets/img/home/social3.png',
      title:
        'Đặc quyền nâng cấp Laptop Windows bảo hành 1 đổi 1 trong 1 năm chỉ 299K',
    },
    {
      image: 'assets/img/home/social4.png',
      title:
        'Khả dụng trên iPhone là gì? Cách kiểm tra, tăng bộ nhớ khả dụng iPhone',
    },
  ];
}
