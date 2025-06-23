<?php include "dbcon.php"; ?>
<?php include "include/header.php"; ?>

<!-- Affichage d'un message de succès -->
<?php if (isset($_GET['msg'])): ?>
    <section class='container-fluid p-0'>
        <div class='bg-success p-2'>
            <div class='container text-white text-center fw-bold'>
                <h6 class='text-center'><?= htmlspecialchars($_GET['msg']) ?></h6>
            </div>
        </div>
    </section>
<?php endif; ?>

<div class="container">
    <section class="row mt-5 mb-2">
        <h1 class="fw-bold text-center">Shop Your Favorite Products</h1>
    </section>
    
    <section class="row mb-3">
        <div class="d-md-flex justify-content-md-end d-grid gap-2 d-md-block">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add">Add Product</button>
        </div>
    </section>

    <section class="row">
        <?php 
            $query = "SELECT * FROM produits";
            $result = mysqli_query($connection, $query);
            if (!$result) {
                die("Query failed: " . mysqli_error($connection));
            } else {
                while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 p-2">
            <div class="card border-0 p-2 rounded-0 bg-body-secondary py-4" id="product-<?= $row['id_produit'] ?>">
                <div class="d-flex justify-content-center">
                    <img src="<?= htmlspecialchars($row['chemin_image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['nom_produit']) ?>" style="width: 150px;">
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title mb-1"><?= htmlspecialchars($row['nom_produit']) ?></h5>
                    <p class="card-text mb-1 fw-bold">BIF<?= number_format($row['prix'], 2) ?></p>
                    <a href="pages/product.php?id=<?= $row['id_produit'] ?>" class="btn btn-info w-100 mb-2">Details</a>
                    <div class="d-flex justify-content-evenly">
                        <a href="pages/edit_product.php?id=<?= $row['id_produit'] ?>" class="btn btn-warning w-100 mx-1">Edit</a>
                        <a href="pages/delete_product.php?id=<?= $row['id_produit'] ?>" class="btn btn-danger w-100 mx-1">Delete</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
                }
            }
        ?>
    </section>

    <!-- TABLEAU -->
    <section class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th><th>Product Name</th><th>Price</th><th>Quantity</th>
                    <th>Product Image</th><th>Info</th><th>Edit</th><th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    mysqli_data_seek($result, 0); // reset result pointer
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <th><?= $row['id_produit'] ?></th>
                    <td><?= htmlspecialchars($row['nom_produit']) ?></td>
                    <td>$<?= number_format($row['prix'], 2) ?></td>
                    <td><?= $row['quantite'] ?></td>
                    <td><img src="<?= htmlspecialchars($row['chemin_image']) ?>" style="width: 50px;"></td>
                    <td><a href="pages/product.php?id=<?= $row['id_produit'] ?>" class="btn btn-info">More Info</a></td>
                    <td><a href="pages/edit_product.php?id=<?= $row['id_produit'] ?>" class="btn btn-warning">Edit</a></td>
                    <td><a href="pages/delete_product.php?id=<?= $row['id_produit'] ?>" class="btn btn-danger">Delete</a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>

    <!-- MODAL AJOUT PRODUIT -->
    <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="addLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="pages/create_product.php" method="POST" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addLabel">Add New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="pname">Nom du Produit</label>
                            <input type="text" class="form-control" id="pname" name="pname" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="marque">Marque du Produit</label>
                            <input type="text" class="form-control" id="marque" name="marque" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="categorie">Catégorie</label>
                            <select name="id_categorie" class="form-control" required>
                                <option value="">-- Sélectionner une catégorie --</option>
                                <?php
                                $query = "SELECT id_categorie, nom_categorie FROM categories";
                                $result = mysqli_query($connection, $query);
                                while ($cat = mysqli_fetch_assoc($result)) {
                                    echo "<option value='{$cat['id_categorie']}'>{$cat['nom_categorie']}</option>";
                                }
                                ?>
                            </select>

                            <small>Catégorie non trouvée ? <a href="pages/create_category.php">Créer une catégorie</a></small>
                        </div>
                        <div class="form-group mb-2">
                            <label for="prix">Prix</label>
                            <input type="number" step="0.01" class="form-control" id="prix" name="prix" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="quantite">Quantité</label>
                            <input type="number" class="form-control" id="quantite" name="quantite" value="1" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <input type="submit" class="btn btn-success" name="create_product" value="Ajouter">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "include/footer.php"; ?>
