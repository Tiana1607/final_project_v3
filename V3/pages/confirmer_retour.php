<?php
include '../inc/fonction.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}

if (!isset($_GET['id_objet'])) {
    header('Location: accueil.php');
    exit();
}

$id_objet = $_GET['id_objet'];
$objet = get_objet_by_id($id_objet);
$user = $_SESSION['user'];

// Vérifier si l'utilisateur a bien emprunté cet objet
if (!verifier_emprunt_utilisateur($user['id_membre'], $id_objet)) {
    header('Location: accueil.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['etat'])) {
        if ($_POST['etat'] === 'abime') {
            if (marquer_objet_abime($id_objet)) {
                retourner_objet($id_objet, $user['id_membre']);
                $_SESSION['message'] = "L'objet a été marqué comme abîmé et retourné.";
            } else {
                $_SESSION['erreur'] = "Erreur lors du marquage de l'objet comme abîmé.";
            }
        } elseif ($_POST['etat'] === 'ok') {
            if (verifier_etat_objet($id_objet)) {
                if (retourner_objet($id_objet, $user['id_membre'])) {
                    $_SESSION['message'] = "L'objet a été retourné avec succès.";
                } else {
                    $_SESSION['erreur'] = "Erreur lors du retour de l'objet.";
                }
            } else {
                $_SESSION['erreur'] = "Cet objet est marqué comme abîmé et ne peut pas être retourné normalement.";
            }
        }
        header('Location: accueil.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmer le retour</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h2>Confirmer le retour</h2>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="<?= get_img_obj($objet['id_objet']) ?>" 
                         alt="<?= htmlspecialchars($objet['nom_objet']) ?>" 
                         class="img-fluid rounded" style="max-height: 200px;">
                    <h4><?= htmlspecialchars($objet['nom_objet']) ?></h4>
                </div>
                
                <form method="POST">
                    <div class="form-group">
                        <label>État de l'objet :</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="etat" id="etat_ok" value="ok" checked>
                            <label class="form-check-label" for="etat_ok">
                                L'objet est en bon état
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="etat" id="etat_abime" value="abime">
                            <label class="form-check-label" for="etat_abime">
                                L'objet est abîmé
                            </label>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Confirmer le retour</button>
                        <a href="accueil.php" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>