<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a>
<h1 align="center">Laravel Based API Project Using Sanctum</h1>
</p>


<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About This Project

This is a simple API based project on <b>Laravel</b>. For authentication here i used <b>"Sanctum"</b> package. Here user has one role only which is admin. An admin can add/edit/delete <b>"category","tag", "products"</b>. Without Authentication guest can get product list, product details and also can submit product's review. Later this review's can found with product after admin's approval.

## Project Goal

- Implementing Sanctum for Auth
- CRUD with upload multiple file
- Implementing relationship like One to Many(category-product, product-image, product-review), Many to Many(product-tag)
- Event & Listener
- Sending email
- Factory & seeding

Note: In future like to implement Task Scheduler, Job Queue etc

## Project Setup Instruction

- First clone the project
- Set your database configration in .env file
- Run "php artisan migrate:fresh --seed" for migrating database & populating data
- Default <b>Email: admin@admin.com & Password: 123456</b>
- For email notification after a <b>review</b> submit, set mail configration in .env file



