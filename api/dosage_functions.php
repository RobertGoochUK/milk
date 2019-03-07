<?php
// Alpha v1.0.12
    
    define("SEPARATOR", " - ");

    function formatHalfQuarter($value, $units) {
        // which units of measure should this apply to?
        // tablet, capsule, etc.
        // is it only SNOMED units? and not ucum units?
        //if ( $system ==  "http://snomed.info/sct" ) {
        //alert($units);
        if ( $units ==  "tablet" ) {
            if ( $value == "0.5" ) return "half a";
            if ( $value == "0.25" ) return "quarter of a";
        }
        return $value;
    }
        
    function valuesetUnitsOfTime($value)
    {
        if ( $value == "s" ) { return "second"; }
        if ( $value == "min" ) { return "minute"; }
        if ( $value == "h" ) { return "hour"; }
        if ( $value == "d" ) { return "day"; }
        if ( $value == "wk" ) { return "week"; }
        if ( $value == "mo" ) { return "month"; }
        if ( $value == "a" ) { return "year"; }
        return $value;
    }

    function valuesetDaysOfWeek($value)
    {
        if ( $value == "mon" ) { return "Monday"; }
        if ( $value == "tue" ) { return "Tuesday"; }
        if ( $value == "wed" ) { return "Wednesday"; }
        if ( $value == "thu" ) { return "Thursday"; }
        if ( $value == "fri" ) { return "Friday"; }
        if ( $value == "sat" ) { return "Saturday"; }
        if ( $value == "sun" ) { return "Sunday"; }
        return $value;
    }

    function valuesetEventTiming($value)
    {
        if ( $value == "MORN" ) { return "during the morning"; }
        if ( $value == "AFT" ) { return "during the afternoon"; }
        if ( $value == "EVE" ) { return "during the evening"; }
        if ( $value == "NIGHT" ) { return "during the night"; }
        if ( $value == "PHS" ) { return "after sleep"; }
        if ( $value == "HS" ) { return "before sleep"; }
        if ( $value == "WAKE" ) { return "after waking"; }
        if ( $value == "C" ) { return "at a meal"; }
        if ( $value == "CM" ) { return "at breakfast"; }
        if ( $value == "CD" ) { return "at lunch"; }
        if ( $value == "CV" ) { return "at dinner"; }
        if ( $value == "AC" ) { return "before a meal"; }
        if ( $value == "ACM" ) { return "before beakfast"; }
        if ( $value == "ACD" ) { return "before lunch"; }
        if ( $value == "ACV" ) { return "before dinner"; }
        if ( $value == "PC" ) { return "after a meal"; }
        if ( $value == "PCM" ) { return "after breakfast"; }
        if ( $value == "PCD" ) { return "after lunch"; }
        if ( $value == "PCV" ) { return "after dinner"; }
        return $value;
    }

    function valueDaysHoursMinutes($m)
    {
        $ret = "";
        if ( $m % 1440 == 0 )
        {
            $value = (int) $m / 1440;
            if ( $value == 1 ) {
                $ret = $value . " day ";
            }
            else {
                $ret = $value . " days ";
            }
        }
        elseif ( $m % 60 == 0 )
        {
            $value = (int) $m / 60;
            if ( $value == 1 ) {
                $ret = $value . " hour ";
            }
            else {
                $ret = $value . " hours ";
            }
        }
        else
        {
            $value = $m;
            if ( $m == 1 ) {
                $ret = $value . " minute ";
            }
            else {
                $ret = $value . " minutes ";
            }
        }
        return $ret;
    }

function generateDosageMethod($dom) {
	$s = "";
	$methods = $dom->getElementsByTagName('method');
	if ( $methods->length > 0 ) {
		$displays = $methods->item(0)->getElementsByTagName('display');
		$s .= $displays->item(0)->getAttribute('value');
		$s .= " ";
	}
	return $s;
}
    
function generateDosageDoseQuantity($dom) {
	$s = "";
    $unit = "";
	$doseQuantities = $dom->getElementsByTagName('doseQuantity');
	if ( $doseQuantities->length > 0 ) {
		$values	= $doseQuantities->item(0)->getElementsByTagName('value');
		$value = $values->item(0)->getAttribute('value');
        
        //$values = $doseQuantities->item(0)->getElementsByTagName('system');
        //$system = $values->item(0)->getAttribute('value');
        
        $units = $doseQuantities->item(0)->getElementsByTagName('unit');
        if ( $units->length > 0 ) {
            $unit = $units->item(0)->getAttribute('value');
        }
        
        // handle half & quarter quantities of SNOMED units
        $s .= formatHalfQuarter(formatDecimal($value),$unit);
        
        if ( $unit > "" ) {
            $s .= " " . $unit;
        }
	}
	return $s;
}

