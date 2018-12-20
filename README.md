# Project MILK (Medicines Interoperability Logic Kit)

A collection of RESTful web services to support medicines interoperability between NHS care settings that are implemented using the FHIR standard (based on FHIR STU3). Includes conversion functions between dose-based and product-based medication orders/requests/prescriptions. Uses the data sources of the NHS Dictionary of Medicines and Devices (dm+d) and SNOMED-CT.

## Function: getConcept

Return information for the given dm+d concept id.

### Input parameters

"id" | dm+d concept id | string |

"ot" | output type | string | value-set = json, xml | default-value = json

"of" | output format | string | value-set = summary, complete, careconnect-medication-1 | default value = summary

### Output

Info to be added

## Function: getTextFromDose

Info to be added

## Function: getPatientTextFromDose

Info to be added

## Function: convertDoseToProduct

Info to be added

## Function: convertProductToDose

Info to be added
