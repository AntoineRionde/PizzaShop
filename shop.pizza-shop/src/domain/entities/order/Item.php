<?php

namespace pizzashop\shop\domain\entities\order;

use Illuminate\Database\Eloquent\Model;
use pizzashop\shop\domain\dto\item\ItemDto;

class Item extends Model
{

    protected $connection = 'commande';
    protected $table = 'item';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['id', 'numero', 'libelle', 'taille', 'libelle_taille', 'tarif', 'quantite', 'commande_id'];

    public function commande()
    {
        return $this->belongsTo(Order::class, 'commande_id', 'id');
    }

    public function itemToDTO(){
        return new ItemDTO($this->id, $this->numero, $this->libelle, $this->taille, $this->libelle_taille, $this->tarif, $this->quantite, $this->commande_id);
    }
}