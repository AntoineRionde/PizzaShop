<?php

namespace pizzashop\shop\domain\entities\catalog;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{

    const NORMAL = 1;
    const TALL = 2;
    public $timestamps = false;
    protected $connection = 'catalog';
    protected $table = 'taille';
    protected $primaryKey = 'id';
    protected $fillable = ['libelle'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'tarif', 'taille_id', 'produit_id');
    }

}