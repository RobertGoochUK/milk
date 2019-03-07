<?php

    function formatDecimal($value) {
        //if ( !$places ) {
        //    $places = 6;
        //}
        $tmp = number_format($value,6);
        $tmp = rtrim($tmp, "0");
        $tmp = rtrim($tmp, ".");
        return $tmp;
    }
    
    function removeTrailingZeros($value) {
        $value = rtrim($value, "0");
        $value = rtrim($value, ".");
        return $value;
    }
    
?>
