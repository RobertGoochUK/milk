<?php
if (function_exists('mysqli_connect')) {
    header("Location:api/getProducts-php7.php?" . $_SERVER['QUERY_STRING']);
    exit;
} else {
    header("Location:api/getProducts-php5.php?" . $_SERVER['QUERY_STRING']);
    exit;
}
?>

