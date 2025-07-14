<?php
require('connexion.php');

/* Inscription et connexion */
function inscription($email, $nom, $date_de_naissance, $mdp)
{
    $connexion = connection();

    $requete = "INSERT INTO UTILISATEUR (Email, Nom, Date_naissance, Mot_de_passe) VALUES ('%s', '%s', '%s', '%s')";
    $final = sprintf($requete, $email, $nom, $date_de_naissance, $mdp);
    $insertion = mysqli_query($connexion, $final);
    header('Location: ../index.php');
}

function login($email, $mdp)
{
    $connexion = connection();

    $requete = "SELECT * FROM Membre WHERE email = '%s' AND mot_de_passe = '%s'";
    $final = sprintf($requete, $email, $mdp);
    $result = mysqli_query($connexion, $final);
    $data = mysqli_fetch_assoc($result);
    if ($data) {
        unset($data['Mot_de_passe']);
        $_SESSION['user'] = $data;
        header('Location: ./pages/accueil.php');
        exit();
    } else {
        $_SESSION['error'] = "Email ou mot de passe incorrect !";
    }
}

function list_object()
{
    $connexion = connection();

    $sql = "SELECT * FROM Objet";

    $trait = mysqli_query($connexion, $sql);

    $liste = array();

    while ($result = mysqli_fetch_assoc($trait)) {
        $liste[] = $result;
    }

    mysqli_free_result($trait);
    deconnection($connexion);
    return $liste;
}


function objet_emprunter()
{
    $connexion = connection();

    $sql = "SELECT id_objet, date_retour FROM emprunt WHERE date_retour IS NOT NULL";
    $resultat = mysqli_query($connexion, $sql);

    $liste = array();
    while ($row = mysqli_fetch_assoc($resultat)) {
        $liste[$row['id_objet']] = $row['date_retour'];
    }

    mysqli_free_result($resultat);
    deconnection($connexion);

    return $liste;
}

function list_categorie()
{
    $connexion = connection();

    $sql = "SELECT * FROM categorie_objet";

    $trait = mysqli_query($connexion, $sql);

    $liste = array();

    while ($result = mysqli_fetch_assoc($trait)) {
        $liste[] = $result;
    }

    mysqli_free_result($trait);
    deconnection($connexion);
    return $liste;
}

function filtre_objets_par_categorie($id_categorie)
{
    $connexion = connection();

    $sql = "SELECT * FROM Objet WHERE id_categorie = %d";
    $sql = sprintf($sql, $id_categorie);

    $trait = mysqli_query($connexion, $sql);

    $liste = array();
    while ($result = mysqli_fetch_assoc($trait)) {
        $liste[] = $result;
    }

    mysqli_free_result($trait);
    deconnection($connexion);
    return $liste;
}


function get_img_obj($id_objet)
{
    $connexion = connection();

    $sql = "SELECT nom_image FROM images_objet WHERE id_objet = %d";
    $sql = sprintf($sql, $id_objet);

    $resultat = mysqli_query($connexion, $sql);
    $data = mysqli_fetch_assoc($resultat);

    mysqli_free_result($resultat);
    deconnection($connexion);

    return $data['nom_image'] ?? '../assets/img/default.png';
}

function ajout_img_objets($id_objet, $nom_image)
{
    $connexion = connection();

    $sql = "INSERT INTO images_objet (id_objet, nom_image) VALUES (%d, '%s')";
    $sql = sprintf($sql, $id_objet, $nom_image);

    mysqli_query($connexion, $sql);
    deconnection($connexion);
}



function ajout_objet($nom_objet, $id_categorie, $id_membre)
{
    $connexion = connection();

    $sql = "INSERT INTO Objet (nom_objet, id_categorie, id_membre) VALUES ('%s', %d, %d)";
    $sql = sprintf($sql, mysqli_real_escape_string($connexion, $nom_objet), $id_categorie, $id_membre);

    $result = mysqli_query($connexion, $sql);
    $id_objet = mysqli_insert_id($connexion);

    deconnection($connexion);
    return $id_objet;
}

function traiter_upload_images($id_objet)
{
    $uploadDir = '../assets/img/objets/';
    $allowedMimeTypes = ['image/jpeg', 'image/png'];
    $maxSize = 5 * 1024 * 1024; // 5 MB

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['images'])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $fileName = $_FILES['images']['name'][$key];
            $fileSize = $_FILES['images']['size'][$key];
            $fileType = mime_content_type($tmpName);

            // Vérifier le type et la taille du fichier
            if (in_array($fileType, $allowedMimeTypes) && $fileSize <= $maxSize) {
                $destination = $uploadDir . basename($fileName);

                // Déplacer le fichier vers le dossier de destination
                if (move_uploaded_file($tmpName, $destination)) {
                    // Insérer le chemin de l'image dans la base de données
                    $connexion = connection();
                    $sql = "INSERT INTO images_objet (id_objet, nom_image) VALUES ($id_objet, '$destination')";
                    mysqli_query($connexion, $sql);
                    deconnection($connexion);
                }
            }
        }
    }
}

