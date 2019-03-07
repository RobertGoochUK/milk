CREATE DEFINER=`masteruser`@`%` PROCEDURE `sp_getVTMUOMs`(IN vtm_id BIGINT)
BEGIN

SELECT vpi.strnt_nmrtr_uomcd, lookup.name, count(*) AS c
FROM vtm 
INNER JOIN vmp ON vmp.vtmid = vtm.vtmid
INNER JOIN vpi ON vpi.vmpid = vmp.vmpid
INNER JOIN lookup ON lookup.id = vpi.strnt_nmrtr_uomcd
WHERE vtm.vtmid = vtm_id
AND lookup.valueset = "UOM"
GROUP BY lookup.name
ORDER BY c DESC, lookup.name;

END