const express = require('express');
const CommandeAction = require('./commandeAction');

const router = express.Router();
const commandeAction = new CommandeAction();


router.get('/commandes', commandeAction.listerCommandes);

module.exports = router;
