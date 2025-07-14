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

$id_objet = intval($_GET['id_objet']);
$connexion = connection();

// Récupérer les détails de l'objet
$sql_objet = "SELECT o.*, c.nom_categorie, m.nom AS nom_membre
              FROM Objet o
              JOIN categorie_objet c ON o.id_categorie = c.id_categorie
              JOIN Membre m ON o.id_membre = m.id_membre
              WHERE o.id_objet = $id_objet";
$result_objet = mysqli_query($connexion, $sql_objet);
$objet = mysqli_fetch_assoc($result_objet);

// Récupérer les images de l'objet
$sql_images = "SELECT nom_image FROM images_objet WHERE id_objet = $id_objet";
$result_images = mysqli_query($connexion, $sql_images);
$images = [];
while ($row = mysqli_fetch_assoc($result_images)) {
    $images[] = $row['nom_image'];
}

// Récupérer l'historique des emprunts
$sql_emprunts = "SELECT e.date_emprunt, e.date_retour, m.nom AS nom_emprunteur
                 FROM emprunt e
                 JOIN Membre m ON e.id_membre = m.id_membre
                 WHERE e.id_objet = $id_objet";
$result_emprunts = mysqli_query($connexion, $sql_emprunts);
$emprunts = [];
while ($row = mysqli_fetch_assoc($result_emprunts)) {
    $emprunts[] = $row;
}

mysqli_free_result($result_objet);
mysqli_free_result($result_images);
mysqli_free_result($result_emprunts);
deconnection($connexion);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <title>Fiche Objet</title>
</head>

<body>
    <header class="mt-2 mb-5">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a href="accueil.php" class="navbar-brand">
                    <span class="fs-2 fw-bold">Ebay</span>
                </a>
            </div>
        </nav>
    </header>

    <main class="container">
        <section class="objet mb-4">
            <h2 class="text-center"><?= htmlspecialchars($objet['nom_objet']) ?></h2>
            <div class="text-center mb-4">
                <img src="<?= htmlspecialchars($images[0] ?? 'default.png') ?>"
                    alt="Image principale" class="img-fluid" style="max-width: 400px;">
            </div>
            <div class="text-center">
                <h5>Catégorie : <?= htmlspecialchars($objet['nom_categorie']) ?></h5>
                <h5>Propriétaire : <?= htmlspecialchars($objet['nom_membre']) ?></h5>
            </div>
        </section>

        <section class="images mb-4">
            <h3 class="text-center">Autres images</h3>
            <div class="row g-4">
                <?php foreach ($images as $image): ?>
                    <div class="col-md-3 col-sm-6 col-12">
                        <img src="<?= htmlspecialchars($image)?>" alt="Image Objet" class="img-fluid" style="height: 200px;">
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="historique mb-4">
            <h3 class="text-center">Historique des emprunts</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nom de l'emprunteur</th>
                        <th>Date d'emprunt</th>
                        <th>Date de retour</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emprunts as $emprunt): ?>
                        <tr>
                            <td><?= htmlspecialchars($emprunt['nom_emprunteur']) ?></td>
                            <td><?= htmlspecialchars($emprunt['date_emprunt']) ?></td>
                            <td><?= htmlspecialchars($emprunt['date_retour'] ?? 'Non retourné') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>