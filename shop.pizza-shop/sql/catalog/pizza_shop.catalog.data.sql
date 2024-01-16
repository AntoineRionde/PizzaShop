-- Adminer 4.8.1 PostgreSQL 13.1 (Debian 13.1-1.pgdg100+1) dump

DROP TABLE IF EXISTS "categorie";
DROP SEQUENCE IF EXISTS categorie_id_seq;
CREATE SEQUENCE categorie_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1;

CREATE TABLE "public"."categorie" (
                                      "id" integer DEFAULT nextval('categorie_id_seq') NOT NULL,
                                      "libelle" character varying NOT NULL,
                                      CONSTRAINT "categorie_pkey" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "produit";
DROP SEQUENCE IF EXISTS produit_id_seq;
CREATE SEQUENCE produit_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1;

CREATE TABLE "public"."produit" (
                                    "id" integer DEFAULT nextval('produit_id_seq') NOT NULL,
                                    "numero" integer NOT NULL,
                                    "libelle" character varying NOT NULL,
                                    "description" text,
                                    "image" character varying,
                                    "categorie_id" integer,
                                    CONSTRAINT "produit_pkey" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "taille";
DROP SEQUENCE IF EXISTS taille_id_seq;
CREATE SEQUENCE taille_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1;

CREATE TABLE "public"."taille" (
                                   "id" integer DEFAULT nextval('taille_id_seq') NOT NULL,
                                   "libelle" character varying NOT NULL,
                                   CONSTRAINT "taille_pkey" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "tarif";
CREATE TABLE "public"."tarif" (
                                  "produit_id" integer NOT NULL,
                                  "taille_id" integer NOT NULL,
                                  "tarif" numeric NOT NULL
) WITH (oids = false);


-- 2023-09-06 14:50:11.540125+00


-- Adminer 4.8.1 PostgreSQL 13.1 (Debian 13.1-1.pgdg100+1) dump

INSERT INTO "categorie" ("id", "libelle") VALUES
(5,	'Pizzas'),
(6,	'Boissons'),
(7,	'Salades'),
(8,	'Desserts');

INSERT INTO "produit" ("id", "numero", "libelle", "description", "image", "categorie_id") VALUES
(2,	1,	'Margherita',	'Tomate, mozzarella, basilic',	'https://www.dominos.fr/ManagedAssets/FR/product/PZSO.png',	5),
(3,	2,	'Reine',	'Tomate, mozzarella, jambon, champignons',	'https://www.dominos.fr/ManagedAssets/FR/product/PZRE.png',	5),
(4,	3,	'Savoyarde',	'Tomate, mozzarella, jambon, reblochon, pommes de terre, oignons',	'https://www.dominos.fr/ManagedAssets/FR/product/PZSA.png',	5),
(5,	4,	'Pepperoni',	'Tomate, mozzarella, pepperoni',	'https://www.dominos.fr/ManagedAssets/FR/product/PZPE.png',	5),
(6,	5,	'cola',	'Cola-Calo',	'https://www.dominos.fr/ManagedAssets/FR/product/BOCO.png',	6),
(7,	6,	'eau',	'eau',	'https://www.dominos.fr/ManagedAssets/FR/product/BOEA.png',	6),
(8,	7,	'salade verte',	'salade verte',	'https://www.dominos.fr/ManagedAssets/FR/product/SASA.png',	7),
(9,	8,	'salade tomate',	'salade tomate',	'https://www.dominos.fr/ManagedAssets/FR/product/SATO.png',	7),
(10,	9,	'tiramisu',	'tiramisu',	'https://www.dominos.fr/ManagedAssets/FR/product/DETI.png',	8),
(11,	10,	'panna cotta',	'panna cotta',	'https://www.dominos.fr/ManagedAssets/FR/product/DEPA.png',	8);

INSERT INTO "taille" ("id", "libelle") VALUES
(1,	'normale'),
(2,	'grande');

INSERT INTO "tarif" ("produit_id", "taille_id", "tarif") VALUES
(2,	1,	8.99),
(2,	2,	11.99),
(3,	1,	9.99),
(3,	2,	12.99),
(4,	1,	10.99),
(4,	2,	13.99),
(5,	1,	9.99),
(5,	2,	12.99),
(6,	1,	2.99),
(6,	2,	3.99),
(7,	1,	1.99),
(7,	2,	2.99),
(8,	1,	3.99),
(8,	2,	4.99),
(9,	1,	4.99),
(9,	2,	5.99),
(10,	1,	3.99),
(10,	2,	4.99),
(11,	1,	4.99),
(11,	2,	5.99);

-- 2023-09-06 14:49:59.359268+00