function generateDosageDoseRange($dom) {
	$s = "";
	$doseRanges = $dom->getElementsByTagName('doseRange');
	if ( $doseRanges->length > 0 ) {	

		$lows = $doseRanges->item(0)->getElementsByTagName('low');
		$values = $lows->item(0)->getElementsByTagName("value");
		$value = $values->item(0)->getAttribute('value');
		$s .= formatDecimal($value);
		$s .= " to ";
		$highs = $doseRanges->item(0)->getElementsByTagName('high');
		$values = $highs->item(0)->getElementsByTagName("value");
		$value = $values->item(0)->getAttribute('value');
		$s .= formatDecimal($value);
		$units = $highs->item(0)->getElementsByTagName("unit");
		$s .= " ";
		$s .= $units->item(0)->getAttribute('value');
		$s .= " ";
	}
	return $s;
}

function generateDosageRateRatio($dom) {
	$s = "";
	$rateRatios = $dom->getElementsByTagName('rateRatio');
	if ( $rateRatios->length > 0 ) {	
		$s = "at a rate of ";	
		$numerators = $rateRatios->item(0)->getElementsByTagName('numerator');
		$values = $numerators->item(0)->getElementsByTagName("value");
		$value = $values->item(0)->getAttribute('value');
		$s .= formatDecimal($value);
		$s .= " ";
		$units = $numerators->item(0)->getElementsByTagName("code");
		$s .= $units->item(0)->getAttribute('value');
		
		$denominators = $rateRatios->item(0)->getElementsByTagName('denominator');
		$units = $denominators->item(0)->getElementsByTagName("unit");
		$unit = $units->item(0)->getAttribute('value');
		$values = $denominators->item(0)->getElementsByTagName("value");
		$value = $values->item(0)->getAttribute('value');
		$value = formatDecimal($value);
		if ( $value == "1" ) {
			$s .= " per " . $unit;
		} else {
			$s .= " every " . $value . " " . $unit . "s";
		}
		$s .= " ";
	}
	return $s;
}

function generateDosageRateRange($dom) {
	$s = "";
	$rateRanges = $dom->getElementsByTagName('rateRange');
	if ( $rateRanges->length > 0 ) {
		$s = "at a rate of ";
		$lows = $rateRanges->item(0)->getElementsByTagName('low');
		$values = $lows->item(0)->getElementsByTagName("value");
		$value = $values->item(0)->getAttribute('value');
		$s .= formatDecimal($value);
		
		$s .= " to ";
		$highs = $rateRanges->item(0)->getElementsByTagName('high');
		$values = $highs->item(0)->getElementsByTagName("value");
		$value = $values->item(0)->getAttribute('value');
		$s .= formatDecimal($value);
		
		$units = $highs->item(0)->getElementsByTagName("code");
		$s .= " ";
		$s .= $units->item(0)->getAttribute('value');
		$s .= " ";
	}
	return $s;
}

function generateDosageRateQuantity($dom) {
	$s = "";
	$rateQuantities = $dom->getElementsByTagName('rateQuantity');
	if ( $rateQuantities->length > 0 ) {
		$s = "at a rate of ";
		$values = $rateQuantities->item(0)->getElementsByTagName('value');
		$value = $values->item(0)->getAttribute('value');
		$s .= formatDecimal($value);
		
		$s .= " ";
		$values = $rateQuantities->item(0)->getElementsByTagName('code');
		$s .= $values->item(0)->getAttribute('value');	
		$s .= " ";
	}
	return $s;
}

