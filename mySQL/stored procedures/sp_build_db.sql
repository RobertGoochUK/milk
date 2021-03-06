CREATE DEFINER=`masteruser`@`%` PROCEDURE `sp_build_db`()
BEGIN

CREATE TABLE IF NOT EXISTS lookupUOM (
ucum_cd VARCHAR(30) NOT NULL,
snct_uomcd BIGINT UNSIGNED NOT NULL
);

CREATE TABLE IF NOT EXISTS lookup (
valueset VARCHAR(30) NOT NULL,
id BIGINT UNSIGNED NOT NULL,
name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS vtm (
vtmid BIGINT UNSIGNED PRIMARY KEY,
name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS vmp (
vmpid BIGINT UNSIGNED NOT NULL PRIMARY KEY,
vtmid BIGINT UNSIGNED,
name VARCHAR(255) NOT NULL,
pres_statcd SMALLINT UNSIGNED,
udfs DECIMAL(9,3) UNSIGNED,
udfs_uomcd BIGINT UNSIGNED,
udfs_dose_uomcd BIGINT UNSIGNED
);

CREATE TABLE IF NOT EXISTS vmpform (
vmpid BIGINT UNSIGNED NOT NULL,
formid BIGINT UNSIGNED NOT NULL
);

CREATE TABLE IF NOT EXISTS vmproute (
vmpid BIGINT UNSIGNED NOT NULL,
routeid BIGINT UNSIGNED NOT NULL
);

CREATE TABLE IF NOT EXISTS vpi (
vmpid BIGINT UNSIGNED NOT NULL,
isid BIGINT UNSIGNED NOT NULL,
strnt_nmrtr_val DECIMAL(9,3) UNSIGNED,
strnt_nmrtr_uomcd BIGINT UNSIGNED,
strnt_dnmtr_val DECIMAL(9,3) UNSIGNED,
strnt_dnmtr_uomcd BIGINT UNSIGNED
);

CREATE TABLE IF NOT EXISTS amp (
ampid BIGINT UNSIGNED NOT NULL PRIMARY KEY,
vmpid BIGINT UNSIGNED,
name VARCHAR(255) NOT NULL
);

END