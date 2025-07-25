import { Component, OnInit } from '@angular/core';
import { ProductCommitments } from './product-commitments/product-commitments';
import { ProductGallery } from './product-gallery/product-gallery';
import { ProductPriceBox } from './product-price-box/product-price-box';
import { ProductInfo } from './product-info/product-info';
import { ProductSpecs } from './product-specs/product-specs';
import { ProductReviews } from './product-reviews/product-reviews';
import { RelatedProducts } from './related-products/related-products';
import { ProductDescription } from './product-description/product-description';
import { Header } from '../../components/header/header';
import { Footer } from '../../components/footer/footer';
import { ProductTitleRating } from './product-title-rating/product-title-rating';
import { ProductUsedSuggestion } from './product-used-suggestion/product-used-suggestion';

@Component({
  selector: 'app-product-detail',
  imports: [
    Header,
    Footer,
    ProductGallery,
    ProductInfo,
    ProductTitleRating,
    ProductPriceBox,
    ProductPriceBox,
    ProductCommitments,
    ProductSpecs,
    ProductUsedSuggestion,
  ],
  templateUrl: './product-detail.html',
  styleUrl: './product-detail.scss',
})
export class ProductDetail implements OnInit {
  product: any;
  relatedProducts: any[] = [];

  ngOnInit() {
    this.product = {
      name: 'Điện thoại iPhone 16 Pro Max 256GB',
      sold: '222,2k',
      rating: 4.9,
      price: 22190000,
      oldPrice: 25490000,
      discount: '-12%',
      colors: ['Titan Sa Mạc', 'Titan trắng', 'Titan đen', 'Titan tự nhiên'],
      selectedColor: 'Xanh',
      storageOptions: ['128GB', '256GB', '512GB'],
      selectedStorage: '256GB',
      promotionEnd: '23:00 | 31/07',
      city: 'Hồ Chí Minh',
      promotions: [
        'Phiếu mua hàng AirPods, Apple Watch, Macbook trị giá 500.000đ',
        'Phiếu mua hàng máy lạnh trị giá 300.000đ',
        'Phiếu mua hàng áp dụng mua sạc dự phòng (trừ AVA+, Hydrus), đồng hồ thông minh (trừ Apple), Tai nghe bluetooth và Loa bluetooth (JBL, Marshall,H/M,Sony), Ốp lưng (trừ Jincase, AVA+, JM, Kingxbar, COSANO, MEEKER, OSMIA) trị giá 100.000đ',
        'Phiếu mua hàng máy lọc nước trị giá 300.000d',
        'Phiếu mua hàng áp dụng mua tất cả sim có gói Mobi, Itel, Local, Vina ,VNMB và 2 gói cước (Siêu việt, 5G180) của Viettel trị giá 50,000đ.',
        'Nhập mã VNPAYTGDD3 giảm từ 80,000đ đến 150,000đ (áp dụng tùy giá trị đơn hàng) khi thanh toán qua VNPAY-QR',
        'Trả chậm 0% lãi suất. Đặc biệt giảm đến 10% tối đa 5 triệu khi thanh toán qua Kredivo',
      ],
      images: [
        'assets/img/product-detail/vi-vn-iphone-16-pro-max-1.png',
        'assets/img/product-detail/vi-vn-iphone-16-pro-max-2.png',
        'assets/img/product-detail/vi-vn-iphone-16-pro-max-3.png',
        'assets/img/product-detail/vi-vn-iphone-16-pro-max-4.png',
        'assets/img/product-detail/vi-vn-iphone-16-pro-max-5.png',
        'assets/img/product-detail/vi-vn-iphone-16-pro-max-6.png',
        'assets/img/product-detail/vi-vn-iphone-16-pro-max-7.png',
      ],
      specs: {
        'Hệ điều hành': 'iOS 18',
        'Chip xử lý (CPU)': 'Apple A18 6 nhân',
        RAM: '8 GB',
        'Dung lượng lưu trữ': '256 GB',
        'Tốc độ CPU': 'Hãng không công bố',
        'Chip đồ họa (GPU)': 'Apple GPU 6 nhân',
        'Dung lượng còn lại (khả dụng)': '241 GB',
        'Danh bạ': 'Không giới hạn',
      },
      reviews: [
        {
          name: 'Trần Mỹ Cảnh',
          stars: 5,
          comment:
            'Đặt 9h15 10h05 đã giao hàng đủ rồi, rất hài lòng và hỗ trợ nhanh.',
          date: '1 ngày trước',
          liked: true,
        },
        {
          name: 'Sơn Đặng Huỳnh Như',
          stars: 5,
          comment: 'Tốt nha máy đẹp',
          date: '3 tuần trước',
          liked: false,
        },
      ],
      description: `
    <p><strong>iPhone 16 Plus</strong> là dòng flagship mới nhất của Apple trong năm 2025...</p>
    <p>Máy được trang bị chip A18, màn hình OLED 6.7 inch, hỗ trợ kết nối 5G và camera nâng cấp mạnh mẽ.</p>
  `,
    };

    this.relatedProducts = [
      {
        name: 'AirPods Max cổng USB C',
        price: 12290000,
        oldPrice: 12990000,
        rating: 5,
        sold: '638',
        discount: '-5%',
        image: '',
      },
      {
        name: 'Sạc Anker Nano Pro A2637',
        price: 240000,
        oldPrice: 340000,
        rating: 5,
        sold: '5,1k',
        discount: '-29%',
        image: '',
      },
      {
        name: 'Ốp lưng iPhone 16 AVA+',
        price: 210000,
        oldPrice: 350000,
        rating: 4.9,
        sold: '194',
        discount: '-40%',
        image: '',
      },
      {
        name: 'AirPods 4',
        price: 3190000,
        oldPrice: 3390000,
        rating: 4.9,
        sold: '324',
        discount: '-5%',
        image: '',
      },
    ];
  }
}
