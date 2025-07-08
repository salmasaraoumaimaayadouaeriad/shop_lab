<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250708090111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement_utilisateur (id INT AUTO_INCREMENT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, statut VARCHAR(50) NOT NULL, utilisateur_id INT NOT NULL, plan_id INT NOT NULL, INDEX IDX_EB378F92FB88E14F (utilisateur_id), INDEX IDX_EB378F92E899029B (plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE administrateur (id INT AUTO_INCREMENT NOT NULL, last_login DATETIME DEFAULT NULL, utilisateur_id INT NOT NULL, INDEX IDX_32EB52E8FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, rue VARCHAR(255) NOT NULL, ville VARCHAR(20) NOT NULL, code_postal VARCHAR(20) NOT NULL, pay VARCHAR(100) NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_C35F0816FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE avis_produit (id INT AUTO_INCREMENT NOT NULL, commentaire LONGTEXT NOT NULL, note INT NOT NULL, client_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_2A67C2119EB6921 (client_id), INDEX IDX_2A67C21F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE boutique (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, couleur VARCHAR(255) DEFAULT NULL, slogan VARCHAR(255) DEFAULT NULL, commercant_id INT NOT NULL, INDEX IDX_A1223C5483FA6DD0 (commercant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE client_final (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, adresse_principale_id INT NOT NULL, INDEX IDX_4FB8C071FB88E14F (utilisateur_id), INDEX IDX_4FB8C071C87159FC (adresse_principale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, statut VARCHAR(50) NOT NULL, montant DOUBLE PRECISION NOT NULL, methode_paiment VARCHAR(50) NOT NULL, client_id INT NOT NULL, INDEX IDX_6EEAA67D19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE commercant (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, boutique_principale_id INT DEFAULT NULL, INDEX IDX_ECB4268FFB88E14F (utilisateur_id), INDEX IDX_ECB4268F1D3B42FA (boutique_principale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE conversion (id INT AUTO_INCREMENT NOT NULL, taux DOUBLE PRECISION NOT NULL, date DATE NOT NULL, boutique_id INT NOT NULL, INDEX IDX_BD912744AB677BE6 (boutique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_8933C43219EB6921 (client_id), INDEX IDX_8933C432F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE historique_connexion (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, ip VARCHAR(45) NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_C018B2D4FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE historique_virement (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, statut VARCHAR(50) NOT NULL, paiment_id INT NOT NULL, INDEX IDX_ED95B75478789290 (paiment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE ligne_commande (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, commande_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_3170B74B82EA2E54 (commande_id), INDEX IDX_3170B74BF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE livraison (id INT AUTO_INCREMENT NOT NULL, statut VARCHAR(50) NOT NULL, commande_id INT NOT NULL, adresse_id INT NOT NULL, INDEX IDX_A60C9F1F82EA2E54 (commande_id), INDEX IDX_A60C9F1F4DE7DC5C (adresse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, message LONGTEXT NOT NULL, date DATETIME NOT NULL, lue TINYINT(1) NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_BF5476CAFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE paiement_boutique (id INT AUTO_INCREMENT NOT NULL, montant DOUBLE PRECISION NOT NULL, date DATE NOT NULL, methode VARCHAR(50) NOT NULL, boutique_id INT NOT NULL, INDEX IDX_6B85C46EAB677BE6 (boutique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, session_id INT DEFAULT NULL, INDEX IDX_24CC0DF219EB6921 (client_id), INDEX IDX_24CC0DF2613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE panier_produit (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, panier_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_D31F28A6F77D927C (panier_id), INDEX IDX_D31F28A6F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE parametre_bancaire (id INT AUTO_INCREMENT NOT NULL, iban VARCHAR(34) NOT NULL, bic VARCHAR(11) NOT NULL, commercant_id INT NOT NULL, INDEX IDX_7DC1246683FA6DD0 (commercant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE plan_abonnement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prix DOUBLE PRECISION NOT NULL, limites JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, prix DOUBLE PRECISION NOT NULL, image VARCHAR(255) NOT NULL, categorie VARCHAR(100) NOT NULL, boutique_id INT NOT NULL, INDEX IDX_29A5EC27AB677BE6 (boutique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE reset_mot_de_passe (id INT AUTO_INCREMENT NOT NULL, token VARCHAR(255) NOT NULL, date_expiration DATETIME NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_349863A3FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE revenus_commercant (id INT AUTO_INCREMENT NOT NULL, montant DOUBLE PRECISION NOT NULL, date DATE NOT NULL, commercant_id INT NOT NULL, INDEX IDX_86938BB083FA6DD0 (commercant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, permissions JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, token VARCHAR(255) NOT NULL, date_expiration DATETIME NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_D044D5D4FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE statisue_boutique (id INT AUTO_INCREMENT NOT NULL, nb_visite INT NOT NULL, revenus DOUBLE PRECISION NOT NULL, produit_populaire JSON NOT NULL, boutique_id INT NOT NULL, INDEX IDX_F672E62CAB677BE6 (boutique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE template_email (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE tentative_connexion (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, succes TINYINT(1) NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_27E0C070FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE token_verification (id INT AUTO_INCREMENT NOT NULL, token VARCHAR(255) NOT NULL, date_expiration DATETIME NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_4A078E03FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE transaction_plateforme (id INT AUTO_INCREMENT NOT NULL, montant DOUBLE PRECISION NOT NULL, date DATE NOT NULL, methode VARCHAR(50) NOT NULL, abonnement_id INT NOT NULL, INDEX IDX_D3943BA0F1D74413 (abonnement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE two_fa (id INT AUTO_INCREMENT NOT NULL, secret VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_6734C19CFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, devise VARCHAR(3) NOT NULL, pays VARCHAR(100) NOT NULL, role VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE visite (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, type VARCHAR(50) NOT NULL, boutique_id INT DEFAULT NULL, produit_id INT DEFAULT NULL, INDEX IDX_B09C8CBBAB677BE6 (boutique_id), INDEX IDX_B09C8CBBF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE abonnement_utilisateur ADD CONSTRAINT FK_EB378F92FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE abonnement_utilisateur ADD CONSTRAINT FK_EB378F92E899029B FOREIGN KEY (plan_id) REFERENCES plan_abonnement (id)');
        $this->addSql('ALTER TABLE administrateur ADD CONSTRAINT FK_32EB52E8FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F0816FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE avis_produit ADD CONSTRAINT FK_2A67C2119EB6921 FOREIGN KEY (client_id) REFERENCES client_final (id)');
        $this->addSql('ALTER TABLE avis_produit ADD CONSTRAINT FK_2A67C21F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE boutique ADD CONSTRAINT FK_A1223C5483FA6DD0 FOREIGN KEY (commercant_id) REFERENCES commercant (id)');
        $this->addSql('ALTER TABLE client_final ADD CONSTRAINT FK_4FB8C071FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE client_final ADD CONSTRAINT FK_4FB8C071C87159FC FOREIGN KEY (adresse_principale_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES client_final (id)');
        $this->addSql('ALTER TABLE commercant ADD CONSTRAINT FK_ECB4268FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE commercant ADD CONSTRAINT FK_ECB4268F1D3B42FA FOREIGN KEY (boutique_principale_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE conversion ADD CONSTRAINT FK_BD912744AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C43219EB6921 FOREIGN KEY (client_id) REFERENCES client_final (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE historique_connexion ADD CONSTRAINT FK_C018B2D4FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE historique_virement ADD CONSTRAINT FK_ED95B75478789290 FOREIGN KEY (paiment_id) REFERENCES paiement_boutique (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74BF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1F82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1F4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE paiement_boutique ADD CONSTRAINT FK_6B85C46EAB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF219EB6921 FOREIGN KEY (client_id) REFERENCES client_final (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A6F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A6F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE parametre_bancaire ADD CONSTRAINT FK_7DC1246683FA6DD0 FOREIGN KEY (commercant_id) REFERENCES commercant (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE reset_mot_de_passe ADD CONSTRAINT FK_349863A3FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE revenus_commercant ADD CONSTRAINT FK_86938BB083FA6DD0 FOREIGN KEY (commercant_id) REFERENCES commercant (id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE statisue_boutique ADD CONSTRAINT FK_F672E62CAB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE tentative_connexion ADD CONSTRAINT FK_27E0C070FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE token_verification ADD CONSTRAINT FK_4A078E03FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE transaction_plateforme ADD CONSTRAINT FK_D3943BA0F1D74413 FOREIGN KEY (abonnement_id) REFERENCES abonnement_utilisateur (id)');
        $this->addSql('ALTER TABLE two_fa ADD CONSTRAINT FK_6734C19CFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBBAB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBBF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement_utilisateur DROP FOREIGN KEY FK_EB378F92FB88E14F');
        $this->addSql('ALTER TABLE abonnement_utilisateur DROP FOREIGN KEY FK_EB378F92E899029B');
        $this->addSql('ALTER TABLE administrateur DROP FOREIGN KEY FK_32EB52E8FB88E14F');
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F0816FB88E14F');
        $this->addSql('ALTER TABLE avis_produit DROP FOREIGN KEY FK_2A67C2119EB6921');
        $this->addSql('ALTER TABLE avis_produit DROP FOREIGN KEY FK_2A67C21F347EFB');
        $this->addSql('ALTER TABLE boutique DROP FOREIGN KEY FK_A1223C5483FA6DD0');
        $this->addSql('ALTER TABLE client_final DROP FOREIGN KEY FK_4FB8C071FB88E14F');
        $this->addSql('ALTER TABLE client_final DROP FOREIGN KEY FK_4FB8C071C87159FC');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D19EB6921');
        $this->addSql('ALTER TABLE commercant DROP FOREIGN KEY FK_ECB4268FFB88E14F');
        $this->addSql('ALTER TABLE commercant DROP FOREIGN KEY FK_ECB4268F1D3B42FA');
        $this->addSql('ALTER TABLE conversion DROP FOREIGN KEY FK_BD912744AB677BE6');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C43219EB6921');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432F347EFB');
        $this->addSql('ALTER TABLE historique_connexion DROP FOREIGN KEY FK_C018B2D4FB88E14F');
        $this->addSql('ALTER TABLE historique_virement DROP FOREIGN KEY FK_ED95B75478789290');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B82EA2E54');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74BF347EFB');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1F82EA2E54');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1F4DE7DC5C');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAFB88E14F');
        $this->addSql('ALTER TABLE paiement_boutique DROP FOREIGN KEY FK_6B85C46EAB677BE6');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF219EB6921');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2613FECDF');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY FK_D31F28A6F77D927C');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY FK_D31F28A6F347EFB');
        $this->addSql('ALTER TABLE parametre_bancaire DROP FOREIGN KEY FK_7DC1246683FA6DD0');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27AB677BE6');
        $this->addSql('ALTER TABLE reset_mot_de_passe DROP FOREIGN KEY FK_349863A3FB88E14F');
        $this->addSql('ALTER TABLE revenus_commercant DROP FOREIGN KEY FK_86938BB083FA6DD0');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4FB88E14F');
        $this->addSql('ALTER TABLE statisue_boutique DROP FOREIGN KEY FK_F672E62CAB677BE6');
        $this->addSql('ALTER TABLE tentative_connexion DROP FOREIGN KEY FK_27E0C070FB88E14F');
        $this->addSql('ALTER TABLE token_verification DROP FOREIGN KEY FK_4A078E03FB88E14F');
        $this->addSql('ALTER TABLE transaction_plateforme DROP FOREIGN KEY FK_D3943BA0F1D74413');
        $this->addSql('ALTER TABLE two_fa DROP FOREIGN KEY FK_6734C19CFB88E14F');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBBAB677BE6');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBBF347EFB');
        $this->addSql('DROP TABLE abonnement_utilisateur');
        $this->addSql('DROP TABLE administrateur');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE avis_produit');
        $this->addSql('DROP TABLE boutique');
        $this->addSql('DROP TABLE client_final');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commercant');
        $this->addSql('DROP TABLE conversion');
        $this->addSql('DROP TABLE favoris');
        $this->addSql('DROP TABLE historique_connexion');
        $this->addSql('DROP TABLE historique_virement');
        $this->addSql('DROP TABLE ligne_commande');
        $this->addSql('DROP TABLE livraison');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE paiement_boutique');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE panier_produit');
        $this->addSql('DROP TABLE parametre_bancaire');
        $this->addSql('DROP TABLE plan_abonnement');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE reset_mot_de_passe');
        $this->addSql('DROP TABLE revenus_commercant');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE statisue_boutique');
        $this->addSql('DROP TABLE template_email');
        $this->addSql('DROP TABLE tentative_connexion');
        $this->addSql('DROP TABLE token_verification');
        $this->addSql('DROP TABLE transaction_plateforme');
        $this->addSql('DROP TABLE two_fa');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE visite');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
