<?php
include "../dbcon.php";

if (isset($_POST["create_product"])) {
    $nom_produit = mysqli_real_escape_string($connection, $_POST["pname"]);
    $marque = mysqli_real_escape_string($connection, $_POST["marque"]);
    $prix = floatval($_POST["prix"]);
    $quantite = intval($_POST["quantite"]);
    $id_categorie = intval($_POST["id_categorie"]); 

    // Gestion de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageName = basename($_FILES['image']['name']);
        $uploadDir = "../uploads/";
        $uploadPath = $uploadDir . $imageName;

        // Déplace le fichier vers le dossier de destination
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            $chemin_image = "uploads/" . $imageName;
        } else {
            die("Échec du téléchargement de l'image.");
        }
    } else {
        die("Veuillez sélectionner une image valide.");
    }

    // Requête d'insertion
    $query = "INSERT INTO produits 
              (nom_produit, marque, prix, quantite, id_categorie, chemin_image)
              VALUES 
              ('$nom_produit', '$marque', $prix, $quantite, $id_categorie, '$chemin_image')";

    $result = mysqli_query($connection, $query);

    if ($result) {
        header("Location: ../index.php?msg=Le produit a été ajouté avec succès.");
        exit;
    } else {
        die("Erreur lors de l'insertion : " . mysqli_error($connection));
    }
}
?>
