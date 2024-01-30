import CommandeService from '../services/commandeService.js'

class CommandeAction {
    constructor() {
        this.commandeService = new CommandeService();
    }

    async listerCommandes(req, res) {
        try {
            const commandes = await this.commandeService.getCommandes();
            console.log(commandes)
            res.json(commandes);
        } catch (error) {
            console.error(error);
            res.status(500).json({ error: 'Internal Server Error' });
        }
    }
}

export default CommandeAction;