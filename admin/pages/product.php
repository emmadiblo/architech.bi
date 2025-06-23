<?php include "../dbcon.php"; ?>
<?php include "../include/header.php"; ?>

<section class="container mt-5 mb-5">

<?php 
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $query = "SELECT * FROM produits WHERE id_produit = $id";
    $result = mysqli_query($connection, $query);

    if (!$result || mysqli_num_rows($result) === 0) {
        echo "<div class='alert alert-danger'>Produit non trouvé.</div>";
    } else {
        $row = mysqli_fetch_assoc($result);
    }
} else {
    echo "<div class='alert alert-danger'>ID de produit invalide.</div>";
    include("../include/footer.php");
    exit;
}
?>

<?php if (!empty($row)): ?>

<div class="row">
    <div class="col-sm-12 col-md-6 col-xl-4 pb-5 text-center text-md-start">
        <img src="../<?php echo htmlspecialchars($row['chemin_image']); ?>" 
             alt="<?php echo htmlspecialchars($row["nom_produit"]); ?>" 
             style="width: 250px;" class="img-fluid rounded" />
    </div>
    <div class="col-sm-12 col-md-6 col-xl-8">
        <h1 class="fw-bold"><?php echo htmlspecialchars($row['nom_produit']); ?></h1>
        <p><strong>Marque:</strong> <?php echo htmlspecialchars($row['marque']); ?></p>
        <p class="fw-semibold fs-2 text-success">BIF <?php echo number_format($row['prix'], 2); ?></p>
        <p><strong>Quantité en stock:</strong> <?php echo (int)$row['quantite']; ?></p>
    </div>
</div>


<!-- Suggestions -->
<div class="row mt-5">
    <div class="col-12">
        <h4 class="fw-bold mb-3">Les clients achètent souvent après avoir vu ce produit :</h4>
    </div>

    <?php 
    $suggestQuery = "SELECT * FROM produits WHERE id_produit != $id ORDER BY RAND() LIMIT 6";
    $suggestResult = mysqli_query($connection, $suggestQuery);

    if ($suggestResult && mysqli_num_rows($suggestResult) > 0) {
        while ($suggest = mysqli_fetch_assoc($suggestResult)) {
            ?>
            <div class="col-sm-6 col-md-4 col-lg-2 mb-4">
                <div class="bg-light text-center py-3 px-2 border rounded">
                    <img src="../<?php echo htmlspecialchars($suggest['chemin_image']); ?>" 
                         alt="<?php echo htmlspecialchars($suggest['nom_produit']); ?>" 
                         style="width: 120px;" class="img-fluid mb-2">
                    <h6 class="mb-1">
                        <a href="product.php?id=<?php echo $suggest['id_produit']; ?>" 
                           class="text-decoration-none text-dark">
                            <?php echo htmlspecialchars($suggest['nom_produit']); ?>
                        </a>
                    </h6>
                    <p class="text-muted small">BIF <?php echo number_format($suggest['prix'], 2); ?></p>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p class='text-muted'>Aucune autre suggestion pour le moment.</p>";
    }
    ?>
</div>

<?php endif; ?>

</section>

<?php include("../include/footer.php"); ?>
