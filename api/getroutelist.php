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

    $sql_query = "SELECT vmproute.routeid, lookup.name, count(*) AS c FROM vtm INNER JOIN vmp ON vmp.vtmid = vtm.vtmid INNER JOIN vmproute ON vmproute.vmpid = vmp.vmpid INNER JOIN lookup ON lookup.id = vmproute.routeid WHERE vtm.vtmid = " . $vtmid . " GROUP BY lookup.name ORDER BY lookup.name;";
    $result = mysql_query($sql_query);
    if (!$result) {
        echo "<?xml version='1.0'?><ERROR code='004'>" . mysql_error() . "</ERROR>";
        exit;
    }

    $s = '<?xml version="1.0"?><ROUTES>';
    while ($row = mysql_fetch_array($result, MYSQL_NUM))
    {
        $id = $row[0];
        $name = $row[1];
        $s .= '<ROUTE><ID>' . $id . '</ID><NM>' . $name . '</NM></ROUTE>';
    }
    $s .= '</ROUTES>';
    mysql_free_result($result);
    mysql_close($con);

    $xml = new SimpleXMLElement($s);
    if ( !$xml ) {
        $xml = '<?xml version="1.0"?><ROUTES></ROUTES>';
    }
    echo json_encode($xml);

?>
