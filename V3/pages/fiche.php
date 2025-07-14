<?php
include '../inc/fonction.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}

$user = $_SESSION['user'];
$membre_info = info_membre($user['id_membre']);

// Regrouper les objets par catégorie
$grouped_objects = [];
foreach ($membre_info['objets'] as $objet) {
    $grouped_objects[$objet['nom_categorie']][] = $objet;
}

$obj_empruntes = list_obj_emprunte($user['id_membre']);
$im = get_img_obj($id_objet);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <title>Fiche Membre</title>
</head>

<body>
    <header class="mt-2 mb-5">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a href="#" class="navbar-brand">
                    <span class="fs-2 fw-bold">Ebay</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>
    </header>

    <main class="container">
        <section class="profil mb-4">
            <div class="text-center">
                <img src="<?= $user['image_profile'] ?>" alt="Image de profil" class="rounded-circle"
                    style="width: 150px; height: 150px;">
                <h3><?= htmlspecialchars($user['nom']) ?></h3>
                <p>Email : <?= htmlspecialchars($user['email']) ?></p>
                <p>Date de naissance : <?= htmlspecialchars($user['date_naissance']) ?></p>
            </div>
        </section>

        <section class="objets">
            <h2 class="text-center mb-4">Objets du membre</h2>
            <?php foreach ($grouped_objects as $categorie => $objets): ?>
                <div class="mb-4">
                    <h3 class="text-primary"><?= htmlspecialchars($categorie) ?></h3>
                    <div class="row g-4">
                        <?php foreach ($objets as $objet): ?>
                            <div class="col-md-3 col-sm-6 col-12">
                                <?php $image_obj = get_img_obj($objet['id_objet']); ?>
                                <div class="card h-100">
                                    <img src="<?= $image_obj ?>" class="card-img-top" alt="Image Objet" style="height: 260px;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($objet['nom_objet']) ?></h5>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="objets">
            <h2 class="text-center mb-4">Objets empruntés</h2>
                <div class="mb-4">
                    <div class="row g-4">
                    <?php foreach ($obj_empruntes as $ob): ?>
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="card h-100">
                                <img src="<?= $ob['nom_image'] ?>" class="card-img-top" alt="Image Objet" style="height: 260px;">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($ob['nom_objet']) ?></h5>
                                </div>
                                <a href="confirmer_retour.php?id_objet=<?= $ob['id_objet'] ?>"
                                            class="btn btn-success mt-auto">Retourner</a>
                            </div>
                            
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
        </section>
    </main>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>