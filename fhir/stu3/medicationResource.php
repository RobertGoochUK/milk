<?php

    // get the Request parameters
    $id = $_REQUEST['id'];
    $type = $_REQUEST['ot'];

    $result = null;
    $nm = null;
    $form_cd = null;
    $form_desc = null;
    
    // open the database connection
    $dom = new DOMDocument;
    if ( $dom->load("f_vmp2_3011118.xml") ) {
        // valid
        $x = new DOMXPath($dom);
        $result = $x->evaluate("//text()[. = '" . $id . "']/parent::*");
    } else {
        // invalid
        echo "Error loading dm+d XML";
    }
    
    // loop thru the XPath result nodes containing the requested concept id
    foreach ($result as $item) {
        // get the node parent
        $parent = $item->parentNode;
        // if the VMP general info
        if ( $parent->nodeName == "VMP" ) {
            $nodes = $parent->childNodes;
            foreach ($nodes as $childNode) {
                if ( $childNode->nodeName == "NM" ) {
                    $nm = $childNode->nodeValue;
                }
            }
        }
        // if the VMP Form info
        if ( $parent->nodeName == "DFORM" ) {
            $nodes = $parent->childNodes;
            foreach ($nodes as $childNode) {
                if ( $childNode->nodeName == "FORMCD" ) {
                    $form_cd = $childNode->nodeValue;
                    $form_desc = "Capsule"; // need to write a function for the lookup xml file
                }
            }
        }
    }
    
// get the data from dm+d from the database
$resourceid = '<id value="careconnect-medication-1-' . $id . '/>';
$text = '<text><div>' . $nm . '</div></text>';
$system = '<system value="http://snomed.info/sct"/>';
$code = '<code><coding><display value="' . $nm . '"/><code value="' . $id . '"/></coding></code>';
$form = '<form><coding><system value="http://snomed.info/sct"/><code value="' . $form_cd . '"/><display value="' . $form_desc . '"/></coding></form>';
//$ingredients = '<ingredient></ingredient>';

// now build up the FHIR resource XML
$s = '<?xml version="1.0" encoding="UTF-8"?><Medication>';
$s .= $resourceid;
$s .= '<meta></meta>';
$s .= $text;
$s .= $code;
$s .= $form;
//$s .= $ingredient;
$s .= '</Medication>';

//echo $s;

// return in the requested format
$xml = new SimpleXMLElement($s);
if ($type == "xml") {
    echo $xml->asXML();
} else {
    echo json_encode($xml);
}
?>
