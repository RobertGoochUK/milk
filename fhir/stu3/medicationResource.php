<?php

    include_once("dbconfig.php");
    
    // get the Request parameters
    $id = $_REQUEST['id'];
    $type = $_REQUEST['ot'];

    $result = null;
    $nm = null;
    $form_cd = null;
    $form_desc = null;
    
    $con = mysql_connect($host, $user, $password, true );
    // Check connection
    if (!$con) {
        echo "FAIL";
        die("Connection failed: " . mysql_error());
        exit;
    }
    
    /*
    // open the database connection
    $con = mysqli_connect($host, $user, $password, $dbname);
    if (!$con) {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    */
    
    $db_selected = mysql_select_db($dbname, $con);
    if (!$db_selected) {
        die ('Can\'t use foo : ' . mysql_error());
        exit;
    }
    $sql_query = "SELECT name FROM vtm WHERE vtmid = " . $id . " UNION SELECT name FROM vmp WHERE vmpid = " . $id . ";";
    $result = mysql_query($sql_query);
    if (!$result) {
        die('Invalid query: ' . mysql_error());
        exit;
    }
    while ($row = mysql_fetch_array($result, MYSQL_NUM))
    {
        $nm = $row[0];
    }
    mysql_close($con);
    
    
// get the data from dm+d from the database
$resourceid = '<id value="careconnect-medication-1-' . $id . '"/>';
$text = '<text><div>' . $nm . '</div></text>';
$system = '<system value="http://snomed.info/sct"/>';
$code = '<code><coding><display value="' . $nm . '"/><code value="' . $id . '"/></coding></code>';
$form = '<form><coding><system value="http://snomed.info/sct"/><code value="' . $form_cd . '"/><display value="' . $form_desc . '"/></coding></form>';
//$ingredients = '<ingredient></ingredient>';

// now build up the FHIR resource XML
$s = '<?xml version="1.0"?><Medication>';
$s .= $resourceid;
$s .= '<meta></meta>';
$s .= $text;
$s .= $code;
//$s .= $form;
//$s .= $ingredient;
$s .= '</Medication>';

// return in the requested format
$xml = new SimpleXMLElement($s);
if ( !$xml ) {
    echo "Error creating XML";
    exit;
}

if ($type == "xml") {
    echo $xml->asXML();
} else {
    echo json_encode($xml);
}
?>
