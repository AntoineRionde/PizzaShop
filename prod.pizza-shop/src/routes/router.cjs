const express = require('express');

// Utiliser import() pour importer un module ESM de manière dynamique
const loadCommandeAction = async () => {
    const { CommandeAction } = await import('../actions/commandeAction.js');
    return new CommandeAction();
};

const router = express.Router();

// Utiliser une fonction asynchrone pour charger dynamiquement l'action
router.get('/commandes', async (req, res) => {
    try {
        const commandeAction = await loadCommandeAction();
        const commandes = await commandeAction.listerCommandes();
        res.json(commandes);
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Internal Server Error' });
    }
});

module.exports = router;
