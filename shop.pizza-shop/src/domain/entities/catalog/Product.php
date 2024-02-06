<?php

namespace pizzashop\shop\domain\entities\catalog;

use Illuminate\database\eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use pizzashop\shop\domain\dto\catalog\ProductDTO;
use stdClass;

class Product extends Model
{

    public $timestamps = false;
    protected $connection = 'catalog';
    protected $table = 'produit';
    protected $primaryKey = 'id';
    protected $fillable = ['numero', 'libelle', 'description', 'image'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'tarif', 'produit_id', 'taille_id')
            ->withPivot('tarif');
    }

    public function toDTO()
    {
        $tarifs = new stdClass();
        foreach ($this->sizes as $size) {
            $tarifs->{$size->libelle} = $size->pivot->tarif;
        }
        return new ProductDTO($this->numero, $this->libelle, $this->description, $this->image, $tarifs);
    }
}