CREATE DEFINER=`masteruser`@`%` FUNCTION `calc_rank`(doseQ DECIMAL(9,3), num DECIMAL(30,12), den DECIMAL(9,3), udfs DECIMAL) RETURNS decimal(30,12)
BEGIN
## doseQ is the desired dose quantity
## num is the VMP strength numerator, scaled for same units as doseQ
## den is the VMP strength denominator (set to 1 is not defined)
## udfs is the VMP UDFS, but need to solve the problem of this not populated for unlicensed routes (or whatever)
	IF den = 0 THEN SET den = 1; END IF;
    IF udfs = 0 THEN RETURN doseQ / ( num / den ); END IF;
    RETURN ( doseQ / ( num / den ) ) / udfs;
END