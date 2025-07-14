<?php
session_start();
include('./inc/fonction.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'], $_POST['mdp'])) {
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];

        login($email, $mdp);
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="assets/images/icone.png">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Ebay</title>
    <style>
        label:hover{
            background-color: none;
        }
    </style>
</head>

<body>

    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16" fill="#ea868f">
            <path
                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
        </symbol>
    </svg>

    <!--Connexion-->
    <div class="connexion position-absolute top-50 start-50 translate-middle">
        <div class="container d-flex justify-content-center align-items-center text-center text-white">
            <div class="row">
                <div class="col bg-black">
                    <div class="mt-3">
                        <img src="assets/images/icone.png" alt="Icône" width="10%">
                    </div>
                    <div class="text-start p-5">
                        <h3 class="fw-bold mb-4">Connectez-vous à Ebay</h3>
                        <form action="index.php" method="post" class="form">
                            <input type="email" name="email" placeholder="Email" required><br><br>
                            <input type="password" name="mdp" placeholder="Mot de passe" required><br><br>
                            <input type="submit" value="Se connecter"><br><br>
                        </form>
                        <label for="creation_compte">Vous n'avez pas de compte ?</label>
                        <a href="./pages/inscription.php" id="creation_compte">Inscrivez-vous</a><br><br>
                    </div>

                    <?php if (isset($_SESSION['error'])) { ?>
                        <div class="alert alert-danger d-flex align-items-center p-2 ms-5 me-5 mb-4" role="alert">
                            <svg class="bi flex-shrink-0 ms-5" width="20" height="20" role="img" aria-label="Danger:">
                                <use xlink:href="#exclamation-triangle-fill" />
                            </svg>
                            <div class="ms-3">
                                <?= $_SESSION['error']; ?>
                            </div>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php } ?>

                </div>
            </div>
        </div>


        <script src="bootstrap/js/bootstrap.bundle.js"></script>
</body>

</html>