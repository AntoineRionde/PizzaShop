import app from "./index.js";
import CommandePublisher from "./src/rmq/commandePublisher.js";
import CommandeConsumer from "./src/rmq/commandeConsumer.js";


app.listen(process.env.PORT, () => {
    console.log(`🚀 Server ready at http://localhost:${process.env.PORT}`);
});

(async () => {
    const commandPublisher = new CommandePublisher();

    await commandPublisher.publish(1, 'EN PRÉPARATION');

    const commandeConsumer = new CommandeConsumer();

    await commandeConsumer.consume();

})();

