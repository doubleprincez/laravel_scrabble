CREATE TABLE `Joueur` (
    `idJoueur` INT NOT NULL AUTO_INCREMENT,
    `nom` varchar(40) NOT NULL,
    `photo` VARCHAR(255) NOT NULL,
    `chevalet` VARCHAR(7),
    `score` INT,
    `statutJoueur` BOOLEAN NOT NULL,
    PRIMARY KEY (`idJoueur`)
);

CREATE TABLE `Partie` (
	`idPartie` INT NOT NULL AUTO_INCREMENT,
	`idJoueur2` INT,
	`idJoueur3` INT,
	`idJoueur4` INT,
	`typePartie` INT(1) NOT NULL,
	`Reserve` varchar(50),
	`grille` varchar(225),
	`dateCreation` TIMESTAMP NOT NULL,
	`dateDebutPartie` TIMESTAMP NOT NULL,
	`dateFin` TIMESTAMP NOT NULL,
	`statutPartie` VARCHAR(255) DEFAULT 'enAttente',
	PRIMARY KEY (`idPartie`)
);

CREATE TABLE `Message` (
	`idMessage` VARCHAR(255) NOT NULL,
	`dateCreation` TIMESTAMP NOT NULL,
	`envoyeur` INT NOT NULL,
	`partie` INT NOT NULL,
	`contenu` varchar(50) NOT NULL,
	PRIMARY KEY (`idMessage`)
);

CREATE TABLE `Lettres` (
	`lettre` varchar(1) NOT NULL,
	`valeur` INT(2) NOT NULL,
	PRIMARY KEY (`lettre`)
);

ALTER TABLE `Partie` ADD CONSTRAINT `Partie_fk0` FOREIGN KEY (`idJoueur2`) REFERENCES `Joueur`(`idJoueur`);

ALTER TABLE `Partie` ADD CONSTRAINT `Partie_fk1` FOREIGN KEY (`idJoueur3`) REFERENCES `Joueur`(`idJoueur`);

ALTER TABLE `Partie` ADD CONSTRAINT `Partie_fk2` FOREIGN KEY (`idJoueur4`) REFERENCES `Joueur`(`idJoueur`);

ALTER TABLE `Message` ADD CONSTRAINT `Message_fk0` FOREIGN KEY (`envoyeur`) REFERENCES `Joueur`(`idJoueur`);

ALTER TABLE `Message` ADD CONSTRAINT `Message_fk1` FOREIGN KEY (`partie`) REFERENCES `Partie`(`idPartie`);