function generateDosageDuration($dom) {
	$s = "";
	$values = $dom->getElementsByTagName('duration');	
	if ( $values->length > 0 ) {
		$duration = $values->item(0)->getAttribute('value');
		
		$values = $dom->getElementsByTagName('durationUnit');	
		if ( $values->length > 0 ) {
			$unit = $values->item(0)->getAttribute('value');
			$unit = valuesetUnitsOfTime($unit);
		}
		
		$values = $dom->getElementsByTagName('durationMax');
		if ( $values->length > 0 ) {
			$max = $values->item(0)->getAttribute('value');
		}
		
		$s .= "over " . $duration . " " . $unit;
		if ( $duration > "1" ) {
			$s .= "s";
		}
		if ( $max > "" ) {
			$s .= " (maximum " . $max . " " . $unit;
			if ( $unit > "1" ) {
				$s .= "s";
			}
			$s .= ")";
		}
	}
	return $s;
}

function generateDosageRoute($dom) {
	$s = "";
	$routes = $dom->getElementsByTagName('route');
	if ( $routes->length > 0 ) {
		$values = $routes->item(0)->getElementsByTagName('display');
		$value = $values->item(0)->getAttribute('value');
		$s .= $value;
	}
	return $s;
}

function generateDosageSite($dom) {
	$s = "";
	$sites = $dom->getElementsByTagName('site');
	if ( $sites->length > 0 ) {
		$values = $sites->item(0)->getElementsByTagName('display');	
		$value = $values->item(0)->getAttribute('value');
		$s .= $value;
	}
	return $s;
}

function generateDosageWhen($dom) {
	$s = "";
	$mins = "";
    // handle any offset
	$offsets = $dom->getElementsByTagName('offset');
    if ( $offsets->length > 0 ) {
        $mins = $offsets->item(0)->getAttribute('value');
        if ( $mins != "" )
        {
            $s .= valueDaysHoursMinutes($mins);
        }
    }
    
    // loop through any 'when'
	$whens = $dom->getElementsByTagName('when');
    $count = 1;
    $maxCount = (int)$whens->length;
	foreach ($whens as $when) 
	{
		$value = $when->getAttribute('value');
        $s .= valuesetEventTiming($value);
        if ( $count == $maxCount - 1 ) {
            $s .= " and ";
        } elseif ( $count < $maxCount ) {
            $s .= ", ";
        }
        $count += 1;
	}
	return $s;
}

function generateDosageDayOfWeek($dom) {
	$s = "";
	$days = $dom->getElementsByTagName('dayOfWeek');
    $count = 1;
    $maxCount = (int)$days->length;
	foreach ($days as $day) 
	{
		$value = $day->getAttribute('value');
        $s .= valuesetDaysOfWeek($value);
        if ( $count == $maxCount - 1 ) {
            $s .= " and ";
        } elseif ( $count < $maxCount ) {
            $s .= ", ";
        }
        $count += 1;
	}
    if ( $s > "" ) {
        $s = "on " . $s;
    }
	return $s;	
}

function generateDosageTimeOfDay($dom) {
	$s = "";
	$times = $dom->getElementsByTagName('timeOfDay');
    $count = 1;
    $maxCount = (int)$times->length;
	foreach ($times as $time) 
	{
        $s .= $time->getAttribute('value');
        if ( $count == $maxCount - 1 ) {
            $s .= " and ";
        } elseif ( $count < $maxCount ) {
            $s .= ", ";
        }
        $count += 1;
	}
	if ( $s > "" ) {
		$s = "at " . $s;
	}
	return $s;	
}

