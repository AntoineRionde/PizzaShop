<?php
namespace domain\service\interfaces;

use domain\dto\CommandeDTO;

interface ICommander
{

   public function readCommande(String $id): CommandeDTO;

   public function validateCommande(String $id): CommandeDTO;
   
   // public function creerCommande(CommandeDTO $commandeDTO): void;

   // public function validerCommande(String $id): void;

   // public function getCommande(String $id): CommandeDTO;

}