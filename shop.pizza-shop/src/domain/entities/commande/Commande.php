<?php

namespace pizzashop\shop\domain\entities\commande;
use pizzashop\shop\domain\dto\commande\CommandeDTO;

class Commande extends \Illuminate\Database\Eloquent\Model
{

    protected $connection = 'pizza_shop';
    protected $table = 'commande';
    protected $primaryKey = 'id';
    public $timestamps = false;  
    public $fillable = ['id', 'date_commande', 'type_livraison','etat', 'montant_total', 'mail_client'];

    public function toDTO(){
        $commandeDTO = new CommandeDTO();
        $commandeDTO->id = $this->id;
        $commandeDTO->date_commande = $this->date_commande;
        $commandeDTO->type_livraison = $this->type_livraison;
        $commandeDTO->etat = $this->etat;
        $commandeDTO->montant_total = $this->montant_total;
        $commandeDTO->mail_client = $this->mail_client;
        return $commandeDTO;
    }
}