function generateDosageFrequency($dom) {
	
	$s = "";
	$timings = $dom->getElementsByTagName('timing');
	if ( $timings->length > 0 ) {
	
		$frequencies = $timings->item(0)->getElementsByTagName('frequency');
		if ( $frequencies->length > 0 ) {
			$frequency = $frequencies->item(0)->getAttribute('value');
		}
		
		$periods = $timings->item(0)->getElementsByTagName('period');
		if ( $periods->length > 0 ) {
			$period = $periods->item(0)->getAttribute('value');
		}
		
		$units = $timings->item(0)->getElementsByTagName('periodUnit');
		if ( $units->length > 0 ) {
			$unit = $units->item(0)->getAttribute('value');	
		}
		
		$nodes = $timings->item(0)->getElementsByTagName('periodMax');
		if ( $nodes->length > 0 ) {
			$periodMax = $nodes->item(0)->getAttribute('value');
		}
		
		$nodes = $timings->item(0)->getElementsByTagName('frequencyMax');
		if ( $nodes->length > 0 ) {
			$frequencyMax = $nodes->item(0)->getAttribute('value');
		}
		
		if ( !$frequency && !$period && !$unit && !$periodMax && !$frequencyMax ) {
			return $s;
		}
		
		if ( $frequency < $period ) {
			// every $period $unit
			// twice every $period $unit
			// $frequency times every $period $unit
			// $frequency to $frequencyMax times every $period $unit
			// $frequency times every $period to $periodMax $unit
			// $frequency to $frequencyMax times every $period to $periodMax $unit
			
			if ( $frequencyMax > "" ) {
				if ( $frequency > "" ) {
					$s .= $frequency . " to " . $frequencyMax . " times every ";
				} else {
					$s .= "up to " . $frequencyMax . " times every ";
				}
			}
			else {
				if ( $frequency == "1" ) { 
					$s .= "every ";
				} else if ( $frequency == "2" ) {
					$s .= "twice every ";
				} else {
					$s .= $frequency . " times every ";
				}
			}
			
			if ( $periodMax > "" ) {
				$s .= $period . " to " . $periodMax . " " . valuesetUnitsOfTime($unit);
			} else {
				$s .= $period . " " . valuesetUnitsOfTime($unit);
			}
		} else {
			// once
			// twice
			// $frequency times a $period
			// $frequency times every $period $unit
			// $frequency times every $period to $periodMax $unit
			// $frequency to $frequencyMax times every $period $unit
			// $frequency to $frequencyMax times every $period to $periodMax $unit
			
			if ( $frequencyMax > "" ) {
				if ( $frequency > "" ) {
					$s .= $frequency . " to " . $frequencyMax . " times ";
				} else {
					$s .= "up to " . $frequencyMax . " times ";
				}
			}
			else {
				if ( $frequency == "1" ) { 
					$s .= "once ";
				} else if ( $frequency == "2" ) {
					$s .= "twice ";
				} else {
					$s .= $frequency . " times ";
				}
			}				
			if ( $period == "1" && !$frequencyMax ) {
				$s .= "a " . valuesetUnitsOfTime($unit);
			} else {
				if ( $periodMax > "" ) {
					$s .= "every " . $period . " to " . $periodMax . " " . valuesetUnitsOfTime($unit);
				} else {
					$s .= "every " . $period . " " . valuesetUnitsOfTime($unit);
				}
			}
		}
		// pluralise the units unless it's for a single hour/day/week etc.
		if (( $period != "1" && $period != "") || $frequencyMax ) {
			$s .= "s";
		}
		
	}
	return $s;
}

function generateDosageAsNeeded($dom) {
	$s = "";
	$asNeededBooleans = $dom->getElementsByTagName('asNeededBoolean');
	if ( $asNeededBooleans->length > 0 ) {
		$asNeeded = $asNeededBooleans->item(0)->getAttribute('value');	
		if ( $asNeeded != "" ) {
			$s .= "as required";
		}
	}
	$asNeededCodeds = $dom->getElementsByTagName('asNeededCodeableConcept');
	if ( $asNeededCodeds->length > 0 ) {
		$asNeededDisplays = $asNeededCodeds->item(0)->getElementsByTagName('display');
		$asNeeded = $asNeededDisplays->item(0)->getAttribute('value');
		$s .= "as required for " . $asNeeded;
	}
	return $s;
}

function generateDosageTimingBounds($dom) {
	$s = "";
	$boundsDuration = $dom->getElementsByTagName('boundsDuration');
	if ( $boundsDuration->length > 0 ) {
		$nodes = $boundsDuration->item(0)->getElementsByTagName('value');
		$value = $nodes->item(0)->getAttribute('value');
		$nodes = $boundsDuration->item(0)->getElementsByTagName('unit');
		$unit = $nodes->item(0)->getAttribute('value');
		$s .= "for " . $value . " " . valuesetUnitsOfTime($unit);
		if ( $value != "1" ) {
			$s .= "s";
		}
	}
	
	$boundsRange = $dom->getElementsByTagName('boundsRange');
	if ( $boundsRange->length > 0 ) {
		
        if ( $s > "" ) { $s .= " "; };
        
		$nodes = $boundsRange->item(0)->getElementsByTagName('low');
		$lowNode = $nodes->item(0)->getElementsByTagName('value');
		$lowValue = $lowNode->item(0)->getAttribute('value');
		$lowNode = $nodes->item(0)->getElementsByTagName('unit');
		$lowUnit = $lowNode->item(0)->getAttribute('value');
		
		$nodes = $boundsRange->item(0)->getElementsByTagName('high');
		$highNode = $nodes->item(0)->getElementsByTagName('value');
		$highValue = $highNode->item(0)->getAttribute('value');
		$highNode = $nodes->item(0)->getElementsByTagName('unit');
		$highUnit = $highNode->item(0)->getAttribute('value');
		
		$s .= "for " . $lowValue . " to " . $highValue . " " . $highUnit . "s";
	}

    
    $boundsRange = $dom->getElementsByTagName('boundsPeriod');
    if ( $boundsRange->length > 0 ) {
        if ( $s > "" ) { $s .= " "; };
        $dateStart = "";
        $dateEnd = "";
        $nodes = $boundsRange->item(0)->getElementsByTagName('start');
        if ( $nodes->length > 0 ) {
            $dateStart = $nodes->item(0)->getAttribute('value');
            $s .= "starting " . $dateStart;
        }
        $nodes = $boundsRange->item(0)->getElementsByTagName('end');
        if ( $nodes->length > 0 ) {
            $dateEnd = $nodes->item(0)->getAttribute('value');
            $s .= " until " . $dateEnd;
        }
    }
    
	return $s;
}

