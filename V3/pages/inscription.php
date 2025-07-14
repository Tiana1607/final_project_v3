<?php
    session_start();
    include('../inc/fonction.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['nom'], $_POST['date'], $_POST['email'], $_POST['mdp'])) {
            $nom = $_POST['nom'];
            $date = $_POST['date'];
            $email = $_POST['email'];
            $mdp = $_POST['mdp'];

            inscription($email, $nom, $date, $mdp);
        } 
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/images/icone.png">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Ebay</title>
</head>

<body>

    <!--Inscription-->
    <div class="inscription position-absolute top-50 start-50 translate-middle">
        <div class="container d-flex justify-content-center align-items-center text-center text-white">
            <div class="row">
                <div class="col bg-black">
                    <div class="mt-3">
                        <img src="../assets/images/icone.png" alt="Icône" width="10%">
                    </div>
                    <div class="text-start p-5">
                        <h3 class="fw-bold mb-4">Créer votre compte</h3>
                        <form action="inscription.php" method="post" class="form">
                            <input type="text" name="nom" placeholder="Nom et prénom(s)" required><br><br>
                            <input type="email" name="email" placeholder="Email" required><br><br>
                            <h6 class="fw-bold" style="color: #dbe7e9;">Date de naissance</h6>
                            <input type="date" name="date" required><br><br>
                            <input type="password" name="mdp" placeholder="Mot de passe" required><br><br>
                            <input type="submit" value="S'inscrire"><br><br>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.js"></script>
</body>

</html>