-- MySQL dump 10.13  Distrib 9.3.0, for Win64 (x86_64)
--
-- Host: localhost    Database: shop_lab_db
-- ------------------------------------------------------
-- Server version	9.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `abonnement_utilisateur`
--

DROP TABLE IF EXISTS `abonnement_utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `abonnement_utilisateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `statut` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `utilisateur_id` int NOT NULL,
  `plan_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_EB378F92FB88E14F` (`utilisateur_id`),
  KEY `IDX_EB378F92E899029B` (`plan_id`),
  CONSTRAINT `FK_EB378F92E899029B` FOREIGN KEY (`plan_id`) REFERENCES `plan_abonnement` (`id`),
  CONSTRAINT `FK_EB378F92FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `abonnement_utilisateur`
--

LOCK TABLES `abonnement_utilisateur` WRITE;
/*!40000 ALTER TABLE `abonnement_utilisateur` DISABLE KEYS */;
/*!40000 ALTER TABLE `abonnement_utilisateur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `administrateur`
--

DROP TABLE IF EXISTS `administrateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `administrateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `last_login` datetime DEFAULT NULL,
  `utilisateur_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_32EB52E8FB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_32EB52E8FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrateur`
--

LOCK TABLES `administrateur` WRITE;
/*!40000 ALTER TABLE `administrateur` DISABLE KEYS */;
/*!40000 ALTER TABLE `administrateur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adresse`
--

DROP TABLE IF EXISTS `adresse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `adresse` (
  `id` int NOT NULL AUTO_INCREMENT,
  `rue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_postal` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pay` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `utilisateur_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C35F0816FB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_C35F0816FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adresse`
--

LOCK TABLES `adresse` WRITE;
/*!40000 ALTER TABLE `adresse` DISABLE KEYS */;
/*!40000 ALTER TABLE `adresse` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `avis_produit`
--

DROP TABLE IF EXISTS `avis_produit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `avis_produit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `commentaire` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` int NOT NULL,
  `client_id` int NOT NULL,
  `produit_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2A67C2119EB6921` (`client_id`),
  KEY `IDX_2A67C21F347EFB` (`produit_id`),
  CONSTRAINT `FK_2A67C2119EB6921` FOREIGN KEY (`client_id`) REFERENCES `client_final` (`id`),
  CONSTRAINT `FK_2A67C21F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avis_produit`
--

LOCK TABLES `avis_produit` WRITE;
/*!40000 ALTER TABLE `avis_produit` DISABLE KEYS */;
/*!40000 ALTER TABLE `avis_produit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boutique`
--

DROP TABLE IF EXISTS `boutique`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `boutique` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `couleur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slogan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commercant_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A1223C5483FA6DD0` (`commercant_id`),
  CONSTRAINT `FK_A1223C5483FA6DD0` FOREIGN KEY (`commercant_id`) REFERENCES `commercant` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boutique`
--

LOCK TABLES `boutique` WRITE;
/*!40000 ALTER TABLE `boutique` DISABLE KEYS */;
/*!40000 ALTER TABLE `boutique` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_final`
--

DROP TABLE IF EXISTS `client_final`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_final` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `adresse_principale_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4FB8C071FB88E14F` (`utilisateur_id`),
  KEY `IDX_4FB8C071C87159FC` (`adresse_principale_id`),
  CONSTRAINT `FK_4FB8C071C87159FC` FOREIGN KEY (`adresse_principale_id`) REFERENCES `adresse` (`id`),
  CONSTRAINT `FK_4FB8C071FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_final`
--

LOCK TABLES `client_final` WRITE;
/*!40000 ALTER TABLE `client_final` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_final` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commande`
--

DROP TABLE IF EXISTS `commande`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commande` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `statut` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `montant` double NOT NULL,
  `methode_paiment` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6EEAA67D19EB6921` (`client_id`),
  CONSTRAINT `FK_6EEAA67D19EB6921` FOREIGN KEY (`client_id`) REFERENCES `client_final` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commande`
--

LOCK TABLES `commande` WRITE;
/*!40000 ALTER TABLE `commande` DISABLE KEYS */;
/*!40000 ALTER TABLE `commande` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commercant`
--

DROP TABLE IF EXISTS `commercant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commercant` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int DEFAULT NULL,
  `boutique_principale_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_ECB4268FFB88E14F` (`utilisateur_id`),
  KEY `IDX_ECB4268F1D3B42FA` (`boutique_principale_id`),
  CONSTRAINT `FK_ECB4268F1D3B42FA` FOREIGN KEY (`boutique_principale_id`) REFERENCES `boutique` (`id`),
  CONSTRAINT `FK_ECB4268FFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commercant`
--

LOCK TABLES `commercant` WRITE;
/*!40000 ALTER TABLE `commercant` DISABLE KEYS */;
/*!40000 ALTER TABLE `commercant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversion`
--

DROP TABLE IF EXISTS `conversion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conversion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `taux` double NOT NULL,
  `date` date NOT NULL,
  `boutique_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BD912744AB677BE6` (`boutique_id`),
  CONSTRAINT `FK_BD912744AB677BE6` FOREIGN KEY (`boutique_id`) REFERENCES `boutique` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversion`
--

LOCK TABLES `conversion` WRITE;
/*!40000 ALTER TABLE `conversion` DISABLE KEYS */;
/*!40000 ALTER TABLE `conversion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favoris`
--

DROP TABLE IF EXISTS `favoris`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favoris` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `produit_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8933C43219EB6921` (`client_id`),
  KEY `IDX_8933C432F347EFB` (`produit_id`),
  CONSTRAINT `FK_8933C43219EB6921` FOREIGN KEY (`client_id`) REFERENCES `client_final` (`id`),
  CONSTRAINT `FK_8933C432F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favoris`
--

LOCK TABLES `favoris` WRITE;
/*!40000 ALTER TABLE `favoris` DISABLE KEYS */;
/*!40000 ALTER TABLE `favoris` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historique_connexion`
--

DROP TABLE IF EXISTS `historique_connexion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historique_connexion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `utilisateur_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C018B2D4FB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_C018B2D4FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historique_connexion`
--

LOCK TABLES `historique_connexion` WRITE;
/*!40000 ALTER TABLE `historique_connexion` DISABLE KEYS */;
/*!40000 ALTER TABLE `historique_connexion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historique_virement`
--

DROP TABLE IF EXISTS `historique_virement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historique_virement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `statut` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paiment_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_ED95B75478789290` (`paiment_id`),
  CONSTRAINT `FK_ED95B75478789290` FOREIGN KEY (`paiment_id`) REFERENCES `paiement_boutique` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historique_virement`
--

LOCK TABLES `historique_virement` WRITE;
/*!40000 ALTER TABLE `historique_virement` DISABLE KEYS */;
/*!40000 ALTER TABLE `historique_virement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ligne_commande`
--

DROP TABLE IF EXISTS `ligne_commande`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ligne_commande` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quantite` int NOT NULL,
  `prix_unitaire` double NOT NULL,
  `commande_id` int NOT NULL,
  `produit_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3170B74B82EA2E54` (`commande_id`),
  KEY `IDX_3170B74BF347EFB` (`produit_id`),
  CONSTRAINT `FK_3170B74B82EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`),
  CONSTRAINT `FK_3170B74BF347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ligne_commande`
--

LOCK TABLES `ligne_commande` WRITE;
/*!40000 ALTER TABLE `ligne_commande` DISABLE KEYS */;
/*!40000 ALTER TABLE `ligne_commande` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `livraison`
--

DROP TABLE IF EXISTS `livraison`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `livraison` (
  `id` int NOT NULL AUTO_INCREMENT,
  `statut` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `commande_id` int NOT NULL,
  `adresse_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A60C9F1F82EA2E54` (`commande_id`),
  KEY `IDX_A60C9F1F4DE7DC5C` (`adresse_id`),
  CONSTRAINT `FK_A60C9F1F4DE7DC5C` FOREIGN KEY (`adresse_id`) REFERENCES `adresse` (`id`),
  CONSTRAINT `FK_A60C9F1F82EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `livraison`
--

LOCK TABLES `livraison` WRITE;
/*!40000 ALTER TABLE `livraison` DISABLE KEYS */;
/*!40000 ALTER TABLE `livraison` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification`
--

DROP TABLE IF EXISTS `notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `lue` tinyint(1) NOT NULL,
  `utilisateur_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BF5476CAFB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_BF5476CAFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification`
--

LOCK TABLES `notification` WRITE;
/*!40000 ALTER TABLE `notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paiement_boutique`
--

DROP TABLE IF EXISTS `paiement_boutique`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paiement_boutique` (
  `id` int NOT NULL AUTO_INCREMENT,
  `montant` double NOT NULL,
  `date` date NOT NULL,
  `methode` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `boutique_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6B85C46EAB677BE6` (`boutique_id`),
  CONSTRAINT `FK_6B85C46EAB677BE6` FOREIGN KEY (`boutique_id`) REFERENCES `boutique` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paiement_boutique`
--

LOCK TABLES `paiement_boutique` WRITE;
/*!40000 ALTER TABLE `paiement_boutique` DISABLE KEYS */;
/*!40000 ALTER TABLE `paiement_boutique` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `panier`
--

DROP TABLE IF EXISTS `panier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `panier` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `session_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_24CC0DF219EB6921` (`client_id`),
  KEY `IDX_24CC0DF2613FECDF` (`session_id`),
  CONSTRAINT `FK_24CC0DF219EB6921` FOREIGN KEY (`client_id`) REFERENCES `client_final` (`id`),
  CONSTRAINT `FK_24CC0DF2613FECDF` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `panier`
--

LOCK TABLES `panier` WRITE;
/*!40000 ALTER TABLE `panier` DISABLE KEYS */;
/*!40000 ALTER TABLE `panier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `panier_produit`
--

DROP TABLE IF EXISTS `panier_produit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `panier_produit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quantite` int NOT NULL,
  `panier_id` int NOT NULL,
  `produit_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D31F28A6F77D927C` (`panier_id`),
  KEY `IDX_D31F28A6F347EFB` (`produit_id`),
  CONSTRAINT `FK_D31F28A6F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`),
  CONSTRAINT `FK_D31F28A6F77D927C` FOREIGN KEY (`panier_id`) REFERENCES `panier` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `panier_produit`
--

LOCK TABLES `panier_produit` WRITE;
/*!40000 ALTER TABLE `panier_produit` DISABLE KEYS */;
/*!40000 ALTER TABLE `panier_produit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parametre_bancaire`
--

DROP TABLE IF EXISTS `parametre_bancaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parametre_bancaire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `iban` varchar(34) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bic` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `commercant_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7DC1246683FA6DD0` (`commercant_id`),
  CONSTRAINT `FK_7DC1246683FA6DD0` FOREIGN KEY (`commercant_id`) REFERENCES `commercant` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parametre_bancaire`
--

LOCK TABLES `parametre_bancaire` WRITE;
/*!40000 ALTER TABLE `parametre_bancaire` DISABLE KEYS */;
/*!40000 ALTER TABLE `parametre_bancaire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plan_abonnement`
--

DROP TABLE IF EXISTS `plan_abonnement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plan_abonnement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` double NOT NULL,
  `limites` json NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plan_abonnement`
--

LOCK TABLES `plan_abonnement` WRITE;
/*!40000 ALTER TABLE `plan_abonnement` DISABLE KEYS */;
/*!40000 ALTER TABLE `plan_abonnement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produit`
--

DROP TABLE IF EXISTS `produit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` double NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categorie` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `boutique_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_29A5EC27AB677BE6` (`boutique_id`),
  CONSTRAINT `FK_29A5EC27AB677BE6` FOREIGN KEY (`boutique_id`) REFERENCES `boutique` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produit`
--

LOCK TABLES `produit` WRITE;
/*!40000 ALTER TABLE `produit` DISABLE KEYS */;
/*!40000 ALTER TABLE `produit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reset_mot_de_passe`
--

DROP TABLE IF EXISTS `reset_mot_de_passe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reset_mot_de_passe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_expiration` datetime NOT NULL,
  `utilisateur_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_349863A3FB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_349863A3FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reset_mot_de_passe`
--

LOCK TABLES `reset_mot_de_passe` WRITE;
/*!40000 ALTER TABLE `reset_mot_de_passe` DISABLE KEYS */;
/*!40000 ALTER TABLE `reset_mot_de_passe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revenus_commercant`
--

DROP TABLE IF EXISTS `revenus_commercant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `revenus_commercant` (
  `id` int NOT NULL AUTO_INCREMENT,
  `montant` double NOT NULL,
  `date` date NOT NULL,
  `commercant_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_86938BB083FA6DD0` (`commercant_id`),
  CONSTRAINT `FK_86938BB083FA6DD0` FOREIGN KEY (`commercant_id`) REFERENCES `commercant` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `revenus_commercant`
--

LOCK TABLES `revenus_commercant` WRITE;
/*!40000 ALTER TABLE `revenus_commercant` DISABLE KEYS */;
/*!40000 ALTER TABLE `revenus_commercant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` json NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `session` (
  `id` int NOT NULL AUTO_INCREMENT,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_expiration` datetime NOT NULL,
  `utilisateur_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D044D5D4FB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_D044D5D4FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `session`
--

LOCK TABLES `session` WRITE;
/*!40000 ALTER TABLE `session` DISABLE KEYS */;
/*!40000 ALTER TABLE `session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statisue_boutique`
--

DROP TABLE IF EXISTS `statisue_boutique`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `statisue_boutique` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nb_visite` int NOT NULL,
  `revenus` double NOT NULL,
  `produit_populaire` json NOT NULL,
  `boutique_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F672E62CAB677BE6` (`boutique_id`),
  CONSTRAINT `FK_F672E62CAB677BE6` FOREIGN KEY (`boutique_id`) REFERENCES `boutique` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statisue_boutique`
--

LOCK TABLES `statisue_boutique` WRITE;
/*!40000 ALTER TABLE `statisue_boutique` DISABLE KEYS */;
/*!40000 ALTER TABLE `statisue_boutique` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `template_email`
--

DROP TABLE IF EXISTS `template_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `template_email` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contenu` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `template_email`
--

LOCK TABLES `template_email` WRITE;
/*!40000 ALTER TABLE `template_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `template_email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tentative_connexion`
--

DROP TABLE IF EXISTS `tentative_connexion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tentative_connexion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `succes` tinyint(1) NOT NULL,
  `utilisateur_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_27E0C070FB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_27E0C070FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tentative_connexion`
--

LOCK TABLES `tentative_connexion` WRITE;
/*!40000 ALTER TABLE `tentative_connexion` DISABLE KEYS */;
/*!40000 ALTER TABLE `tentative_connexion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `token_verification`
--

DROP TABLE IF EXISTS `token_verification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `token_verification` (
  `id` int NOT NULL AUTO_INCREMENT,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_expiration` datetime NOT NULL,
  `utilisateur_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4A078E03FB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_4A078E03FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `token_verification`
--

LOCK TABLES `token_verification` WRITE;
/*!40000 ALTER TABLE `token_verification` DISABLE KEYS */;
/*!40000 ALTER TABLE `token_verification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_plateforme`
--

DROP TABLE IF EXISTS `transaction_plateforme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaction_plateforme` (
  `id` int NOT NULL AUTO_INCREMENT,
  `montant` double NOT NULL,
  `date` date NOT NULL,
  `methode` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abonnement_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D3943BA0F1D74413` (`abonnement_id`),
  CONSTRAINT `FK_D3943BA0F1D74413` FOREIGN KEY (`abonnement_id`) REFERENCES `abonnement_utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_plateforme`
--

LOCK TABLES `transaction_plateforme` WRITE;
/*!40000 ALTER TABLE `transaction_plateforme` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_plateforme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `two_fa`
--

DROP TABLE IF EXISTS `two_fa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `two_fa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `secret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `utilisateur_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6734C19CFB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_6734C19CFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `two_fa`
--

LOCK TABLES `two_fa` WRITE;
/*!40000 ALTER TABLE `two_fa` DISABLE KEYS */;
/*!40000 ALTER TABLE `two_fa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utilisateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `devise` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pays` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateur`
--

LOCK TABLES `utilisateur` WRITE;
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;
/*!40000 ALTER TABLE `utilisateur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visite`
--

DROP TABLE IF EXISTS `visite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `visite` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `boutique_id` int DEFAULT NULL,
  `produit_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B09C8CBBAB677BE6` (`boutique_id`),
  KEY `IDX_B09C8CBBF347EFB` (`produit_id`),
  CONSTRAINT `FK_B09C8CBBAB677BE6` FOREIGN KEY (`boutique_id`) REFERENCES `boutique` (`id`),
  CONSTRAINT `FK_B09C8CBBF347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visite`
--

LOCK TABLES `visite` WRITE;
/*!40000 ALTER TABLE `visite` DISABLE KEYS */;
/*!40000 ALTER TABLE `visite` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-07 20:18:54
