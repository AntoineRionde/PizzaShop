<?php
namespace domain\service\interfaces;

use domain\dto\CommandeDTO;

interface ICommander
{

    public function creerCommande(CommandeDTO $commandeDTO): void;
    
    public function validerCommande(String $id): void;

    public function getCommande(String $id): CommandeDTO;

}