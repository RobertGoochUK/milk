CREATE DEFINER=`masteruser`@`%` PROCEDURE `sp_getLookupName`(IN IN_id BIGINT UNSIGNED)
BEGIN

SELECT name FROM lookup WHERE id = IN_id LIMIT 1;

END