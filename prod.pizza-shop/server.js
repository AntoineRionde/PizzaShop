import app from "./index.js";
import CommandePublisher from "./src/rmq/commandePublisher.js";


app.listen(process.env.PORT, () => {
    console.log(`🚀 Server ready at http://localhost:${process.env.PORT}`);
});

(async () => {
    const commandPublisher = new CommandePublisher();

    await commandPublisher.publish(1, 'EN PRÉPARATION');
})();

