import express from 'express';

// Utiliser l'import dynamique avec await pour charger le module ESM
const loadCommandeAction = async () => {
    const { default: CommandeAction } = await import('../actions/CommandeAction.js');
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

export default router; // Utiliser 'export default' pour exporter le module
