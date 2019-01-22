<?php
    
include("functions.php");

$xml = urldecode($_REQUEST['dosage']);

$dom = new DOMDocument;
$dom->loadXML($xml);
$completeMedicationString = "";
$completeInstructionString = "";

$medication = generateMedicationName($dom);
$brand = generateMedicationBrand($dom);
$form = generateMedicationForm($dom);

if ( $medication > "" ) { $completeMedicationString .= $medication . SEPARATOR ; }
if ( $brand > "" ) { $completeMedicationString .= strtoupper ($brand) . SEPARATOR ; }
if ( $form > "" ) { $completeMedicationString .= $form . SEPARATOR ; }

$dosageInstructions = $dom->getElementsByTagName('dosageInstruction');
$multiSequence = false;
$lastSeq = (int)1;
if ( $dosageInstructions->length > 1 ) {
	$multiSequence = true;
}

$firstInstruction = true;
foreach ($dosageInstructions as $instruction) 
{
	if ( $multiSequence ) {
		if ( $firstInstruction ) {
			$firstInstruction = false;
		} else {
			$nodes = $instruction->getElementsByTagName('sequence');
			if ( $nodes->length > 0 ) {
				$seq = (int)$nodes->item(0)->getAttribute('value');
				if ( $seq > $lastSeq ) {
					$completeInstructionString .= " *then* ";
				} else {
					$completeInstructionString .= " *and* ";
				}
				$lastSeq = $seq;
			}
		}
	}
	
	$dosageStructureArray = array(
		"method" => generateDosageMethod($instruction),
		"doseQuantity" => generateDosageDoseQuantity($instruction),
		"doseRange" => generateDosageDoseRange($instruction),
		"rateRatio" => generateDosageRateRatio($instruction),
		"rateRange" => generateDosageRateRange($instruction),
		"rateQuantity" => generateDosageRateQuantity($instruction),
		"duration" => generateDosageDuration($instruction),
		"route" => generateDosageRoute($instruction),
		"site" => generateDosageSite($instruction),
		"timingWhen" =>  generateDosageWhen($instruction),
		"timingDayOfWeek" =>  generateDosageDayOfWeek($instruction),
		"timingTimeOfDay" => generateDosageTimeOfDay($instruction),
		"timingFrequency" => generateDosageFrequency($instruction),
		"asNeeded" => generateDosageAsNeeded($instruction),
		"timingBounds" => generateDosageTimingBounds($instruction),
		"timingCount" =>  generateDosageTimingCount($instruction),
		"timingEvent" => generateDosageTimingEvent($instruction),
		"maxDosePer" => generateDosagemaxDosePer($instruction),
		"additionalInstruction" => generateDosageAdditionalInstructions($instruction),
		"patientInstruction" => generateDosagePatientInstructions($instruction),
	);
	$completeInstructionString .= createInstructionString($dosageStructureArray);
}
echo $completeMedicationString . $completeInstructionString;
?>
