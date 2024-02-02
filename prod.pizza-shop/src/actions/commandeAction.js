export default class commandeAction {
    #_service;

    constructor(service) {
        this.#_service = service;
    }

    async listerCommandes(req, res, next){
        try {
            const commandes = await this.#_service.getCommandes();
            res.json(commandes);
            next();
        } catch (error) {
            console.error(error);
            res.status(500).json({ error: 'Internal Server Error' });
            next(500);
        }
    }

    async changerEtatCommande(req, res, next){
        const id = req.params.id;
        const nouvelEtape = req.body.etape;

        try {
            await this.#_service.updateEtatCommande(id, nouvelEtape);
            res.json({ message: 'État de la commande mis à jour avec succès.' });
            next();
        } catch (error) {
            console.error(error);
            res.status(500).json({ error: 'Internal Server Error' });
            next(500);
        }
    }
}