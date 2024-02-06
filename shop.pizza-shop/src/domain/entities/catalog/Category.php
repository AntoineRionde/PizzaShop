<?php

namespace pizzashop\shop\domain\entities\catalog;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    public $timestamps = false;
    protected $connection = 'catalog';
    protected $table = 'categorie';
    protected $primaryKey = 'id';
    protected $fillable = ['libelle'];

    public function products()
    {
        return $this->hasMany(Product::class, 'categorie_id');
    }

}