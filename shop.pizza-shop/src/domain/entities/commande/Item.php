<?php

namespace pizzashop\shop\domain\entities\commande;

class Item extends \Illuminate\Database\Eloquent\Model
{

    protected $connection = 'pizza_shop';
    protected $table = 'item';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['id', 'numero', 'libelle', 'taille', 'libelle_taille', 'tarif', 'quantite', 'commande_id'];

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'commande_id', 'id');
    }
}