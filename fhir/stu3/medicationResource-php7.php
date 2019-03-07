<?php

    include_once("../../api/dbconfig.php");
    
    // get the Request parameters
    $id = $_REQUEST['id'];
    $type = $_REQUEST['ot'];

    $result = null;
    $nm = null;
    $form_cd = null;
    $form_desc = null;
    
    // open the mySQL database connection
    if (function_exists('mysqli_connect')) {
        $con = mysqli_connect($host, $user, $password, $dbname);
        if (!$con) {
            echo "<?xml version='1.0'?><ERROR>" . mysqli_connect_errno() . mysqli_connect_error() . "</ERROR>";
            exit;
        }
    } else {
        $con = mysql_connect($host, $user, $password, true);
        if (!$con) {
            echo "<?xml version='1.0'?><ERROR>" . mysql_error() . "</ERROR>";
            exit;
        }
        $db_selected = mysql_select_db($dbname, $con);
        if (!$db_selected) {
            echo "<?xml version='1.0'?><ERROR>" . mysql_error() . "</ERROR>";
            exit;
        }
    }

    if (function_exists('mysqli_query')) {
        $sql_query = "CALL sp_getConcept(" . $id . ");";
        $result = mysqli_query($con, $sql_query);
        if (!$result) {
            echo "<?xml version='1.0'?><ERROR>" . mysqli_connect_errno() . mysqli_connect_error() . "</ERROR>";
            exit;
        }
    } else {
        $sql_query = "SELECT vtm.name AS name, null AS formid, null AS formdesc FROM vtm WHERE vtmid = " . $id . " UNION SELECT vmp.name AS name, vmpform.formid AS formid, lookup.name AS formdesc FROM vmp INNER JOIN vmpform ON vmp.vmpid = vmpform.vmpid INNER JOIN lookup ON vmpform.formid = lookup.id WHERE vmp.vmpid = " . $id . " UNION SELECT amp.name AS name, vmpform.formid AS formid, lookup.name AS formdesc FROM amp INNER JOIN vmpform ON amp.vmpid = vmpform.vmpid INNER JOIN lookup ON vmpform.formid = lookup.id WHERE ampid = " . $id .";";
        $result = mysql_query($sql_query);
        if (!$result) {
            echo "<?xml version='1.0'?><ERROR>" . mysql_error() . "</ERROR>";
            exit;
        }
    }

    if (function_exists('mysqli_fetch_array')) {
        while ($row = mysqli_fetch_array($result, MYSQL_NUM))
        {
            $nm = $row[0];
            $form_cd = $row[1];
            $form_desc = $row[2];
        }
    } else {
        while ($row = mysql_fetch_array($result, MYSQL_NUM))
        {
            $nm = $row[0];
            $form_cd = $row[1];
            $form_desc = $row[2];
        }
    }

    if (function_exists('mysqli_close')) {
        mysqli_free_result($result);
        mysqli_close($con);
    } else {
        mysql_free_result($result);
        mysql_close($con);
    }
    
    // get the data from dm+d from the database
    $resourceid = '<id value="careconnect-medication-1-' . $id . '"/>';
    $text = '<text><div>' . $nm . '</div></text>';
    $system = '<system value="http://snomed.info/sct"/>';
    $code = '<code><coding><display value="' . $nm . '"/><code value="' . $id . '"/></coding></code>';
    if ( $form_cd > "" ) {
        $form = '<form><coding><system value="http://snomed.info/sct"/><code value="' . $form_cd . '"/><display value="' . $form_desc . '"/></coding></form>';
    }
    //$ingredients = '<ingredient></ingredient>';

    // now build up the FHIR resource XML
    $s = '<?xml version="1.0"?><Medication>';
    $s .= $resourceid;
    //$s .= '<meta></meta>';
    $s .= $text;
    $s .= $code;
    $s .= $form;
    //$s .= $ingredient;
    $s .= '</Medication>';

    // return in the requested format
    $xml = new SimpleXMLElement($s);
    if ( !$xml ) {
        echo "<?xml version='1.0'?><ERROR>Error creating XML</ERROR>";
        exit;
    }

    if ($type == "json") {
        echo json_encode($xml);
    } else {
        echo $xml->asXML();
    }
?>
