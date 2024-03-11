import knex from "knex";
import knexConfig from '../configs/db.config.js'
import amqp from 'amqplib';
import CommandeService from "../services/commandeService.js";
const db = knex(knexConfig);

const amqp_url = 'amqp://admin:admin1@localhost:5672';
const exchange = 'pizzashop';
const queue = 'suivi_commandes';
const routingKey = 'suivi';

class CommandPublisher {
    constructor() {
        this.commandService = new CommandeService();
    }

    async publish(commandId, newStatus) {
        try {


        const connection = await amqp.connect(amqp_url);
        const channel = await connection.createChannel();
        await channel.assertExchange(exchange, 'direct', { durable: false });
        await channel.assertQueue(queue, { durable: false });
        await channel.bindQueue(queue, exchange, routingKey);

        const message = { commandId, newStatus };
        channel.publish(exchange, routingKey, Buffer.from(JSON.stringify(message)));

        await this.commandService.updateEtatCommande(commandId, newStatus);

        } catch (error) {
            console.log(error);
        }
    }
}

export default CommandPublisher;