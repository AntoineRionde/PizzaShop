# TP Pizza-shop


## Groupe
- Arnaud Elian
- Flageollet Quentin
- Rionde Antoine
- Serrier Matheo
- Elias Tamourgh

## Services

| Service               | Description                          |
|-----------------------|--------------------------------------|
| auth.pizza-shop       | Service d'authentification           |
| gateway.pizza-shop    | Gateway                              |
| pizza.shop.components | Docker-compose des services          |
| prod.pizza-shop       | API node.js                          |
| shop.pizza-shop       | Service de commandes et du catalogue |
| websocket.front-end   | Websocket côté client                |
| websocket.pizza-shop  | Websocket côté serveur               |


## Connexion BDD

| Service                 | Username   | Password   |
|-------------------------|------------|------------|
| pizza-shop.commande.db  | pizza_shop | pizza_shop |
| pizza-shop.catalogue.db | pizza_cat  | pizza_cat  |
| pizza-shop.auth.db      | pizza_shop | pizza_shop |
| pizza-shop.prod.db      | pizza_shop | pizza_shop |

## Contributions

| n° TD          | Contributeurs           |
|----------------|-------------------------|
| TD2            | Antoine, Elian, Quentin |
| TD3            | Elian, Quentin          |
| TD4            | Elian, Antoine, Quentin |
| TD5            | Elian, Antoine, Quentin |
| TD6            | Mathéo                  |
| TD7            | Quentin, Elian          |
| TD8            | Antoine, Mathéo, Elian  |
| TD9 (Partie 1) | Mathéo                  |
| TD9 (Partie 2) | Antoine                 |
| TD10           | Elian                   |

## Tableau des routes

| Méthode | Nom de l'API   | Endpoint                                 | Autorisation | Type de contenu | Paramètres                                                                                   |
|---------|----------------|------------------------------------------|--------------|-----------------|----------------------------------------------------------------------------------------------|
| GET     | Get Order      | http://localhost:2080/order/[id]         | Bearer Token | -               | -                                                                                            |
| PATCH   | Validate Order | http://localhost:2080/order/[id]         | Bearer Token | -               | -                                                                                            |
| GET     | Get Orders     | http://localhost:2080/order              | Bearer Token | -               | -                                                                                            |
| POST    | Order          | http://localhost:2080/order              | Bearer Token | form-data       | date, livraisonType, delay, clientMail, items[0][number], items[0][size], items[0][quantity] |
| POST    | RefreshToken   | http://localhost:2082/api/users/refresh  | Bearer Token | -               | -                                                                                            |
| POST    | SignInAction   | http://localhost:2082/api/users/signin   | Basic Auth   | -               | Username, Password                                                                           |
| GET     | ValidateToken  | http://localhost:2082/api/users/validate | Bearer Token | -               | -                                                                                            |
| GET     | Base           | http://localhost:2081                    | -            | -               | -                                                                                            |

## Lancer le projet

Pour lancer le projet, il suffit de faire dans le dossier `pizza.shop.components` :
```bash
docker-compose up
```

### Remarque

Il est possible qu'au premier lancement, tous les conteneurs ne soient pas fonctionnel
dû aux différentes installations de dépendances, il est alors nécessaire d'arrêter les conteneurs
et de les relancer.