function info_membre($id_membre)
{
    $connexion = connection();

    $sql = "SELECT m.*, o.id_objet, o.nom_objet, c.nom_categorie
            FROM Membre m
            JOIN Objet o ON m.id_membre = o.id_membre
            JOIN categorie_objet c ON o.id_categorie = c.id_categorie
            WHERE m.id_membre = %d";
    $sql = sprintf($sql, $id_membre);

    $resultat = mysqli_query($connexion, $sql);

    $data = [];
    $data['objets'] = [];
    while ($row = mysqli_fetch_assoc($resultat)) {
        if (empty($data['id_membre'])) {
            $data['id_membre'] = $row['id_membre'];
            $data['nom'] = $row['nom'];
            $data['email'] = $row['email'];
            $data['date_naissance'] = $row['date_naissance'];
            $data['image_profile'] = $row['image_profile'];
        }
        if (!empty($row['id_objet'])) {
            $data['objets'][] = [
                'id_objet' => $row['id_objet'],
                'nom_objet' => $row['nom_objet'],
                'nom_categorie' => $row['nom_categorie']
            ];
        }
    }

    mysqli_free_result($resultat);
    deconnection($connexion);

    return $data;
}

function filtre_objets($id_categorie = 0, $recherche = '', $disponible_seulement = false) {
    $connexion = connection();
    
    // Requête de base
    $sql = "SELECT o.* FROM Objet o WHERE 1=1";
    
    // Filtre par catégorie
    if ($id_categorie > 0) {
        $sql .= " AND o.id_categorie = $id_categorie";
    }
    
    // Filtre par recherche
    if (!empty($recherche)) {
        $sql .= " AND o.nom_objet LIKE '%$recherche%'";
    }
    
    $result = mysqli_query($connexion, $sql);
    $objets = array();
    
    while ($row = mysqli_fetch_assoc($result)) {
        $objets[] = $row;
    }
    
    mysqli_free_result($result);
    deconnection($connexion);
    
    return $objets;
}

function emprunter_objet($id_objet, $id_utilisateur, $date_retour) {
    $mysqli = connection();
    
    $sql = "INSERT INTO emprunt (id_objet, id_membre, date_emprunt, date_retour) 
            VALUES (?, ?, NOW(), ?)";
    $stmt = $mysqli->prepare($sql);
    
    if ($stmt === false) {
        throw new Exception("Erreur de préparation: " . $mysqli->error);
    }
    
    $stmt->bind_param("iis", $id_objet, $id_utilisateur, $date_retour);
    $success = $stmt->execute();
    
    if (!$success) {
        throw new Exception("Erreur d'exécution: " . $stmt->error);
    }
    
    return $success;
}

function get_objet_by_id($id_objet) {
    $connexion = connection();
    $id_objet = intval($id_objet);
    
    $sql = "SELECT * FROM Objet WHERE id_objet = ?";
    $stmt = $connexion->prepare($sql);
    
    if ($stmt === false) {
        throw new Exception("Erreur de préparation: " . $connexion->error);
    }
    
    $stmt->bind_param("i", $id_objet);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Objet non trouvé");
    }
    
    return $result->fetch_assoc();
}

function list_obj_emprunte($id_membre)
{
    $connexion = connection();

    $sql = "SELECT o.id_objet, o.nom_objet, e.date_retour, i.nom_image 
            FROM Objet o 
            JOIN emprunt e ON o.id_objet = e.id_objet 
            JOIN images_objet i ON e.id_objet = i.id_objet
            WHERE e.id_membre = %d";
    $sql = sprintf($sql, $id_membre);

    $trait = mysqli_query($connexion, $sql);

    $liste = array();

    while ($res = mysqli_fetch_assoc($trait)) {
        $liste[] = $res;
    }
    mysqli_free_result($trait);
    deconnection($connexion);
    return $liste;
}

function verifier_emprunt_utilisateur($id_membre, $id_objet) {
    $connexion = connection();
    
    $sql = "SELECT * FROM emprunt 
            WHERE id_membre = $id_membre 
            AND id_objet = $id_objet 
            AND date_retour IS NULL";
    
    $resultat = mysqli_query($connexion, $sql);
    $existe = mysqli_num_rows($resultat) > 0;
    
    mysqli_free_result($resultat);
    deconnection($connexion);
    
    return $existe;
}

function marquer_objet_abime($id_objet) {
    $connexion = connection();
    
    $sql = "UPDATE Objet SET abime = 1 WHERE id_objet = $id_objet";
    $result = mysqli_query($connexion, $sql);
    
    deconnection($connexion);
    return $result;
}

function retourner_objet($id_objet, $id_membre) {
    $connexion = connection();
    $date_retour = date('Y-m-d');
    
    $sql = "UPDATE emprunt 
            SET date_retour = '$date_retour' 
            WHERE id_objet = $id_objet 
            AND id_membre = $id_membre 
            AND date_retour IS NULL";
    
    $result = mysqli_query($connexion, $sql);
    deconnection($connexion);
    return $result;
}

function verifier_etat_objet($id_objet) {
    $connexion = connection();
    
    $sql = "SELECT abime FROM Objet WHERE id_objet = $id_objet";
    $resultat = mysqli_query($connexion, $sql);
    $objet = mysqli_fetch_assoc($resultat);
    
    mysqli_free_result($resultat);
    deconnection($connexion);
    
    return $objet['abime'] == 0;
}
?>