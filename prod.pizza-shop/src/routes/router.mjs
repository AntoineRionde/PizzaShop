import express from 'express';
import CommandeAction from "../actions/commandeAction.js";
import CommandeService from "../services/commandeService.js";

const router = express.Router();
const service = new CommandeService();

const action = new CommandeAction(service);

router
    .route("/commandes")
    .get(action.listerCommandes.bind(action))
    .all((req, res, next) => next(405));

router
    .route("/commande/:id/")
    .patch(action.changerEtatCommande.bind(action))
    .all((req, res, next) => next(405));

export default router;
