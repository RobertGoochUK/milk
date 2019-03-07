CREATE DEFINER=`masteruser`@`%` PROCEDURE `sp_getLookupID`(IN IN_name VARCHAR(30))
BEGIN

SELECT id FROM lookup WHERE name = IN_name LIMIT 1;

END