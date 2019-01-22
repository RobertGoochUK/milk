<html>
<head>
<style>
	.medicationCUI {font-family: Arial;border:1px;width:745px;padding:5px;border-style:solid;}
	.medsLabelCUI {font-size:smaller;font-weight:bold;color:blue;}
	.dosageElement {font-size:smaller;border-style:solid;padding:2px;}
</style>
</head>
<body>

<?php
    
include("functions.php");
    
$xml = urldecode($_REQUEST['dosage']);

$xmlxxx = '
<medicationRequest>
<contained>
		<medication>
			<id value="med1"/>
			<code>
				<coding>
					<system value="http://snomed.info/sct"/>
					<code value="324095003"/>
					<display value="Oxytetracycline 250mg tablets"/>
				</coding>
			</code>
<form>
<coding>
<system value="http://snomed.info/sct"/> 
<code value="385194003"/> 
<display value="suppository"/> 
</coding>
</form>			
			<text><div>Oxytetracycline 250mg tablets (BrandX)</div></text>
		</medication>
	</contained>
<dosageInstruction>
<method>
	<coding>
		<system value="http://snomed.info/sct"/>
		<code value="422145002"/>
		<display value="inject"/>
	</coding>
</method>
<doseQuantity>
	<value value="1"/>
	<unit value="capsule"/>
	<system value="http://snomed.info/sct"/>
	<code value="732937005"/>
</doseQuantity>
<rateRatio>
	<numerator>
		<value value="30"/>
		<unit value="millilitre"/>
		<system value="http://unitsofmeasure.org"/>
		<code value="mL"/>
	</numerator>
	<denominator>
		<value value="1"/>
		<unit value="hour"/>
		<system value="http://unitsofmeasure.org"/>
		<code value="h"/>
	</denominator>
</rateRatio>
<rateRange>
	<low>
		<value value="1"/>
		<unit value="liter per minute"/>
		<system value="http://unitsofmeasure.org"/>
		<code value="L/min"/>
	</low>
	<high>
		<value value="2"/>
		<unit value="liter per minute"/>
		<system value="http://unitsofmeasure.org"/>
		<code value="L/min"/>
	</high>
</rateRange>
<rateQuantity>
	<value value="1"/>
	<unit value="microgram per kilogram per hour"/>
	<system value="http://unitsofmeasure.org"/>
	<code value="ug/kg/h"/>
</rateQuantity>
<timing>
<repeat>
	<frequency value="1"/>
	<period value="8"/>
	<periodUnit value="h"/>	
	<duration value="4"/>
	<durationUnit value="h" />
	<durationMax value="8"/>
	<dayOfWeek value="mon"/>
	<dayOfWeek value="wed"/>
	<dayOfWeek value="fri"/>
	<timeOfDay value="10:00"/>
	<timeOfDay value="15:00"/>
	<offset value="60"/>
	<when value="WAKE"/>
	<when value="AC"/>	
	<asNeededCodeableConcept>
		<coding>
			<system value="http://snomed.info/sct"/>
			<code value="37796009"/>
			<display value="Migraine"/>
		</coding>
	</asNeededCodeableConcept>		
	<boundsDuration>
		<value value="7"/>
		<unit value="day"/> 
		<code value="d"/>
		<system value="http://unitsofmeasure.org"/>
	</boundsDuration>
	<boundsRange>
		<low>
			<value value="2"/>
			<unit value="hour"/> 
			<system value="http://unitsofmeasure.org"/>
			<code value="h"/>
		</low>
		<high>
			<value value="4"/>
			<unit value="hour"/>
			<system value="http://unitsofmeasure.org"/>
			<code value="h"/>
		</high>
	</boundsRange>	
	<count value="3"/>
	<countMax value="4"/>	
</repeat>
</timing>
<route>
	<coding>
		<system value="http://snomed.info/sct"/>
		<code value="78421000"/>
		<display value="intramuscular"/>
	</coding>
</route>
<site>
	<coding>
		<system value="http://snomed.info/sct"/>
		<code value="59380008"/>
		<display value="Anterior abdominal wall structure"/>
	</coding>
</site>
<maxDosePerPeriod>
	<numerator>
		<value value="1000"/>
		<unit value="milligram"/>
		<system value="http://unitsofmeasure.org"/>
		<code value="mg"/>
	</numerator>
	<denominator>
		<value value="24"/>
		<unit value="hour"/>
		<system value="http://unitsofmeasure.org"/>
		<code value="h"/>
	</denominator>
</maxDosePerPeriod>
<maxDosePerAdministration>
	<value value="2"/>
	<unit value="milligram"/>
	<system value="http://unitsofmeasure.org"/>
	<code value="mg"/>
</maxDosePerAdministration>
<maxDosePerLifetime>
	<value value="600"/>
	<unit value="milligram per square metre"/>
	<system value="http://unitsofmeasure.org"/>
	<code value="mg/m2"/>
</maxDosePerLifetime>
<additionalInstruction>
	<text value="with dialysis"/>
</additionalInstruction>
<additionalInstruction>
	<coding> 
		<system value="http://snomed.info/sct"/> <code value="417995008"/> 
		<display value="Dissolve or mix with water before taking"/> 
	</coding>
</additionalInstruction>
<patientInstruction value="avoid grapefruit"/>
</dosageInstruction>
</medicationRequest>';
?>

<?php
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
?>

<div>
<button type="button" onclick="location.href='index.html'" style="background:lightblue;padding:10px;">BACK TO FORM</button>

<h2>Instruction as a human readable string (with no formatting)</h2>
<div class="medicationCUI">
<?php echo $completeMedicationString . $completeInstructionString; ?>
</div>

<?php
$sOut = "<text><status value='generated'/><div>" . $completeMedicationString . $completeInstructionString . "</div></text>";
$sOutdom = new DOMDocument;
$sOutdom->loadXML($sOut);
?>
<h2>as a CareConnect medicationRequest.text XML fragment</h2>
<div class="medicationCUI">
<?php echo htmlspecialchars($sOutdom->saveXML()); ?>
</div>

<?php
$sOut = "<text value='" . $completeInstructionString . "'/>";
$sOutdom = new DOMDocument;
$sOutdom->loadXML($sOut);
?>
<h2>as a FHIR STU3 Dosage.text XML fragment</h2>
<div class="medicationCUI">
<?php echo htmlspecialchars($sOutdom->saveXML()); ?>
</div>

<h2>formatted as per CUI guidance</h2>
<h3>as a single line instruction</h3>
<div class="medicationCUI">
<?php 
if ( $completeMedicationString > "" ) {
	echo "<span style='font-weight:bold;'>" . $medication . "</span>";
	if ( $brand > "" ) {
		echo " - " . strtoupper($brand);
	}
	if ( $form > "" ) {
		echo " - " . $form;
	}
	echo " - ";
}
echo createCUIDosageString($dosageStructureArray, "SINGLE");
?>
</div>
<h3>as a multi-line instruction</h3>
<div class="medicationCUI">
<?php 
if ( $completeMedicationString > "" ) {
	echo "<span style='font-weight:bold;'>" . $medication . "</span>";
	if ( $brand > "" ) {
		echo " - " . strtoupper($brand);
	}
	if ( $form > "" ) {
		echo " - " . $form;
	}
	echo "<br/>";
}
echo createCUIDosageString($dosageStructureArray, "MULTI");
?>
</div>

<div>
<h2>Raw Input XML</h2>
<p>
<textarea cols="100" rows="40" readonly>
<?php echo $dom->saveXML(); ?>
</textarea>
</p>
</div>

</body>
</html>