function generateDosageTimingCount($dom) {
	$s = "";
	$countMaxs = $dom->getElementsByTagName('countMax');
	$countMax = "";
	if ( $countMaxs->length > 0 ) {
		$countMax = $countMaxs->item(0)->getAttribute('value');
	}
	
	$counts = $dom->getElementsByTagName('count');
	if ( $counts->length > 0 ) {
		$count = $counts->item(0)->getAttribute('value');
        $s .= "take ";
		if ( $count == "1" ) {
			$s .= "once";
		} else {
			if ( $count == "2" && $countMax == "" ) {
				$s .= "twice ";
			} else {
				$s .= $count;
				if ( $countMax != "" ) {
					$s .= " to " . $countMax;
				}
				$s .= " times";
			}
		}
	}
	return $s;
}

function generateDosageTimingEvent($dom) {
	$s = "";
	$nodes = $dom->getElementsByTagName('timing');
	if ( $nodes->length > 0 ) {
		$events = $nodes->item(0)->getElementsByTagName('event');
        $count = 1;
        $maxCount = (int)$events->length;
        foreach ($events as $event)
        {
            $s .= $event->getAttribute('value');
            if ( $count == $maxCount - 1 ) {
                $s .= " and ";
            } elseif ( $count < $maxCount ) {
                $s .= ", ";
            }
            $count += 1;
        }
        if ( $s > "" ) {
            $s = "on " . rtrim($s, ", ");
        }
	}
	return $s;
}

