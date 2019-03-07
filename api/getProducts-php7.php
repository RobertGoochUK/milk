<?php
// Alpha v1.0.12

include_once("dbconfig.php");
include_once("common_functions.php");
include_once("termserver_functions.php");
    
    // get the request parameters
    $type = $_REQUEST['ot'];
    
    $xml = urldecode($_REQUEST['d']);
    // some real voodoo here with the ajax style of calling this function encoded " as \", which is really annoying
    $xml = str_replace('\"','"',$xml);
    
    $med_id = ($_REQUEST['code'] ? $_REQUEST['code'] : "null" );
    $doseUOM = ($_REQUEST['uom'] ? $_REQUEST['uom'] : "null" );
    $dose = ($_REQUEST['s'] ? $_REQUEST['s'] : "null" );
    $form_id = ($_REQUEST['form'] ? $_REQUEST['form'] : "null" );
    $route_id = ($_REQUEST['route'] ? $_REQUEST['route'] : "null" );
    $med_name = "";
    
    if ( $xml != "" ) {
        list($med_id, $med_name, $form_id, $route_id, $dose, $unit) = getFromDosageXML($xml);
    }
    
$con = mysqli_connect($host, $user, $password, $dbname);
if (!$con) {
    echo "<?xml version='1.0'?><ERROR code='001'>" . mysqli_connect_errno() . mysqli_connect_error() . "</ERROR>";
    exit;
}

if ( $med_name == "" ) {
    $sql_query = "SELECT name FROM vtm WHERE vtmid = " . $med_id . " LIMIT 1;";
    $result = mysqli_query($con, $sql_query);
    if (!$result) {
        echo "<?xml version='1.0'?><ERROR code='004'>" . mysql_error() . "</ERROR>";
        exit;
    }
    $row = mysql_fetch_array($result);
    $med_name = $row[0];
    mysql_free_result($result);
}

// get the SNOMED code for the dose unit of measure
$doseUOM = "null";
$sql_query = "CALL sp_getLookupID('" . $unit . "');";
$result = mysqli_query($con, $sql_query);
if (!$result) {
    echo "<?xml version='1.0'?><ERROR code='004'>" . mysqli_error($con) . "</ERROR>";
    exit;
}
$data = mysqli_fetch_all($result);
foreach ($data AS $row) {
    $doseUOM = $row[0];
}
mysqli_free_result($result);
mysqli_next_result($con);

// now the main query
$sql_query = "CALL sp_VTMtoVMP2(" . $med_id . "," . $dose . "," . $doseUOM . "," . $form_id . "," . $route_id . ");";
if (!$result = mysqli_query($con, $sql_query)) {
    echo "<?xml version='1.0'?><ERROR code='006'>" . mysqli_error($con) . "</ERROR>";
    exit;
}
$data = mysqli_fetch_all($result);
mysqli_free_result($result);
mysqli_next_result($con);
//$row = mysqli_fetch_array($result);

// now create the initial XML response
$s = createInitialXML($med_id, $med_name, $form_id, $route_id, $dose, $doseUOM);

$pres_stat = "null";

foreach ($data AS $row) {
    $vmp_id = $row[0];
    $vmp_nm = $row[1];
    $qty = formatDecimal($row[2]);
    $pres_stat = $row[5];
    $rank = removeTrailingZeros($row[6]);
    
    // now work out the unit of measure for the VMP
    $uom = "";
    
    // get the unit of measure from the UDFS, if exists, otherwise the VPI UOM
    $udfs_dose_uomcd = $row[3];
    if ( $udfs_dose_uomcd == 0 ) {
        $udfs_dose_uomcd = $row[4];
    }
    
    $sql_query2 = "CALL sp_getLookupName(" . $udfs_dose_uomcd . ");";
    $result2 = mysqli_query($con, $sql_query2);
    if (!$result2) {
        echo "<?xml version='1.0'?><ERROR code='008'>" . mysqli_error($con) . "</ERROR>";
        exit;
    }
    $row2 = mysqli_fetch_array($result2);
    $uom = $row2[0];
    mysqli_free_result($result2);
    mysqli_next_result($con);
    
    $s .= "<vmp><id>" . $vmp_id . "</id><description>" . $vmp_nm . "</description><qty>" . $qty . "</qty><uom>" . $uom . "</uom><pres_stat>" . $pres_stat . "</pres_stat><rank>" . $rank . "</rank></vmp>";
}

// now finish off the XML response
$s = createEndingXML($s);

mysqli_free_result($result);
mysqli_next_result($con);
mysqli_close($con);

$xml = new SimpleXMLElement($s);
// now format in JSON if requested
if ($type == "xml") {
    echo $xml->asXML();
}
else {
    echo json_encode($xml);
}

?>
