<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;
use Tasmidur\Coupon\Traits\CouponTrait;

class Course extends Model
{
    use HasFactory,CouponTrait,LaravelVueDatatableTrait;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class,'course_category_id','id');
    }
}
