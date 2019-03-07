<?php
    include_once("dbconfig.php");
    
    $name = urldecode($_REQUEST['s']);
    
    $con = mysql_connect($host, $user, $password, true);
    if (!$con) {
        echo "<?xml version='1.0'?><ERROR code='002'>" . mysql_error() . "</ERROR>";
    exit;
    }
    $db_selected = mysql_select_db($dbname, $con);
    if (!$db_selected) {
        echo "<?xml version='1.0'?><ERROR code='003'>" . mysql_error() . "</ERROR>";
        exit;
    }

    $sql_query = "SELECT vtm.vtmid, vtm.name FROM vtm WHERE vtm.name LIKE '" . $name . "%' ORDER BY vtm.name";
    $result = mysql_query($sql_query);
    if (!$result) {
        echo "<?xml version='1.0'?><ERROR code='004'>" . mysql_error() . "</ERROR>";
        exit;
    }

    $s = '<?xml version="1.0"?><VTMS>';
    while ($row = mysql_fetch_array($result, MYSQL_NUM))
    {
        $id = $row[0];
        $name = $row[1];
        $s .= '<VTM><ID>' . $id . '</ID><NM>' . $name . '</NM></VTM>';
    }
    $s .= '</VTMS>';
    mysql_free_result($result);
    mysql_close($con);

    $xml = new SimpleXMLElement($s);
    if ( !$xml ) {
        echo "<?xml version='1.0'?><ERROR>Error creating XML</ERROR>";
        exit;
    }
    echo json_encode($xml);

?>
