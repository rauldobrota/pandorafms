START TRANSACTION;

DELETE FROM tconfig WHERE `token` = 'legacy_database_ha'

COMMIT;