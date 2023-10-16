<?php

namespace pizzashop\shop\domain\dto\item;

class ItemDTO
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

    public function toArray()
    {
        return [
            'id' => $this->id,
            'numero' => $this->numero,
            'libelle' => $this->libelle,
            'taille' => $this->taille,
            'libelle_taille' => $this->libelle_taille,
            'tarif' => $this->tarif,
            'quantite' => $this->quantite,
            'commande_id' => $this->commande_id,
        ];
    }


}