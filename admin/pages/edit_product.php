<?php include "../dbcon.php"; ?>
<?php include "../include/header.php"; ?>

<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $query = "SELECT * FROM produits WHERE id_produit = $id";
    $result = mysqli_query($connection, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        die("Produit introuvable.");
    }

    $row = mysqli_fetch_assoc($result);
}
?>

<?php
if (isset($_POST['update_product'])) {
    $nom_produit = mysqli_real_escape_string($connection, $_POST["nom_produit"]);
    $marque = mysqli_real_escape_string($connection, $_POST["marque"]);
    $prix = floatval($_POST["prix"]);
    $quantite = intval($_POST["quantite"]);
    $id_categorie = intval($_POST["id_categorie"]);

    // Récupère l'image actuelle
    $chemin_image = $row['chemin_image'];

    if (!empty($_FILES['image']['name'])) {
        $imageName = basename($_FILES['image']['name']);
        $uploadDir = "../uploads/";
        $uploadPath = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            $chemin_image = "uploads/" . $imageName;
        } else {
            die("Échec du téléchargement de l'image.");
        }
    }

    $query = "UPDATE produits 
              SET nom_produit = '$nom_produit', 
                  marque = '$marque', 
                  prix = $prix, 
                  quantite = $quantite, 
                  id_categorie = $id_categorie, 
                  chemin_image = '$chemin_image' 
              WHERE id_produit = $id";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Erreur de mise à jour : " . mysqli_error($connection));
    } else {
        header("Location: ../index.php?msg=Le produit a été mis à jour.");
        exit;
    }
}
?>

<div class="container mt-5 mb-5">
    <h2 class="mb-4">Modifier le produit</h2>
    <form action="edit_product.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nom_produit" class="form-label">Nom du Produit</label>
            <input type="text" class="form-control" name="nom_produit" value="<?php echo htmlspecialchars($row['nom_produit']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="marque" class="form-label">Marque</label>
            <input type="text" class="form-control" name="marque" value="<?php echo htmlspecialchars($row['marque']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="prix" class="form-label">Prix</label>
            <input type="text" class="form-control" name="prix" value="<?php echo htmlspecialchars($row['prix']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="quantite" class="form-label">Quantité</label>
            <input type="number" class="form-control" name="quantite" value="<?php echo htmlspecialchars($row['quantite']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="id_categorie" class="form-label">Catégorie</label>
            <select name="id_categorie" class="form-select" required>
                <option value="">-- Choisir une catégorie --</option>
                <?php
                $catQuery = "SELECT id_categorie, nom_categorie FROM categories";
                $catResult = mysqli_query($connection, $catQuery);
                while ($cat = mysqli_fetch_assoc($catResult)) {
                    $selected = ($cat['id_categorie'] == $row['id_categorie']) ? 'selected' : '';
                    echo "<option value='{$cat['id_categorie']}' $selected>{$cat['nom_categorie']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image (laisser vide pour garder l'actuelle)</label>
            <input type="file" class="form-control" name="image" accept="image/*">
            <div class="mt-2">
                <img src="../<?php echo htmlspecialchars($row['chemin_image']); ?>" style="width: 120px;" alt="Image actuelle">
            </div>
        </div>

        <button type="submit" class="btn btn-primary" name="update_product">Mettre à jour</button>
        <a href="../index.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php include "../include/footer.php"; ?>


<div class="container mt-3 mb-3">
    <h2 class="mb-3">Modifier un produit</h2>
    <form action="edit_product.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group mb-2">
            <label for="nom_produit">Nom du Produit</label>
            <input type="text" class="form-control" id="nom_produit" name="nom_produit" value="<?= htmlspecialchars($row['nom_produit']) ?>" required>
        </div>

        <div class="form-group mb-2">
            <label for="marque">Marque</label>
            <input type="text" class="form-control" name="marque" value="<?= htmlspecialchars($row['marque']) ?>" required>
        </div>

        <div class="form-group mb-2">
            <label for="categorie">Catégorie</label>
            <select name="categorie" id="categorie" class="form-control" required>
                <option value="">-- Sélectionner --</option>
                <?php
                $catQuery = "SELECT nom_categorie FROM categories";
                $catResult = mysqli_query($connection, $catQuery);
                while ($cat = mysqli_fetch_assoc($catResult)) {
                    $selected = ($cat['nom_categorie'] == $row['categorie']) ? "selected" : "";
                    echo "<option value='{$cat['nom_categorie']}' $selected>{$cat['nom_categorie']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group mb-2">
            <label for="prix">Prix</label>
            <input type="number" step="0.01" class="form-control" name="prix" value="<?= $row['prix'] ?>" required>
        </div>

        <div class="form-group mb-2">
            <label for="quantite">Quantité</label>
            <input type="number" class="form-control" name="quantite" value="<?= $row['quantite'] ?>" required>
        </div>

        <div class="form-group mb-2">
            <label for="image">Changer l'image (optionnel)</label>
            <input type="file" class="form-control" name="image" accept="image/*">
            <img src="../<?= htmlspecialchars($row['chemin_image']) ?>" alt="<?= htmlspecialchars($row['nom_produit']) ?>" style="width: 100px;" class="mt-2">
        </div>

        <div class="form-group mt-3">
            <input type="submit" class="btn btn-success" name="update_product" value="Mettre à jour">
        </div>
    </form>
</div>

<?php include "../include/footer.php"; ?>
