CREATE DEFINER=`masteruser`@`%` FUNCTION `convert_units`(val DECIMAL, source_cd BIGINT, target_cd BIGINT) RETURNS decimal(30,12)
BEGIN
	DECLARE scaler DECIMAL(20,9) UNSIGNED;
    DECLARE result DECIMAL(30,12) UNSIGNED;
	CASE
		## weight
		WHEN ( source_cd = 258683005 && target_cd = 258682000 ) THEN SET scaler = 1000; ## kilogram to gram
    	WHEN ( source_cd = 258683005 && target_cd = 258684004 ) THEN SET scaler = 1000000; ## kilogram to mg
    	WHEN ( source_cd = 258683005 && target_cd = 258685003 ) THEN SET scaler = 1000000000; ## kilogram to microgram
    	WHEN ( source_cd = 258683005 && target_cd = 258686002 ) THEN SET scaler = 1000000000000; ## kilogram to nanogram
        
		WHEN ( source_cd = 258682000 && target_cd = 258684004 ) THEN SET scaler = 1000; ## gram to mg
		WHEN ( source_cd = 258682000 && target_cd = 258685003 ) THEN SET scaler = 1000000;  ## gram to microgram
		WHEN ( source_cd = 258682000 && target_cd = 258686002 ) THEN SET scaler = 1000000000; ## gram to nanogram
        
		WHEN ( source_cd = 258684004 && target_cd = 258682000 ) THEN SET scaler = 0.001; ## mg to gram
		WHEN ( source_cd = 258684004 && target_cd = 258685003 ) THEN SET scaler = 1000; ## mg to microgram
		WHEN ( source_cd = 258684004 && target_cd = 258686002 ) THEN SET scaler = 1000000; ## mg to nanogram
        
		WHEN ( source_cd = 258685003 && target_cd = 258682000 ) THEN SET scaler = 0.000001; ## microgram to gram
		WHEN ( source_cd = 258685003 && target_cd = 258684004 ) THEN SET scaler = 0.001; ## microgram to mg
		WHEN ( source_cd = 258685003 && target_cd = 258686002 ) THEN SET scaler = 1000; ## microgram to nanogram
        
		WHEN ( source_cd = 258686002 && target_cd = 258682000 ) THEN SET scaler = 0.000000001; ## nanogram to gram
		WHEN ( source_cd = 258686002 && target_cd = 258684004 ) THEN SET scaler = 0.000001; ## nanogram to mg
		WHEN ( source_cd = 258686002 && target_cd = 258685003 ) THEN SET scaler = 0.001; ## nanogram to microgram
        
        ## volume
        WHEN ( source_cd = 258770004 && target_cd = 258773002 ) THEN SET scaler = 1000; ## litre to ml
		WHEN ( source_cd = 258770004 && target_cd = 258774008 ) THEN SET scaler = 1000000;  ## litre to microlitre
		WHEN ( source_cd = 258770004 && target_cd = 282113003 ) THEN SET scaler = 1000000000; ## litre to nanolitre
	
        WHEN ( source_cd = 258773002 && target_cd = 258773002 ) THEN SET scaler = 0.001; ## ml to litre
		WHEN ( source_cd = 258773002 && target_cd = 258774008 ) THEN SET scaler = 1000;  ## ml to microlitre
		WHEN ( source_cd = 258773002 && target_cd = 282113003 ) THEN SET scaler = 1000000; ## ml to nanolitre

        WHEN ( source_cd = 258774008 && target_cd = 258773002 ) THEN SET scaler = 0.000001; ## microlitre to litre
		WHEN ( source_cd = 258774008 && target_cd = 258774008 ) THEN SET scaler = 0.001;  ## microlitre to ml
		WHEN ( source_cd = 258774008 && target_cd = 282113003 ) THEN SET scaler = 1000; ## microlitre to nanolitre
        
        WHEN ( source_cd = 282113003 && target_cd = 258773002 ) THEN SET scaler = 0.000000001; ## nanolitre to litre
		WHEN ( source_cd = 282113003 && target_cd = 258774008 ) THEN SET scaler = 0.000001;  ## nanolitre to ml
		WHEN ( source_cd = 282113003 && target_cd = 282113003 ) THEN SET scaler = 0.001; ## nanolitre to microlitre
        
        ## length
        WHEN ( source_cd = 258669008 && target_cd = 258672001 ) THEN SET scaler = 1000; ## m to cm
		WHEN ( source_cd = 258669008 && target_cd = 258673006 ) THEN SET scaler = 1000000; ## m to mm
        
        WHEN ( source_cd = 258672001 && target_cd = 258669008 ) THEN SET scaler = 0.001; ## cm to m
		WHEN ( source_cd = 258672001 && target_cd = 258673006 ) THEN SET scaler = 1000; ## cm to mm
        
        WHEN ( source_cd = 258673006 && target_cd = 258669008 ) THEN SET scaler = 0.000001; ## mm to m
		WHEN ( source_cd = 258673006 && target_cd = 258672001 ) THEN SET scaler = 0.001; ## mm to cm
		
		ELSE SET scaler = 1; 
	END CASE;
    SET result = val * scaler;
    RETURN result;
END