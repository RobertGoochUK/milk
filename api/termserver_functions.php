<?php

    function getFromDosageXML($xml)
    {
        $med_id = "null";
        $med_name = "null";
        $form_id = "null";
        $route_id = "null";
        $unit = "null";
        $dose = "null";
        
        // create the XML DOM object
        $dom = new DOMDocument;
        $dom->loadXML($xml);
        
        // go find the medication data
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
        
        // now go find the form (if specified)
        if ( $nodes->length > 0 ) {
            $node = $nodes->item(0)->getElementsByTagName('form');
            if ( $node->length > 0 ) {
                $node2 = $node->item(0)->getElementsByTagName('code');
                if ( $node2->length > 0 ) {
                    $form_id = $node2->item(0)->getAttribute('value');
                }
            }
        }
        
        // now go find the route (if specified)
        $nodes = $dom->getElementsByTagName('route');
        if ( $nodes->length > 0 ) {
            $nodes2 = $nodes->item(0)->getElementsByTagName('code');
            if ( $nodes2->length > 0 ) {
                $route_id = $nodes2->item(0)->getAttribute('value');
            }
        }
        
        // look for doseQuantity
        $nodes = $dom->getElementsByTagName('doseQuantity');
        if ( $nodes->length > 0 ) {
            $nodes2 = $nodes->item(0)->getElementsByTagName('value');
            if ( $nodes2->length > 0 ) {
                $dose = $nodes2->item(0)->getAttribute('value');
            }
            $nodes2 = $nodes->item(0)->getElementsByTagName('unit');
            if ( $nodes2->length > 0 ) {
                $unit = $nodes2->item(0)->getAttribute('value');
            }
        }
        
        // look for doseRange
        if ( $dose == "null" ) {
            $nodes = $dom->getElementsByTagName('doseRange');
            if ( $nodes->length > 0 ) {
                $lows = $nodes->item(0)->getElementsByTagName('low');
                $values = $lows->item(0)->getElementsByTagName('value');
                if ( $values->length > 0 ) {
                    $dose = $values->item(0)->getAttribute('value');
                }
                $values = $lows->item(0)->getElementsByTagName('unit');
                if ( $values->length > 0 ) {
                    $unit = $values->item(0)->getAttribute('value');
                }
            }
        }
        
        return array($med_id, $med_name, $form_id, $route_id, $dose, $unit);
    }
    
    function createInitialXML($med_id, $med_name, $form_id, $route_id, $dose, $doseUOM) {
        $s = "<?xml version='1.0' encoding='UTF-8'?>";
        $s .= "<conversion>";
        $s .= "<source><id>" . $med_id . "</id><description>" . $med_name . "</description>";
        if ( $form_id != "null" ) {
            $s .= "<form>" . $form_id . "</form>";
        }
        if ( $route_id != "null" ) {
            $s .= "<route>" . $route_id . "</route>";
        }
        if ( $dose != "null" ) {
            $s .= "<doseStrength>" . $dose . "</doseStrength>";
            $s .= "<doseStrengthUOM>" . $doseUOM . "</doseStrengthUOM>";
        }
        $s .= "</source><products>";
        return $s;
    }

    function createEndingXML($s){
        return $s . "</products></conversion>";
    }

?>
