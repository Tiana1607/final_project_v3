<?php
include '../inc/fonction.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}
$user = $_SESSION['user'];

$list_objet = list_object();
$emprunter = objet_emprunter();
$list_categ = list_categorie();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <title>Accueil</title>
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

    <main>

        <section class="profil container mb-4">
            <div class="container text-center">
                <img src="<?= $user['image_profile'] ?>" alt="">
                <h3 class="ms-2"><?= $user['nom'] ?></h3>
            </div>
            <div class="container text-center">
                <a href="ajout.php"><button type="submit">Ajouter un nouvel objet</button></a>
            </div>
            <div class="container text-center">
                <a href="fiche.php"><button type="submit">Mon profil</button></a>
            </div>
        </section>

        <!-- Formulaire simplifié -->
        <section class="mb-4">
            <div class="container">
                <form method="post" action="trait_filtre.php" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="recherche" class="form-control" placeholder="Rechercher...">
                    </div>
                    <div class="col-md-3">
                        <select name="cat" class="form-select">
                            <option value="0">Toutes catégories</option>
                            <?php foreach ($list_categ as $cat): ?>
                                <option value="<?= $cat['id_categorie'] ?>"><?= $cat['nom_categorie'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="checkbox" name="dispo" id="dispo" class="form-check-input">
                            <label for="dispo" class="form-check-label">Disponible seulement</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                    </div>
                </form>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row g-4 justify-content-center">
                    <?php
                    $objets_a_afficher = isset($_SESSION['filt']) ? $_SESSION['filt'] : $list_objet;

                    foreach ($objets_a_afficher as $objet): ?>
                        <!-- Dans la boucle foreach des objets -->
                        <article class="col-md-3 col-sm-6 col-12">
                            <?php $image_obj = get_img_obj($objet['id_objet']); ?>
                            <div class="card h-100">
                                <img src="<?= $image_obj ?>" class="card-img-top" alt="Image Objet" style="height: 260px;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= htmlspecialchars($objet['nom_objet']) ?></h5>
                                    <?php if (isset($emprunter[$objet['id_objet']])): ?>
                                        <p class="text-danger">Déjà emprunté</p>
                                        <h6 class="text-muted mb-3">Retour prévu le :
                                            <?= htmlspecialchars($emprunter[$objet['id_objet']]) ?></h6>
                                    <?php else: ?>
                                        <p class="text-success">Disponible</p>
                                        <a href="emprunt.php?id_objet=<?= $objet['id_objet'] ?>"
                                            class="btn btn-success mt-auto">Emprunter</a>
                                    <?php endif; ?>
                                    <a href="fiche_objet.php?id_objet=<?= $objet['id_objet'] ?>"
                                        class="btn btn-primary mt-2">Voir la fiche</a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach;

                    if (isset($_SESSION['filt'])) {
                        unset($_SESSION['filt']);
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>