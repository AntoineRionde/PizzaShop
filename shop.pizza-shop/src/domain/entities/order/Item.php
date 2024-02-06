<?php

namespace pizzashop\shop\domain\entities\order;

use Illuminate\Database\Eloquent\Model;
use pizzashop\shop\domain\dto\item\ItemDTO;

class Item extends Model
{

    public $timestamps = false;
    protected $connection = 'commande';
    protected $table = 'item';
    protected $primaryKey = 'id';
    protected $fillable = ['numero', 'libelle', 'taille', 'libelle_taille', 'tarif', 'quantite', 'commande_id'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'commande_id', 'id');
    }

    public function toDTO()
    {
        return new ItemDTO($this->numero, $this->libelle, $this->taille, $this->libelle_taille, $this->tarif, $this->quantite, $this->commande_id);
    }
}