import { Component } from '@angular/core';
import { Header } from '../../components/header/header';
import { Footer } from '../../components/footer/footer';
import { Banner } from './banner/banner';
import { SubBannerCarousel } from './sub-banner-carousel/sub-banner-carousel';
import { SuggestedProducts } from './suggested-products/suggested-products';
import { ExclusiveProducts } from './exclusive-products/exclusive-products';
import { BrandBanner } from './brand-banner/brand-banner';
import { SpecialStore } from './special-store/special-store';
import { SocialSection } from './social-section/social-section';
import { HotKeywords } from './hot-keywords/hot-keywords';
import { PromoSection } from './promo-section/promo-section';

@Component({
  selector: 'app-home',
  imports: [
    Header,
    Footer,
    Banner,
    SubBannerCarousel,
    SuggestedProducts,
    ExclusiveProducts,
    BrandBanner,
    SpecialStore,
    SocialSection,
    HotKeywords,
    PromoSection,
  ],
  templateUrl: './home.html',
  styleUrl: './home.scss',
})
export class Home {}
