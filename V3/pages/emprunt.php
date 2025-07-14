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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date_retour = date('Y-m-d', strtotime($_POST['date_retour']));
    emprunter_objet($id_objet, $_SESSION['user']['id_membre'], $date_retour);
    header('Location: accueil.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emprunter un objet</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <style>
        .emprunt-container {
            max-width: 500px;
            margin: 2rem auto;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .date-picker {
            margin: 1.5rem 0;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <div class="emprunt-container bg-white">
            <h2 class="text-center mb-4">Emprunter <?= htmlspecialchars($objet['nom_objet']) ?></h2>
            
            <div class="text-center mb-4">
                <img src="<?= get_img_obj($objet['id_objet']) ?>" alt="<?= htmlspecialchars($objet['nom_objet']) ?>" class="img-fluid rounded" style="max-height: 200px;">
            </div>
            
            <form method="POST">
                <div class="form-group date-picker">
                    <label for="date_retour">Date de retour :</label>
                    <input type="date" class="form-control" id="date_retour" name="date_retour" required 
                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>" 
                           max="<?= date('Y-m-d', strtotime('+1 month')) ?>">
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">Confirmer l'emprunt</button>
                    <a href="accueil.php" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Définir la date minimale (demain) par défaut
        document.getElementById('date_retour').valueAsDate = new Date(new Date().getTime() + 86400000);
    </script>
</body>
</html>