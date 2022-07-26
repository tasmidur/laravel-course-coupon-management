#### Laravel Course Coupon management service where course coupon code is managed using a laravel-coupon (https://github.com/user/repo/blob/branch/other_file.md) package and frontend is developed by vue js
## Installation

Clone the repository

    https://github.com/tasmidur/laravel-course-coupon-management.git

Switch to the repo folder

    cd laravel-course-coupon-management

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

You can install the package via composer:

```bash
composer require tasmidur/coupon
```

The package will automatically register itself.

You can publish the migration with:

```bash
php artisan vendor:publish --provider="Tasmidur\Coupon\LaravelCouponServiceProvider" --tag="coupon-migrations"
```

After the migration has been published you can create the coupons table by running the migrations:

```bash
php artisan migrate
```

You can publish the config-file with:

```bash
php artisan vendor:publish --provider=Tasmidur\Coupon\LaravelCouponServiceProvider --tag="config"
```
npm package install for vue js

```bash
yarn install
```
This is the contents of the published config file:

```php
<?php

return [

    /*
     * Table that will be used for migration
     */
    'table' => 'coupons',

    /*
     * Model to use
     */
    'model' => \Tasmidur\Coupon\Models\Coupon::class,

    /*
     * Pivot table name for coupons and other table relation
     */
    'relation_table' => 'coupon_applied',

    /*
    * Pivot table model name for coupons and other table relation
    */

    'relation_model_class' => \App\Models\Course::class,
    /*
     * List of characters that will be used for Coupons code generation.
     */
    'coupon_mix_characters' => '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',

    /*
     * Coupons code prefix.
     *
     * Example: course2022
     * Generated Code: course2022-37JH-1PUY
     */
    'prefix' => null,

    /*
     * Coupons code suffix.
     *
     * Example: course2022
     * Generated Code: 37JH-1PUY-course2022
     */
    'suffix' => null,

    /*
     * Separator to be used between prefix, code and suffix.
     */
    'separator' => '-',

    'coupon_format'=>'*****-*****'


];
```
Seed the CourseSeeder for courses
```bash
php artisan db:seed --class=CourseSeeder
```

```bash
php artisan serve
```

The basic use and code base is here
```php
//Use for Create
$coupon = Coupons::createCoupon(string $couponType, float $price, Carbon|null $expiredAt = null, int $totalAmount = 1);
//Use for get Coupon List
$coupon = Coupons::getCouponList(string $sortBy = "id", string $orderBy = "ASC");
$coupon = Coupons::getCouponListWithPagination(int $length = 10, string $sortBy = "id", string $orderBy = "ASC");
$coupon = Coupons::deleteCoupon(int $id);
$coupon = Coupons::getCoupon(int $id);
//Use for update Coupon List
$coupon = Coupons::updateCoupon(array $payload, int $id);
//Use for validity check of Coupon
$coupon = Coupons::check(string $code);
//return list of applied coupon where it applied
$coupon = Coupons::whereApplyCoupon(string $code);

```
Add the `Tasmidur\Coupon\Traits\CouponTrait` trait is used for applying coupon codes and the package takes care of storing the coupon association in the database.
```php
 $course = Course::findOrFail($courseId);
 /** One Coupon Is for One Course */
 $course->applyUniqueCoupon($couponCode);
 /** all applied coupons that is associated with course */
 $coupons = Course::eloquentQuery($sortBy, $orderBy, $searchValue)->with(['category', 'coupons'])->get();
```
### UI
## Coupon-list:
![coupon-list](https://github.com/tasmidur/laravel-course-coupon-management/blob/master/static-files/coupon-list.png)

## Add Coupon:
![coupon-list](https://github.com/tasmidur/laravel-course-coupon-management/blob/master/static-files/add-coupon.png)

## Apply Coupon:
![coupon-list](https://github.com/tasmidur/laravel-course-coupon-management/blob/master/static-files/apply-coupon.png)

## Where Apply Coupon view:
![coupon-list](https://github.com/tasmidur/laravel-course-coupon-management/blob/master/static-files/where-applied.png)
