<?php include "../dbcon.php" ?>

<?php

if(isset($_GET['id'])){
    $id = $_GET['id'];
}

    $query = "delete from `produits` where `id_produit` = '$id'";

    $result = mysqli_query($connection, $query);

    if(!$result){
        die("Query Failed".mysqli_error($connection));
    }else{
        header("location:../index.php?msg=The product has been deleted to the database.");
    }

?>