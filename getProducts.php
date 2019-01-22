<?php

$id = $_REQUEST['id'];
$type = $_REQUEST['ot'];
$form = $_REQUEST['form'];
$route = $_REQUEST['route'];


    $s = "<?xml version='1.0' encoding='UTF-8'?>";
    $s .= "<conversion>";
    $s .= "<vtm><id>108564000</id><description>Ramipril</description></vtm>";
	$s .= "<products>";

if ( $form == "" ) { // no form
	$s .= "<vmp><id>318902004</id><description>Ramipril 1.25mg capsules</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 2.5mg capsules</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 5mg capsules</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 10mg capsules</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 1.25mg tablets</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 2.5mg tablets</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 5mg tablets</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 10mg tablets</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 750micrograms/5ml oral solution</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 750micrograms/5ml oral suspension</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 1.25mg/5ml oral solution</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 1.25mg/5ml oral suspension</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 2.5mg/5ml oral solution sugar free</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 2.5mg/5ml oral solution sugar free</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 2.5mg/5ml oral solution</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 2.5mg/5ml oral suspension</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 5mg/5ml oral solution</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 5mg/5ml oral suspension</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 10mg/5ml oral solution</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 10mg/5ml oral suspension</description></vmp>";
}

if ( $form == "428673006" ) { // tablet
	$s .= "<vmp><id>318902004</id><description>Ramipril 1.25mg tablets</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 2.5mg tablets</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 5mg tablets</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 10mg tablets</description></vmp>";
}

if ( $form == "428641000" ) { // capsule
	$s .= "<vmp><id>318902004</id><description>Ramipril 1.25mg capsules</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 2.5mg capsules</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 5mg capsules</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 10mg capsules</description></vmp>";
}

if ( $form == "385023001" ) { // oral solution
	$s .= "<vmp><id>318902004</id><description>Ramipril 750micrograms/5ml oral solution</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 1.25mg/5ml oral solution</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 2.5mg/5ml oral solution sugar free</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 2.5mg/5ml oral solution sugar free</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 2.5mg/5ml oral solution</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 5mg/5ml oral solution</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 10mg/5ml oral solution</description></vmp>";
}

if ( $form == "385024007" ) { // oral suspension
	$s .= "<vmp><id>318902004</id><description>Ramipril 750micrograms/5ml oral suspension</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 1.25mg/5ml oral suspension</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 2.5mg/5ml oral suspension</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 5mg/5ml oral suspension</description></vmp>";
	$s .= "<vmp><id>318902004</id><description>Ramipril 10mg/5ml oral suspension</description></vmp>";
}

    $s .= "</products>";
    $s .= "</conversion>";

$xml = new SimpleXMLElement($s);

if ($type == "xml") {
    echo $xml->asXML();
}
else {
	echo json_encode($xml);
}

?>

