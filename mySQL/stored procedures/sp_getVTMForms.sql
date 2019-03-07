CREATE DEFINER=`masteruser`@`%` PROCEDURE `sp_getVTMForms`(IN vtmid BIGINT)
BEGIN

SELECT vmpform.formid, lookup.name
FROM vtm 
INNER JOIN vmp ON vmp.vtmid = vtm.vtmid
INNER JOIN vmpform ON vmpform.vmpid = vmp.vmpid
INNER JOIN lookup ON lookup.id = vmpform.formid
WHERE vtm.vtmid = vtmid
GROUP BY lookup.name
ORDER BY lookup.name;

END