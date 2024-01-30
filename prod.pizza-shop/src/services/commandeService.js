import knex from "knex";
import knexConfig from '../configs/db.config.js'

const db = knex(knexConfig);

class CommandeService {
    async getCommandes() {
        return await db.select('*').from('commande');
    }
}

export default CommandeService;