function generateDosagemaxDosePer($dom) {
	$s = "";
	$arr = array(
		"val" => "",
		"uom" => "",
	);
	
	$nodes = $dom->getElementsByTagName('maxDosePerPeriod');
	if ( $nodes->length > 0 ) {
		
		$numerators = $nodes->item(0)->getElementsByTagName('numerator');
		$denominators = $nodes->item(0)->getElementsByTagName('denominator');
		
		if ( $numerators->length > 0 ) {
			$values = $numerators->item(0)->getElementsByTagName('value');
			if ( $values->length > 0 ) {
				$numValue = $values->item(0)->getAttribute('value');
				$numValue = formatDecimal($numValue);
			}
			$values = $numerators->item(0)->getElementsByTagName('code');
			if ( $values->length > 0 ) {
				$numUnit = $values->item(0)->getAttribute('value');		
			}
			$systems = $numerators->item(0)->getElementsByTagName('system');
			if ( $values->length > 0 ) {
				$numSystem = $values->item(0)->getAttribute('value');
				if ( $denSystem = "http://snomed.info/sct" ) {
					$values = $numerators->item(0)->getElementsByTagName('unit');
					if ( $values->length > 0 ) {
						$numUnit = $values->item(0)->getAttribute('value');		
					}
				}				
			}			
		}

		if ( $denominators->length > 0 ) {
			$values = $denominators->item(0)->getElementsByTagName('value');
			if ( $values->length > 0 ) {
				$denValue = $values->item(0)->getAttribute('value');
			}
			$values = $denominators->item(0)->getElementsByTagName('code');
			if ( $values->length > 0 ) {
				$denUnit = $values->item(0)->getAttribute('value');		
			}
			$systems = $denominators->item(0)->getElementsByTagName('system');
			if ( $values->length > 0 ) {
				$denSystem = $values->item(0)->getAttribute('value');	
				if ( $denSystem = "http://snomed.info/sct" ) {
					$values = $denominators->item(0)->getElementsByTagName('unit');
					if ( $values->length > 0 ) {
						$denUnit = $values->item(0)->getAttribute('value');		
					}
				}
			}			
		}
		
		$s .= "up to a maximum of " .	$numValue . " " . $numUnit;
		if ( $denValue == "1" ) {
			$s .= " per " . " " . $denUnit;
		} else {
			$s .= " in " . $denValue . " " . $denUnit;
		}
		if ( $denValue > 1 ) {
			$s .= "s";
		}
	}
	$nodes = $dom->getElementsByTagName('maxDosePerAdministration');
	if ( $nodes->length > 0 ) {
		$values = $nodes->item(0)->getElementsByTagName('value');
		$value = $values->item(0)->getAttribute('value');
		$value = formatDecimal($value);
		$values = $nodes->item(0)->getElementsByTagName('code');
		$unit = $values->item(0)->getAttribute('value');			
		$s .= "up to a maximum of " .	$value . " " . $unit . " per dose";
	}
	$nodes = $dom->getElementsByTagName('maxDosePerLifetime');
	if ( $nodes->length > 0 ) {
		$values = $nodes->item(0)->getElementsByTagName('value');
		$value = $values->item(0)->getAttribute('value');
		$value = formatDecimal($value);
		$values = $nodes->item(0)->getElementsByTagName('code');
		$unit = $values->item(0)->getAttribute('value');			
		$s .= "up to a maximum of " .	$value . " " . $unit . " over lifetime of patient";
	}
	return $s;
}

function generateDosageAdditionalInstructions($dom) {
	$s = "";
	$instructions = $dom->getElementsByTagName('additionalInstruction');
    $count = 1;
    $maxCount = (int)$instructions->length;
	foreach ($instructions as $instruction) 
	{
		$nodes = $instruction->getElementsByTagName('text');
		if ( $nodes->length > 0 ) {
			$s .= $nodes->item(0)->getAttribute('value');
		}
		$nodes = $instruction->getElementsByTagName('display');
		if ( $nodes->length > 0 ) {
			$s .= $nodes->item(0)->getAttribute('value');
		}
        if ( $count == $maxCount - 1 ) {
            $s .= " and ";
        } elseif ( $count < $maxCount ) {
            $s .= ", ";
        }
        $count += 1;
	}
	return $s;
}

function generateDosagePatientInstructions($dom) {
	$s = "";
	$instructions = $dom->getElementsByTagName('patientInstruction');
	if ( $instructions->length > 0 ) {
		$s = $instructions->item(0)->getAttribute('value');
	}
	return $s;
}

function generateMedicationName($dom) {
	$s = "";
	// most likely be a <medication> or <Medication>
	$nodes = $dom->getElementsByTagName('medication');
	if ( $nodes->length == 0 ) {
		$nodes = $dom->getElementsByTagName('Medication');
	}
	if ( $nodes->length > 0 ) {
		$medication = $nodes->item(0)->getElementsByTagName('display');
		if ( $medication->length > 0 ) {
			$s .= $medication->item(0)->getAttribute('value');
		}
	} else {
		// may also be a <medicationCodeableConcept>
		$nodes = $dom->getElementsByTagName('medicationCodeableConcept');
		if ( $nodes->length > 0 ) {
			$medication = $nodes->item(0)->getElementsByTagName('display');
			if ( $medication->length > 0 ) {
				$s .= $medication->item(0)->getAttribute('value');
			}
		}
	}	
	return $s;
}

function generateMedicationForm($dom) {
	$s = "";
	$nodes = $dom->getElementsByTagName('form');
	if ( $nodes->length > 0 ) {
		$form = $nodes->item(0)->getElementsByTagName('display');
		if ( $form->length > 0 ) {
			$s .= $form->item(0)->getAttribute('value');
		}
	}
	return $s;
}

