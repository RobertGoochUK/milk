<?php
// Alpha v1.0.8
    
    function CallOntoServer($id, $name) {
        // here we go !

        $s = "<?xml version='1.0' encoding='UTF-8'?>";
        $s .= "<conversion>";
        $s .= "<vtm><id>" . $id . "</id><description>" . $name . "</description></vtm>";
        $s .= "<products>";
        $s .= "<vmp><id>318900007</id><description>Ramipril 1.25mg capsules</description></vmp>";
        $s .= "<vmp><id>318901006</id><description>Ramipril 2.5mg capsules</description></vmp>";
        $s .= "<vmp><id>318902004</id><description>Ramipril 5mg capsules</description></vmp>";
        $s .= "<vmp><id>13013411000001105</id><description>Ramipril 750micrograms/5ml oral suspension</description></vmp>";
        $s .= "<vmp><id>8720511000001107</id><description>Ramipril 10mg/5ml oral suspension</description></vmp>";
        $s .= "</products>";
        $s .= "</conversion>";
        return $s;
    }
    
    // get the request parameters
    $xml = urldecode($_REQUEST['d']);
    // some real voodoo here with the ajax style of calling this function encoded " as \", which is really annoying
    $xml = str_replace('\"','"',$xml);
    $type = $_REQUEST['ot'];
    $sortOrder = $_REQUEST['so'];
    
    //$id = $_REQUEST['id'];
    //$form = $_REQUEST['form'];
    //$route = $_REQUEST['route'];

    // create the XML DOM object
    $dom = new DOMDocument;
    $dom->loadXML($xml);

    // go find the medication data
    $med_id = "X";
    $med_name = "Y";
    
    // got find the medication data
    $nodes = $dom->getElementsByTagName('medication');
    if ( $nodes->length == 0 ) {
        $nodes = $dom->getElementsByTagName('Medication');
        if ( $nodes->length == 0 ) {
            $nodes = $dom->getElementsByTagName('medicationCodeableConcept');
        }
    }
    // now go find the medication name and id
    if ( $nodes->length > 0 ) {
        $node = $nodes->item(0)->getElementsByTagName('coding');
        if ( $node->length > 0 ) {
            $node2 = $node->item(0)->getElementsByTagName('display');
            if ( $node2->length > 0 ) {
                $med_name = $node2->item(0)->getAttribute('value');
            }
            $node2 = $node->item(0)->getElementsByTagName('code');
            if ( $node2->length > 0 ) {
                $med_id = $node2->item(0)->getAttribute('value');
            }
        }
    }

    // do the hard bit and return products in XML
    $s = CallOntoServer($med_id, $med_name);

    $xml = new SimpleXMLElement($s);
    // now format in JSON if requested
    if ($type == "xml") {
        echo $xml->asXML();
    }
    else {
        echo json_encode($xml);
    }

?>

