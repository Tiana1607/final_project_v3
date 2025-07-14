<?php
include '../inc/fonction.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}

$user = $_SESSION['user'];
$list_categ = list_categorie();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_objet = $_POST['nom_objet'];
    $id_categorie = intval($_POST['id_categorie']);
    $id_membre = $user['id_membre'];

    // Ajouter l'objet
    $id_objet = ajout_objet($nom_objet, $id_categorie, $id_membre);

    // Traiter l'upload des images
    traiter_upload_images($id_objet);

    header('Location: accueil.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <title>Ajouter un objet</title>
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
        <h2 class="text-center mb-4">Ajouter un nouvel objet</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nom_objet" class="form-label">Nom de l'objet</label>
                <input type="text" class="form-control" id="nom_objet" name="nom_objet" required>
            </div>
            <div class="mb-3">
                <label for="id_categorie" class="form-label">Catégorie</label>
                <select class="form-select" id="id_categorie" name="id_categorie" required>
                    <?php foreach ($list_categ as $cat): ?>
                        <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="images" class="form-label">Images</label>
                <input type="file" class="form-control" id="images" name="images[]" multiple>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </main>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>