function generateMedicationBrand($dom) {
	$s = "";
	$nodes = $dom->getElementsByTagName('medication');
	if ( $nodes->length > 0 ) {
		$medication = $nodes->item(0)->getElementsByTagName('display');
		if ( $medication->length > 0 ) {
			$med = $medication->item(0)->getAttribute('value');
		}
		$nodeDiv = $nodes->item(0)->getElementsByTagName('div');
		if ( $nodeDiv->length > 0 ) {
			$s = ltrim($nodeDiv->item(0)->nodeValue, $med);
			if ( $s > "" ) {
				$s = substr("$s", 1, -1);
			}
		}		
	}
	return $s;
}

function createInstructionString($arr) {
	$s = "";
	if ( $arr["method"] > "" ) { $s .= $arr["method"] ; }
	if ( $arr["doseQuantity"] > "" ) { $s .= $arr["doseQuantity"] . SEPARATOR ; }
	if ( $arr["doseRange"] > "" ) { $s .= $arr["doseRange"] . SEPARATOR ; }
	if ( $arr["rateRatio"] > "" ) { $s .= $arr["rateRatio"] . SEPARATOR ; }
	if ( $arr["rateRange"] > "" ) { $s .= $arr["rateRange"] . SEPARATOR ; }
	if ( $arr["rateQuantity"] > "" ) { $s .= $arr["rateQuantity"] . SEPARATOR ; }
	if ( $arr["duration"] > "" ) { $s .= $arr["duration"] . SEPARATOR ; }
    if ( $arr["timingFrequency"] > "" ) { $s .= $arr["timingFrequency"] . SEPARATOR ; }
	if ( $arr["timingWhen"] > "" ) { $s .= $arr["timingWhen"] . SEPARATOR ; }
	if ( $arr["timingDayOfWeek"] > "" && $arr["timingTimeOfDay"] > "" ) {
		$s .= $arr["timingDayOfWeek"] . " " . $arr["timingTimeOfDay"] . SEPARATOR ;
	}
	else {
		if ( $arr["timingDayOfWeek"] > "" ) {$s .= $arr["timingDayOfWeek"] . SEPARATOR ; }
		if ( $arr["timingTimeOfDay"] > "" ) { $s .= $arr["timingTimeOfDay"] . SEPARATOR ; }
	}
    if ( $arr["route"] > "" ) { $s .= $arr["route"] . SEPARATOR ; }
    if ( $arr["site"] > "" ) { $s .= $arr["site"] . SEPARATOR ; }
	if ( $arr["asNeeded"] > "" ) { $s .= $arr["asNeeded"] . SEPARATOR ; }
	if ( $arr["timingBounds"] > "" ) { $s .= $arr["timingBounds"] . SEPARATOR ; }
	if ( $arr["timingCount"] > "" ) { $s .= $arr["timingCount"] . SEPARATOR ; }
	if ( $arr["timingEvent"] > "" ) { $s .= $arr["timingEvent"] . SEPARATOR ; }
	if ( $arr["maxDosePer"] > "" ) { $s .= $arr["maxDosePer"] . SEPARATOR ; }
	if ( $arr["additionalInstruction"] > "" ) { $s .= $arr["additionalInstruction"] . SEPARATOR ; }
	if ( $arr["patientInstruction"] > "" ) { $s .= $arr["patientInstruction"] . SEPARATOR ; }
	return rtrim($s, SEPARATOR);
}
    
