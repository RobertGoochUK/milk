CREATE DEFINER=`masteruser`@`%` PROCEDURE `sp_getConcept`(IN IN_vtmid BIGINT UNSIGNED)
BEGIN

SELECT vtm.name AS name, null AS formid, null AS formdesc 
FROM vtm 
WHERE vtmid = IN_vtmid
UNION 
SELECT vmp.name AS name, vmpform.formid AS formid, lookup.name AS formdesc 
FROM vmp 
INNER JOIN vmpform ON vmp.vmpid = vmpform.vmpid 
INNER JOIN lookup ON vmpform.formid = lookup.id 
WHERE vmp.vmpid = IN_vtmid
UNION 
SELECT amp.name AS name, vmpform.formid AS formid, lookup.name AS formdesc 
FROM amp 
INNER JOIN vmpform ON amp.vmpid = vmpform.vmpid 
INNER JOIN lookup ON vmpform.formid = lookup.id 
WHERE ampid = IN_vtmid;
    
END