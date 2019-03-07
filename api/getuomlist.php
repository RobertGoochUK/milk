<?php
    include_once("dbconfig.php");
    
    $vtmid = urldecode($_REQUEST['id']);
    
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

    $sql_query = "SELECT vpi.strnt_nmrtr_uomcd, lookup.name, count(*) AS c FROM vtm INNER JOIN vmp ON vmp.vtmid = vtm.vtmid INNER JOIN vpi ON vpi.vmpid = vmp.vmpid INNER JOIN lookup ON lookup.id = vpi.strnt_nmrtr_uomcd WHERE vtm.vtmid = " . $vtmid . " AND lookup.valueset = 'UOM' GROUP BY lookup.name ORDER BY c DESC, lookup.name;";
    $result = mysql_query($sql_query);
    if (!$result) {
        echo "<?xml version='1.0'?><ERROR code='004'>" . mysql_error() . "</ERROR>";
        exit;
    }

    $s = '<?xml version="1.0"?><UOMS>';
    while ($row = mysql_fetch_array($result, MYSQL_NUM))
    {
        $id = $row[0];
        $name = $row[1];
        $s .= '<UOM><ID>' . $id . '</ID><NM>' . $name . '</NM></UOM>';
    }
    $s .= '</UOMS>';

    mysql_free_result($result);
    mysql_close($con);

    $xml = new SimpleXMLElement($s);
    if ( !$xml ) {
        $xml = '<?xml version="1.0"?><UOMS></UOMS>';
    }
    echo json_encode($xml);
?>

