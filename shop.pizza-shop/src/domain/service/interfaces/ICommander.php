<?php
namespace domain\service\interfaces;

use pizzashop\shop\domain\dto\commande\CommandeDTO;

interface ICommander
{

   public function readCommande(String $id): CommandeDTO;

   public function validateCommande(String $id): CommandeDTO;
   
   // public function creerCommande(CommandeDTO $commandeDTO): void;

   // public function validerCommande(String $id): void;

   // public function getCommande(String $id): CommandeDTO;

}