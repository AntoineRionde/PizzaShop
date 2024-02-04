import knex from "knex";
import knexConfig from '../configs/db.config.js';
import amqp from "amqplib";
const CommandeService = require('../services/commandeService.js');

const db = knex(knexConfig);

const consumerTag = process.env.CONSUMER_TAG;
const amqp_url = process.env.AMQP_URL;

class CommandConsumer {
    constructor(commandService) {
        this.commandService = new CommandeService();
    }

    async consume() {
        const connection = await amqp.connect(amqp_url);
        const channel = await connection.createChannel();

        await channel.assertQueue(consumerTag, { durable: false });

        await channel.consume(consumerTag, async (message) => {
            if (message.content) {
                const command = JSON.parse(message.content.toString());
                await this.commandService.createCommand(command);
                console.log('Command created:', command);
            }
        }, {noAck: true});
    }
}