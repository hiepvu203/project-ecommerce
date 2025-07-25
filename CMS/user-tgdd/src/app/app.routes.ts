import { Routes, RouterModule } from '@angular/router';
import { NgModel } from '@angular/forms';
import { Home } from './pages/home/home';
import { Category } from './pages/category/category';
import { NgModule } from '@angular/core';
import { ProductDetail } from './pages/product-detail/product-detail';

export const routes: Routes = [
  { path: '', component: Home },
  { path: 'category', component: Category },
  { path: 'product-detail', component: ProductDetail },
];
