const knex = require('knex');
const knexConfig = require('../configs/db.config.js');

const db = knex(knexConfig);

class CommandeService {
    async getCommandes() {
        return await db.select('*').from('commande');
    }
}

module.exports = CommandeService;
