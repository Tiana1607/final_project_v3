create database emprunt;
use emprunt;

create table Membre(
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    genre ENUM('H', 'F') NOT NULL,
    date_naissance DATE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    ville VARCHAR(50) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    image_profile VARCHAR(255) DEFAULT '../assets/img/defaut.png'
);

create table categorie_objet(
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(50) NOT NULL
);

create table Objet(
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet VARCHAR(100) NOT NULL,
    id_membre INT,
    id_categorie INT,
    FOREIGN KEY (id_categorie) REFERENCES categorie_objet(id_categorie)
);

create table images_objet(
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    nom_image VARCHAR(255)  VARCHAR(255) DEFAULT '../assets/img/default.png',
    FOREIGN KEY (id_objet) REFERENCES Objet(id_objet)
);

create table emprunt(
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    id_membre INT,
    date_emprunt DATE NOT NULL,
    date_retour DATE,
    FOREIGN KEY (id_objet) REFERENCES Objet(id_objet),
    FOREIGN KEY (id_membre) REFERENCES Membre(id_membre)
);

INSERT INTO Membre (nom, genre, date_naissance, email, ville, mot_de_passe) VALUES
('Alice Dupont', 'F', '1990-04-12', 'alice.dupont@email.com', 'Lyon', 'mdp123'),
('Bob Martin', 'H', '1985-07-30', 'bob.martin@email.com', 'Paris', 'mdp123'),
('Claire Moreau', 'F', '1992-03-18', 'claire.moreau@email.com', 'Marseille', 'mdp123'),
('David Lefevre', 'H', '1988-11-05', 'david.lefevre@email.com', 'Toulouse', 'mdp123');

INSERT INTO categorie_objet (nom_categorie) VALUES
('Esthétique'),
('Bricolage'),
('Mécanique'),
('Cuisine');

-- Objets pour Alice (id_membre = 1)
INSERT INTO Objet (nom_objet, id_membre, id_categorie) VALUES
('Sèche-cheveux', 1, 1),
('Lime à ongles électrique', 1, 1),
('Perceuse Bosch', 1, 2),
('Tournevis plat', 1, 2),
('Pompe à vélo', 1, 3),
('Clé à molette', 1, 3),
('Mixeur', 1, 4),
('Poêle anti-adhésive', 1, 4),
('Miroir LED', 1, 1),
('Pinceau maquillage', 1, 1);

-- Objets pour Bob (id_membre = 2)
INSERT INTO Objet (nom_objet, id_membre, id_categorie) VALUES
('Tondeuse à barbe', 2, 1),
('Marteau', 2, 2),
('Scie sauteuse', 2, 2),
('Pompe voiture', 2, 3),
('Crics', 2, 3),
('Blender', 2, 4),
('Cocotte-minute', 2, 4),
('Friteuse', 2, 4),
('Épilateur électrique', 2, 1),
('Niveau à bulle', 2, 2);

-- Objets pour Claire (id_membre = 3)
INSERT INTO Objet (nom_objet, id_membre, id_categorie) VALUES
('Brosse lissante', 3, 1),
('Fer à lisser', 3, 1),
('Tournevis cruciforme', 3, 2),
('Perforateur mural', 3, 2),
('Compresseur', 3, 3),
('Clé dynamométrique', 3, 3),
('Robot pâtissier', 3, 4),
('Machine à pain', 3, 4),
('Vernis à ongles UV', 3, 1),
('Lampe esthétique', 3, 1);

-- Objets pour David (id_membre = 4)
INSERT INTO Objet (nom_objet, id_membre, id_categorie) VALUES
('Ponceuse orbitale', 4, 2),
('Clé plate', 4, 3),
('Fouet électrique', 4, 4),
('Casserole inox', 4, 4),
('Pied mixeur', 4, 4),
('Grille-pain', 4, 4),
('Pistolet à colle', 4, 2),
('Peigne ionique', 4, 1),
('Trousse manucure', 4, 1),
('Cric hydraulique', 4, 3);

INSERT INTO images_objet (id_objet, nom_image) VALUES
(1, '../assets/img/seche_cheveux.jpg'),
(3, '../assets/img/perceuse.jpg'),
(11, '../assets/img/tondeuse.jpg'),
(14, '../assets/img/pompe_voiture.jpg'),
(21, '../assets/img/brosse_lissante.jpg'),
(26, '../assets/img/robot_patissier.jpg'),
(31, '../assets/img/ponceuse.jpg'),
(33, '../assets/img/fouet.jpg');

INSERT INTO emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(1, 2, '2025-06-01', '2025-06-05'),
(3, 3, '2025-06-02', '2025-06-07'),
(5, 4, '2025-06-03', '2025-06-08'),
(11, 1, '2025-06-04', '2025-06-10'),
(14, 3, '2025-06-05', '2025-06-12'),
(21, 4, '2025-06-06', '2025-06-13'),
(26, 2, '2025-06-07', '2025-06-14'),
(31, 1, '2025-06-08', '2025-06-15'),
(33, 3, '2025-06-09', '2025-06-16'),
(36, 2, '2025-06-10', '2025-06-17');

