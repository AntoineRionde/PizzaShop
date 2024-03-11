import { WebSocketServer } from 'ws';
import consumeMQ from "./src/consumeMQ.js";

const AMQP_URL = 'amqp://admin:' + encodeURIComponent('admin1') + '@rabbitmq:5672';

const QUEUE = process.env.QUEUE || 'suivi_commandes';
const CONSUMER_TAG =  process.env.CONSUMER_TAG || 'ws';

const PORT = process.env.PORT || 3000;

const wss = new WebSocketServer({ port: PORT, clientTracking: true });

const clientOrders = new Map();

wss.on('connection', (ws) => {
    console.log('New websocket connexion established.');

    ws.on('error', console.error);

    ws.on('message', (message) => {
        try {
            let clientMsg = JSON.parse(message);
            console.log('Parsed message : ', clientMsg);

            if (clientMsg.orderId === undefined) {
                console.log('Message must contain orderId field');
                ws.send('Message must contain orderId field');
                return;
            }

            let orderId = clientMsg.orderId;

            if (!clientOrders.has(orderId)) {
                clientOrders.set(orderId, ws);
                console.log(`Client has subscribed to order tracking : ${orderId}`)
                ws.send(`You have subscribed to order tracking :  ${orderId}`);
            } else {
                ws.send(`The ${orderId} order is already being tracked.`);
            }
        } catch (error) {
            ws.send('Error while parsing message');
            console.log('Error while parsing message : ', error);
        }
    });
});

function notify(msg) {
    console.log('Notification received : ', msg)
    try {
        const orderId = JSON.parse(msg).id

        const client = clientOrders.get(orderId);

        if (!client) {
            console.log("User %s disconnected from Websocket", orderId);
        } else {
            client.send(msg);
            console.log("Notification sent to User : %s", orderId);
        }
    } catch (error) {
        console.log(error);
    }
}

await consumeMQ(AMQP_URL, QUEUE, CONSUMER_TAG, notify);
