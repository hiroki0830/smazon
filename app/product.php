<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    public function category()
    {
        return $this->belongsTO('App\Category');
        // 複数ある商品を１つのカテゴリで参照する形
    }
    public function reviews()
    {
        return $this->hasMany('App\Review');
    }
}
