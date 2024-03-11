import knex from "knex";
import knexConfig from '../configs/db.config.js';
import amqp from 'amqplib';
import CommandeService from '../services/commandeService.js';
const db = knex(knexConfig);

const consumerTag = 'nouvelles_commandes';
// const amqp_url = process.env.AMQP_URL ;
const amqp_url = 'amqp://admin:admin1@localhost:5672';
const queue = 'suivi_commandes';

class CommandeConsumer {
    constructor() {
        this.commandService = new CommandeService();
    }

    async consume() {
        try {
        const connection = await amqp.connect(amqp_url);
        const channel = await connection.createChannel();

        await channel.assertQueue(queue, { durable: false });

        await channel.consume(queue, async (message) => {
            if (message.content) {
                const command = JSON.parse(Buffer.from(message.content).toString());
                await this.commandService.createCommand(command);
                console.log('Command created:', command);
            }
        }, {noAck: false,
            consumerTag: consumerTag});

        process.once("SIGINT", async () => {
            console.log("got sigint, closing connection");
            await channel.close();
            await connection.close();
            process.exit(0);
        });
        }
        catch (error) {
            console.log(error);
        }
    }
}

export default CommandeConsumer;