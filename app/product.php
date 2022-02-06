<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
 use Overtrue\LaravelFavorite\Traits\Favoriteable;
  use Kyslik\ColumnSortable\Sortable;

class Product extends Model
{
    use Favoriteable, Sortable;

    public $sortable = [
        'price',
        'updated_at'
    ];

    public function category()
    {
        return $this->belongsTO('App\Category');
        // 複数ある商品を１つのカテゴリで参照する形
    }
    public function reviews()
    {
        return $this->hasMany('App\Review','');
        // 
    }
}
