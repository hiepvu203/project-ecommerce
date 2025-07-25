import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';

@Component({
  selector: 'app-hot-keywords',
  imports: [CommonModule],
  templateUrl: './hot-keywords.html',
  styleUrls: ['./hot-keywords.scss'],
})
export class HotKeywords {
  keywords = [
    'iphone 16',
    'iphone 16 pro',
    'iphone 16 pro max',
    'Samsung Galaxy Tab S9',
    'Lenovo Tab M11',
    'iphone 15',
    'iphone 15 plus',
    'samsung z flip 7',
    'tai nghe airpods',
    'airpods 4',
    'airpods 4 anc',
    'apple watch series 10',
    'apple watch series 9',
    'apple watch ultra 2',
    'asus',
    'laptop gaming',
    'macbook air',
    'macbook pro',
    'Mac Studio M4',
    'MacBook Air M4',
    'airtag',
    'loa jbl',
    'tai nghe sony',
    'loa marshall',
    'bàn phím gaming',
    'chuột logitech',
    'loa harman kardon',
    'đồng hồ g shock',
    'galaxy watch 8',
    'samsung galaxy z series',
    'galaxy watch ultra 2025',
    'đồng hồ thụy sỹ',
    'galaxy watch 8 classic',
    'macbook',
    'macbook pro m4',
    'samsung s25',
    'samsung s25 plus',
    'samsung galaxy s25 ultra',
    'iphone 16e',
    'iphone 14',
    'samsung galaxy z fold 7',
  ];
  onClick(keyword: string, event: Event) {
    event.preventDefault();
    console.log('Tìm kiếm:', keyword);
    // Redirect: this.router.navigate(['/search'], { queryParams: { q: keyword } });
  }
}
