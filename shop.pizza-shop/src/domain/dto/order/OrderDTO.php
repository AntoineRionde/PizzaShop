<?php

namespace pizzashop\shop\domain\dto\order;


class OrderDTO
{

    public string $id;
    public string $date_commande;
    public string $type_livraison;
    public string $etat;
    public $montant_total;
    public string $mail_client;
    public array $items;

    public function __construct(string $id, string $date_commande, string $type_livraison, string $etat, $montant_total, string $mail_client, array $items = [])
    {
        $this->id = $id;
        $this->date_commande = $date_commande;
        $this->type_livraison = $type_livraison;
        $this->etat = $etat;
        $this->montant_total = $montant_total;
        $this->mail_client = $mail_client;
        $this->items = $items;
    }


}