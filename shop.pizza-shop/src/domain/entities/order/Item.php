<?php

namespace pizzashop\shop\domain\entities\order;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    protected $connection = 'item';
    protected $table = 'item';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['id', 'numero', 'libelle', 'taille', 'libelle_taille', 'tarif', 'quantite', 'commande_id'];

    public function commande()
    {
        return $this->belongsTo(Order::class, 'commande_id', 'id');
    }
}