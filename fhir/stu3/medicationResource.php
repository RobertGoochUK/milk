<?php
if (function_exists('mysqli_connect')) {
    header("Location:medicationResource-php7.php?" . $_SERVER['QUERY_STRING']);
    exit;
} else {
    header("Location:medicationResource-php5.php?" . $_SERVER['QUERY_STRING']);
    exit;
}
?>

