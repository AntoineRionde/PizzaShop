import express from 'express';
import listerCommandes from "../actions/commandeAction.js";

import CommandeAction from "../actions/commandeAction.js";
import CommandeService from '../services/commandeService.js'

const router = express.Router();

// Utiliser une fonction asynchrone pour charger dynamiquement l'action
router.get('/commandes', listerCommandes);

export default router; // Utiliser 'export default' pour exporter le module
