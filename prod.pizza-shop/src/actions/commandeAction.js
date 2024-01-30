import CommandeService from '../services/commandeService.js'

const commandeService = new CommandeService();

   const listerCommandes = async (req, res, next)=> {
        try {
            const commandes = await commandeService.getCommandes();
            console.log(commandes);
            res.json(commandes);
        } catch (error) {
            console.error(error);
            res.status(500).json({ error: 'Internal Server Error' });
        }
    }

export default listerCommandes;