<?php 
$GLOBAL_URL = $_SERVER['REQUEST_URI'];
?>
<!-- Testing for global url pathname for active link class -->
<?php //echo $GLOBAL_URL ?>
<?php //print ($_SERVER['REQUEST_URI'] == $GLOBAL_URL) ? 'active' : '' ?>

<div class="sticky-top">
  <?php include 'top-header.php' ?>
  <nav class="navbar navbar-expand-lg bg-body-tertiary py-4">
    <div class="container">
      <a class="navbar-brand fw-bold" href="./">
        <img src="logo.png" width="200px" alt="Product Upload logo" />
      </a>
      <button class="navbar-toggler border border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
          <li class="nav-item">
            <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] == $GLOBAL_URL) ? 'active' : '' ?>" aria-current="page" href="../">Homepage</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="./">Produits</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="./">Ajouter le produit</a>
          </li>

        </ul>
      </div>
    </div>
  </nav>
</div>