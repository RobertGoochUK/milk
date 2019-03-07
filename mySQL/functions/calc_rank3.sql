CREATE DEFINER=`masteruser`@`%` FUNCTION `calc_rank3`(doseQ DECIMAL(9,3), num DECIMAL(30,12), den DECIMAL(9,3), udfs DECIMAL, formid BIGINT UNSIGNED) RETURNS smallint(5) unsigned
BEGIN
## doseQ is the desired dose quantity
## num is the VMP strength numerator, scaled for same units as doseQ
## den is the VMP strength denominator (set to 1 is not defined)
## udfs is the VMP UDFS, but need to solve the problem of this not populated for unlicensed routes (or whatever)
## return rank with additoions to push none whole products down the ranking order
	DECLARE rank DECIMAL(30,12) UNSIGNED;
    DECLARE ret SMALLINT UNSIGNED;
    DECLARE divisable BIGINT UNSIGNED;
    
    IF ( den = 0 ) THEN 
		SET den = 1; 
	END IF;
    IF ( udfs = 0 ) THEN 
		SET rank = doseQ / ( num / den ); 
	ELSE
		SET rank = ( doseQ / ( num / den ) ) / udfs;
    END IF;
    
	IF ( rank < 1 ) THEN
		##SET rank = rank + 2000; 
        SET ret = 3; 
	ELSEIF ( ( rank % 1 ) != 0 ) THEN
		##SET rank = rank + 1000;
        SET ret = 2;
	ELSE
		SET ret = 1;
	END IF;
    
    IF (ret != 1) THEN
		SELECT name FROM lookup WHERE valueset="NOTDIVISABLE" AND id = formid INTO divisable;
		IF ( !IsNull(divisable) ) THEN
			SET ret = 4;
		END IF;
    END IF;
    RETURN ret;
END