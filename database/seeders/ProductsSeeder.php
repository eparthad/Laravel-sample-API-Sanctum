<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::factory()->times(10)->create();

        Product::factory()->times(20)->create();

        $tags = Tag::all();

        Product::all()->each(function ($product) use ($tags) { 
            $product->tags()->attach(
                $tags->random(rand(1, 3))->pluck('id')->toArray()
            ); 

            $product->product_images()->saveMany(
                ProductImage::factory()->times(3)->create(),
            );
        });
    }
}
