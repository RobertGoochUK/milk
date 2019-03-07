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

if ( $med_name == "" ) {
    $sql_query = "SELECT name FROM vtm WHERE vtmid = " . $med_id . " LIMIT 1;";
    $result = mysql_query($sql_query);
    if (!$result) {
        echo "<?xml version='1.0'?><ERROR code='004'>" . mysql_error() . "</ERROR>";
        exit;
    }
    $row = mysql_fetch_array($result);
    $med_name = $row[0];
    mysql_free_result($result);
}

// get the SNOMED code for the dose unit of measure
$sql_query = "SELECT id FROM lookup WHERE name = '" . $unit . "' LIMIT 1;";
if (!$result = mysql_query($sql_query)) {
    echo "<?xml version='1.0'?><ERROR code='005'>" . mysql_error() . "</ERROR>";
    exit;
}
if ( $row = mysql_fetch_array($result) ) {
    $doseUOM = $row[0];
}
mysql_free_result($result);

// now the main query
$sql_query = "SELECT DISTINCT vmp.vmpid, vmp.name, calc_rank(" . $dose . ",convert_units(vpi.strnt_nmrtr_val,vpi.strnt_nmrtr_uomcd," . $doseUOM . "),vpi.strnt_dnmtr_val,vmp.udfs) AS qty, vmp.udfs_dose_uomcd, vpi.strnt_dnmtr_uomcd, vmp.pres_statcd, calc_rank3(" . $dose . ",convert_units(vpi.strnt_nmrtr_val,vpi.strnt_nmrtr_uomcd," . $doseUOM . "),vpi.strnt_dnmtr_val,vmp.udfs,vmpform.formid) AS rank FROM vtm INNER JOIN vmp ON vtm.vtmid = vmp.vtmid INNER JOIN vmpform ON vmp.vmpid = vmpform.vmpid INNER JOIN vmproute ON vmp.vmpid = vmproute.vmpid INNER JOIN vpi on vmp.vmpid = vpi.vmpid INNER JOIN lookup on vpi.strnt_nmrtr_uomcd = lookup.id WHERE vtm.vtmid = " . $med_id . " AND ( vmpform.formid = " . $form_id . " OR IsNull(" . $form_id . ") ) AND ( vmproute.routeid = " . $route_id . " OR IsNull(" . $route_id . ") ) ORDER BY rank, qty, vmp.name;";
if (!$result = mysql_query($sql_query)) {
    echo "<?xml version='1.0'?><ERROR code='007'>" . mysql_error() . "</ERROR>";
    exit;
}

// now create the initial XML response
$s = createInitialXML($med_id, $med_name, $form_id, $route_id, $dose, $doseUOM);

$row = mysql_fetch_array($result);
$pres_stat = "null";

while ($row) {
    $vmp_id = $row[0];
    $vmp_nm = $row[1];
    $qty = $row[2];
    if ( $qty != "" ) {
        $qty = formatDecimal($qty);
    }
    $pres_stat = $row[5];
    $rank = removeTrailingZeros($row[6]);
    
    // now work out the unit of measure for the VMP
    $uom = "";
    
    // get the unit of measure from the UDFS, if exists, otherwise the VPI UOM
    $udfs_dose_uomcd = $row[3];
    if ( $udfs_dose_uomcd == 0 ) {
        $udfs_dose_uomcd = $row[4];
    }
    
    $sql_query2 = "SELECT name FROM lookup WHERE id = " . $udfs_dose_uomcd . " LIMIT 1;";
    $result2 = mysql_query($sql_query2);
    if (!$result2) {
        echo "<?xml version='1.0'?><ERROR code='009'>" . $udfs_dose_uomcd . " | " . mysql_error() . "</ERROR>";
        exit;
    }
    $row2 = mysql_fetch_array($result2);
    $uom = $row2[0];
    mysql_free_result($result2);
    
    $s .= "<vmp><id>" . $vmp_id . "</id><description>" . $vmp_nm . "</description><qty>" . $qty . "</qty><uom>" . $uom . "</uom><pres_stat>" . $pres_stat . "</pres_stat><rank>" . $rank . "</rank></vmp>";
    
    // get next row
    $row = mysql_fetch_array($result);
}

// now finish off the XML response
$s = createEndingXML($s);

mysql_free_result($result);
mysql_close($con);

$xml = new SimpleXMLElement($s);
// now format in JSON if requested
if ($type == "xml") {
    echo $xml->asXML();
}
else {
    echo json_encode($xml);
}
?>
