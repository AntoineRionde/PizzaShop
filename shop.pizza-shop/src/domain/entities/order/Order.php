<?php

namespace pizzashop\shop\domain\entities\order;

use Illuminate\Database\Eloquent\Model;
use pizzashop\shop\domain\dto\order\OrderDTO;

class Order extends Model
{
    const CREATED = 1;
    const VALIDATED = 2;
    const PAYED = 3;
    const DELIVERED = 4;

    const ON_SITE = 1;
    const TAKE_AWAY = 2;
    const AT_HOME = 3;
    const LIVRAISON_TYPE = [
        self::ON_SITE,
        self::TAKE_AWAY,
        self::AT_HOME
    ];
    public $keyType = 'string';
    public $timestamps = false;
    public $fillable = ['id', 'date_commande', 'type_livraison', 'etat', 'montant_total', 'mail_client'];
    protected $connection = 'commande';
    protected $table = 'commande';
    protected $primaryKey = 'id';

    public function toDTO()
    {
        return new OrderDTO($this->id, $this->date_commande, $this->type_livraison, $this->montant_total, $this->mail_client, $this->delai, $this->items()->get()->toArray());
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'commande_id', 'id');
    }

}