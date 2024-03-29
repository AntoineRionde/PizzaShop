import knex from "knex";
import knexConfig from '../configs/db.config.js'

const db = knex(knexConfig);

class CommandeService {
    async getCommandes() {
        return await db.select('*').from('commande');
    }

    async updateEtatCommande(commandeId, nouvelEtat){
        await db('commande').where('id', '=', commandeId).update({etape: nouvelEtat});
    }

    async createCommand(commande){
        await db('commande').insert({client: commande.client, etape: 1});
    }
}

export default CommandeService;