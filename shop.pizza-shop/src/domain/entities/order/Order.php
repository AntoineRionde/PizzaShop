<?php

namespace pizzashop\shop\domain\entities\order;
use Illuminate\Database\Eloquent\Model;
use pizzashop\shop\domain\dto\order\OrderDTO;

class Order extends Model
{
    const ETAT_CREE = 1;
    const ETAT_VALIDE = 2;
    const ETAT_PAYE = 3;
    const ETAT_LIVRE = 4;

    const LIVRAISON_SUR_PLACE = 1;
    const LIVRAISON_A_EMPORTER = 2;
    const LIVRAISON_A_DOMICILE = 3;
    const TYPE_LIVRAISON = [
        self::LIVRAISON_SUR_PLACE,
        self::LIVRAISON_A_EMPORTER,
        self::LIVRAISON_A_DOMICILE
    ];

    protected $connection = 'commande';
    protected $table = 'commande';
    protected $primaryKey = 'id';
    public $keyType = 'string';

    public $timestamps = false;  
    public $fillable = ['id', 'date_commande', 'type_livraison','etat', 'montant_total', 'mail_client'];

    public function toDTO(){
        return new OrderDTO($this->id, $this->date_commande, $this->type_livraison, $this->etat, $this->montant_total, $this->mail_client, $this->delai ,$this->items()->get()->toArray());
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'commande_id', 'id');
    }

}