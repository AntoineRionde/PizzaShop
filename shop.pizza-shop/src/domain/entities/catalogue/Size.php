<?php

namespace pizzashop\shop\domain\entities\catalogue;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{

    const NORMAL = 1;
    const TALL = 2;
	
    protected $connection = 'catalog';
    protected $table = 'taille';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [ 'libelle'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'tarif', 'taille_id', 'produit_id');
    }

}