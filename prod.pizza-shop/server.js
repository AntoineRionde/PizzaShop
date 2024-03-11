import app from "./index.js";
import CommandePublisher from "./src/rmq/commandePublisher.js";
import CommandeConsumer from "./src/rmq/commandeConsumer.js";

import amqp from "amqplib";

app.listen(process.env.PORT, () => {
    console.log(`🚀 Server ready at http://localhost:${process.env.PORT}`);
});

(async () => {


    const commandPublisher = new CommandePublisher();

    await commandPublisher.publish("cc1e6220-774a-37bd-b8cf-e7b9dc5c446a", 'EN PRÉPARATION');

    const commandeConsumer = new CommandeConsumer();

    await commandeConsumer.consume();

})();

