<?php
include '../inc/fonction.php';
session_start();

$_SESSION['filtre_recherche'] = $_POST['recherche'] ?? '';
$_SESSION['filtre_cat'] = $_POST['cat'] ?? 0;
$_SESSION['filtre_dispo'] = isset($_POST['dispo']);

// Filtrer les objets
$objets_filtres = filtre_objets($_SESSION['filtre_cat'], $_SESSION['filtre_recherche'], $_SESSION['filtre_dispo']);
$emprunts = objet_emprunter();

if ($_SESSION['filtre_dispo']) {
    $objets_filtres = array_filter($objets_filtres, function($objet) use ($emprunts) {
        return !isset($emprunts[$objet['id_objet']]);
    });
}

$_SESSION['filt'] = $objets_filtres;

header('Location: accueil.php');
exit();