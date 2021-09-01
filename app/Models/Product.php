<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Tag;
use App\Models\Review;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'price',
    ];

    private $statusActive;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->statusActive = config( 'constants.status.status_active' );
    }

    public function product_images(){
        return $this->hasMany(ProductImage::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function tags(){
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function approvedReviews(){
        return $this->reviews()->where('status', '=', $this->statusActive);
    }
}
