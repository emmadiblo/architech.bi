<?php include "../dbcon.php"; ?>
<?php include "../include/header.php"; ?>

<?php
if (isset($_POST['create_category'])) {
    $nom_categorie = mysqli_real_escape_string($connection, $_POST['nom_categorie']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);

    // Vérifie si le nom existe déjà
    $checkQuery = "SELECT * FROM categories WHERE nom_categorie = '$nom_categorie'";
    $checkResult = mysqli_query($connection, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        $msg = "Cette catégorie existe déjà.";
    } else {
        $query = "INSERT INTO categories (nom_categorie, description) 
                  VALUES ('$nom_categorie', '$description')";

        if (mysqli_query($connection, $query)) {
            $msg = "Catégorie ajoutée avec succès.";
        } else {
            $msg = "Erreur : " . mysqli_error($connection);
        }
    }
}
?>

<div class="container mt-5 mb-5">
    <h2 class="mb-3">Créer une nouvelle catégorie</h2>

    <?php if (isset($msg)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="POST" action="create_category.php">
        <div class="form-group mb-3">
            <label for="nom_categorie">Nom de la catégorie</label>
            <input type="text" class="form-control" id="nom_categorie" name="nom_categorie" required>
        </div>

        <div class="form-group mb-3">
            <label for="description">Description (facultative)</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>

        <input type="submit" name="create_category" class="btn btn-primary" value="Créer la catégorie">
        <a href="../index.php" class="btn btn-secondary">Retour</a>
    </form>
</div>

<?php include "../include/footer.php"; ?>
