import amqplib from 'amqplib';

const consumeMQ = async (amqp_url, queue, consumerTag, notify) => {
    try {
        const conn = await amqplib.connect(amqp_url);
        const ch = await conn.createChannel();
        await ch.assertQueue(queue, { durable: true });
        await ch.consume(queue, async (msg) => {
            if (msg !== null) {
                notify(msg.content.toString());
                ch.ack(msg);
            } else {
                console.log('No message received');
            }
        }, { consumerTag });
    } catch (error) {
        console.error('Error while consuming message : ', error);
    }
}
export default consumeMQ;