# Project MILK (Medicines Interoperability Logic Kit)

A collection of web services to support medicines interoperability between NHS care settings that are implemented using the FHIR standard (based on FHIR STU3). Includes conversion functions between dose-based and product-based medication orders/requests/prescriptions. Uses the data sources of the NHS Dictionary of Medicines and Devices (dm+d) and SNOMED-CT.

## Temporary Implementation (will soon be hosted on AWS)
http://mklacrosse.co.uk/milk/

## Function: getConcept

Return information for the given dm+d concept id.

### Input parameters

"id" | dm+d concept id | string | mandatory

"ot" | output type | string | value-set = json, xml | default-value = json

"of" | output format | string | value-set = summary, complete, careconnect-medication-1 | default value = summary

### Output

When the output format is “summary” returns;

  * Concept name
  * Concept description
  * Concept code
  * Plus other stuff deemed required for the most generic use cases, e.g. maybe some flag information such as if invalid?

When the output format is “compete” returns all dm+d information for the given concept. 

When the output format is “careconnect-medication-1” returns dm+d information for the given concept that is supported by that CareConnect profiled resource.

## Function: getTextFromDose

Return a suitable string to use as the Dosage.text from the structured content within the Dosage structure.

### Input parameters

Populated Dosage structure in either XML or json format.

### Output

A string value that the requesting system can use to populate the Dosage.text element to complete the FHIR medication resource. 

## Function: getPatientTextFromDose

Return a suitable dosage string for patient use from the structured content within the Dosage structure. Use cases would include dispensing labels and patient dosage information within patient-facing applications. 

### Input parameters

Info to be added

### Output

Info to be added

## Function: convertDoseToProduct

Return a list of dm+d products (VMP or AMP) that are suitable to fulfil the given VTM and FHIR Dosage instruction. If a trade family (brand) is specified in the dosage instruction then return AMPs, otherwise return AMPs.

### Input parameters

"vtm" | vtm concept id | mandatory

"dosage" | FHIR Dosage structure | optional

"form" | dm+d form id | optional

"tradefamily" | SNOMED-CT trade family id | optional

### Output

A list of dm+d concept ids.

## Function: convertProductToDose

Return a populated FHIR Dosage structure suitable to continue the medication described as a product-based instruction.

### Input parameters

Info to be added

### Output

Info to be added
