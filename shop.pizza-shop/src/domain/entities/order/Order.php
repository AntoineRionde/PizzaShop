<?php

namespace pizzashop\shop\domain\entities\order;
use Illuminate\Database\Eloquent\Model;
use pizzashop\shop\domain\dto\order\OrderDTO;

class Order extends Model
{

    protected $connection = 'commande';
    protected $table = 'commande';
    protected $primaryKey = 'id';
    public $keyType = 'string';

    public $timestamps = false;  
    public $fillable = ['id', 'date_commande', 'type_livraison','etat', 'montant_total', 'mail_client'];

    public function toDTO(){
        return new OrderDTO($this->id, $this->date_commande, $this->type_livraison, $this->etat, $this->montant_total, $this->mail_client);
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'commande_id', 'id');
    }

}