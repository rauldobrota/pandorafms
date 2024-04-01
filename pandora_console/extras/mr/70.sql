START TRANSACTION;

SET @exist = (SELECT count(*) FROM information_schema.columns WHERE TABLE_NAME='tmensajes' AND COLUMN_NAME='id_usuario_destino' AND table_schema = DATABASE());
SET @sqlstmt = IF (@exist>0, 'ALTER TABLE `tmensajes` DROP COLUMN `id_usuario_destino`', 'SELECT ""');
prepare stmt from @sqlstmt;
execute stmt;

COMMIT;
