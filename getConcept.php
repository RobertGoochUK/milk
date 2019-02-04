<?php

$id = $_REQUEST['id'];
$format = $_REQUEST['of'];
$type = $_REQUEST['ot'];


if ($format == "complete") {
    $s = "<?xml version='1.0' encoding='UTF-8'?>";
    $s .= "<medication>";
	if ($id == "90332006") {
		$s .= "<id>90332006</id><description>Paracetamol</description><conceptClass>VTM</conceptClass>";
	}
	if ($id == "318902004") {
		$s .= "<id>318902004</id><description>Ramipril 5mg capsules</description><conceptClass>VMP</conceptClass>";
		$s .= "<PrescribingStatus><code>0001</code><value>Valid as a prescribable product</value></PrescribingStatus>";
		$s .= "<cdCategory><code>0000</code><value>No Controlled Drug Status</value></cdCategory>";
		$s .= "<ontFormRoute><code>0001</code><value>tablet.oral</value></ontFormRoute>";
		$s .= "<dfIndicator><code>1</code><value>Discrete</value></dfIndicator>";
	}
	if ($id == "2887311000001104") {
		$s .= "<id>2887311000001104</id><description>Adizem-SR 120mg capsules (Napp Pharmaceuticals Ltd)</description><conceptClass>AMP</conceptClass>";
		$s .= "<manufacturer><code>3592811000001102</code><value>Napp Pharmaceuticals Ltd</value></manufacturer>";
		$s .= "<licensedRoute><code>26643006</code><value>Oral</value></licensedRoute>";
		$s .= "<availabilityRestrictionCode><code>0001</code><value>None</value></availabilityRestrictionCode>";
		$s .= "<licensingAuthority><code>0001</code><value>Medicines - MHRA/EMA</value></licensingAuthority>";
	}
    $s .= "</medication>";
}
elseif ($format == "summary") {
	
	if ($id == "90332006") {
		$s = "<?xml version='1.0' encoding='UTF-8'?><medication><id>90332006</id><description>Paracetamol</description><conceptClass>VTM</conceptClass></medication>";
	}
	if ($id == "318902004") {
		$s = "<?xml version='1.0' encoding='UTF-8'?><medication><id>318902004</id><description>Ramipril 5mg capsules</description><conceptClass>VMP</conceptClass></medication>";
	}
	if ($id == "2887311000001104") {
		$s = "<?xml version='1.0' encoding='UTF-8'?><medication><id>2887311000001104</id><description>Adizem-SR 120mg capsules (Napp Pharmaceuticals Ltd)</description><conceptClass>AMP</conceptClass></medication>";
	}
}
elseif ($format == "careconnect-medication-1") {
	
	if ($id == "90332006") {
		$s = "<?xml version='1.0' encoding='UTF-8'?><medication><id value='careconnect-medication-1-90332006'/><meta></meta><text><div>Paracetamol</div></text><code><coding><system value='http://snomed.info/sct'/><code value='90332006'/><display value='Paracetamol'/></coding></code><form></form><ingredients></ingredients></medication>";
	}
	if ($id == "318902004") {
		$s = "<?xml version='1.0' encoding='UTF-8'?><medication><id value='careconnect-medication-1-318902004'/><meta></meta><text><div>Ramipril 5mg capsules</div></text><code><coding><system value='http://snomed.info/sct'/><code value='318902004'/><display value='Ramipril 5mg capsules'/></coding></code><form></form><ingredients></ingredients></medication>";
	}
	if ($id == "2887311000001104") {
		$s = "<?xml version='1.0' encoding='UTF-8'?><medication><id value='careconnect-medication-1-2887311000001104'/><meta></meta><text><div>Adizem-SR 120mg capsules (Napp Pharmaceuticals Ltd)</div></text><code><coding><system value='http://snomed.info/sct'/><code value='2887311000001104'/><display value='Adizem-SR 120mg capsules (Napp Pharmaceuticals Ltd)'/></coding></code><form></form><ingredients></ingredients></medication>";
	}
	
}
else {
	$s = "<?xml version='1.0' encoding='UTF-8'?><root>Invalid call</root>";
}

$xml = new SimpleXMLElement($s);

if ($type == "xml") {
    echo $xml->asXML();
}
else {
	echo json_encode($xml);
}

?>


