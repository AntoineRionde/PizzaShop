<?php

namespace pizzashop\shop\domain\dto\item;

class ItemDto
{

    public int $id;
    public int $numero;
    public string $libelle;
    public int $taille;
    public string $libelle_taille;
    public float $tarif;

    public int $quantite;
    public string $commande_id;

    public function __construct(int $id, int $numero, string $libelle, int $taille, string $libelle_taille, float $tarif, int $quantite, string $commande_id)
    {
        $this->id = $id;
        $this->numero = $numero;
        $this->libelle = $libelle;
        $this->taille = $taille;
        $this->libelle_taille = $libelle_taille;
        $this->tarif = $tarif;
        $this->quantite = $quantite;
        $this->commande_id = $commande_id;
    }


}