function createCUIDosageString($xxx, $option) {
	$s = "";
	$part = "";
	$sep = SEPARATOR;
	
    if ( $option == "html" ) {
        $sep = "<br/>";
    }
    
    $count = 1;
    $maxCount = sizeof($xxx);
    
    foreach ($xxx as $arr) {

        if ( $arr["sequenceType"] == "SEQUENTIAL" ) {
            $clauseSep = $sep . "Followed by";
        } elseif ( $arr["sequenceType"] == "CONCURRENT" ) {
            $clauseSep = $sep . "And";
        } else {
            $clauseSep = "X";
        }

        if ( $count <= $maxCount && $count != 1 ) {
            $s .= "<span style='font-family:courier; font-size:smaller;font-weight:bold;color:black;'>" . $clauseSep . " </span>";
        }
        $count += 1;
        
        // method
        if ( $arr["method"] > "" ) {
            if ( $s > "" ) { $s .= $sep; }
            if ( $option == "html" ) {
                $s .= "<span style='font-family:courier; font-size:smaller; font-weight:bold; color:blue;'>METHOD </span>";
            }
            $s .= $arr["method"];
        }
        
        // dose
        if ( $arr["doseQuantity"] > "" || $arr["doseRange"] > "" || $arr["rateRatio"] > "" || $arr["rateRange"] > "" || $arr["rateQuantity"] > "" || $arr["duration"] > ""  )
        {
            if ( $s > "" ) { $s .= $sep; }
            $s .= "<span style='font-family:courier; font-size:smaller;font-weight:bold;color:blue;'>DOSE </span>";
            $part = "";
            if ( $arr["doseQuantity"] > "" ) { $part .= $arr["doseQuantity"] . SEPARATOR ; }
            if ( $arr["doseRange"] > "" ) { $part .= $arr["doseRange"] . SEPARATOR ; }
            if ( $arr["rateRatio"] > "" ) { $part .= $arr["rateRatio"] . SEPARATOR ; }
            if ( $arr["rateRange"] > "" ) { $part .= $arr["rateRange"] . SEPARATOR ; }
            if ( $arr["rateQuantity"] > "" ) { $part .= $arr["rateQuantity"] . SEPARATOR ; }
            if ( $arr["duration"] > "" ) { $parts .= $arr["duration"] . SEPARATOR ; }
            $s .= rtrim($part, SEPARATOR);
        }
        
        // route
        if ( $arr["route"] > "" ) {
            if ( $s > "" ) { $s .= $sep; }
            if ( $option == "html" ) {
                $s .= "<span style='font-family:courier; font-size:smaller;font-weight:bold;color:blue;'>ROUTE </span>";
            }
            $s .= $arr["route"] ;
        }
        
        //site
        if ( $arr["site"] > "" ) {
            if ( $s > "" ) { $s .= $sep; }
            if ( $option == "html" ) {
                $s .= "<span style='font-family:courier; font-size:smaller;font-weight:bold;color:blue;'>SITE </span>";
            }
            $s .= $arr["site"] ;
        }
        
        // timing
        if ( $arr["timingWhen"] > "" || $arr["timingDayOfWeek"] > "" || $arr["timingTimeOfDay"] > "" || $arr["timingFrequency"] > "" || $arr["asNeeded"] > "" || $arr["timingBounds"] > "" || $arr["timingCount"] > "" || $arr["timingEvent"] > "" )
        {
            if ( $s > "" ) { $s .= $sep; }
            if ( $option == "html" ) {
                $s .= "<span style='font-family:courier; font-size:smaller;font-weight:bold;color:blue;'>TIMING </span>";
            }
            $part = "";
            if ( $arr["timingFrequency"] > "" ) { $part .= $arr["timingFrequency"] . SEPARATOR ; }
            if ( $arr["timingWhen"] > "" ) { $part .= $arr["timingWhen"] . SEPARATOR ; }
            if ( $arr["timingDayOfWeek"] > "" && $arr["timingTimeOfDay"] > "" ) {
                $part .= $arr["timingDayOfWeek"] . " " . $arr["timingTimeOfDay"] . SEPARATOR ;
            }
            else {
                if ( $arr["timingDayOfWeek"] > "" ) { $part .= $arr["timingDayOfWeek"] . SEPARATOR ; }
                if ( $arr["timingTimeOfDay"] > "" ) { $part .= $arr["timingTimeOfDay"] . SEPARATOR ; }
            }
            if ( $arr["asNeeded"] > "" ) { $part .= $arr["asNeeded"] . SEPARATOR ; }
            if ( $arr["timingBounds"] > "" ) { $part .= $arr["timingBounds"] . SEPARATOR ; }
            if ( $arr["timingCount"] > "" ) { $part .= $arr["timingCount"] . SEPARATOR ; }
            if ( $arr["timingEvent"] > "" ) { $part .= $arr["timingEvent"] . SEPARATOR ; }
            $s .= rtrim($part, SEPARATOR);
        }
        
        // additional stuff
        $part = "";
        if ( $arr["maxDosePer"] > "" ) { $part .= $arr["maxDosePer"] . SEPARATOR ; }
        if ( $arr["additionalInstruction"] > "" ) { $part .= $arr["additionalInstruction"] . SEPARATOR ; }
        if ( $arr["patientInstruction"] > "" ) { $part .= $arr["patientInstruction"] . SEPARATOR ; }
        if ( $part > "" ) {
            $s .= $sep . rtrim($part, SEPARATOR);
        }
        
    }
	return $s;
}
?>
