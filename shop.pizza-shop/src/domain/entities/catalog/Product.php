<?php

namespace pizzashop\shop\domain\entities\catalog;

use Illuminate\database\eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use pizzashop\shop\domain\dto\catalog\ProductDTO;

class Product extends Model
{

    protected $connection = 'catalog';
    protected $table = 'produit';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['numero', 'libelle', 'description','image'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'tarif', 'produit_id', 'taille_id')
            ->withPivot('tarif');
    }

    public function toDTO(){
        return new ProductDTO($this->numero, $this->libelle, $this->description, $this->image, $this->prix);
    }

}