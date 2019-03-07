CREATE DEFINER=`masteruser`@`%` PROCEDURE `sp_getVTMRoutes`(IN vtmid BIGINT)
BEGIN

SELECT vmproute.routeid, lookup.name
FROM vtm 
INNER JOIN vmp ON vmp.vtmid = vtm.vtmid
INNER JOIN vmproute ON vmproute.vmpid = vmp.vmpid
INNER JOIN lookup ON lookup.id = vmproute.routeid
WHERE vtm.vtmid = vtmid
GROUP BY lookup.name
ORDER BY lookup.name;

END