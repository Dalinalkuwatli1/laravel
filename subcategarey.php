<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subcategarey extends Model
{
    use HasFactory;

   protected $fillable = [
    'category_id',
    'name',
     'slug', 
     'status', '
     show_on_home'
    ];
public function category()

{
    return $this->belongsTo(categarey::class);
}
}
