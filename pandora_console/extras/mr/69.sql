START TRANSACTION;

-- Add new columns in tdeployment_hosts
ALTER TABLE `tdeployment_hosts` ADD COLUMN `deploy_method` ENUM('SSH', 'HTTP', 'HTTPS') DEFAULT 'SSH';
ALTER TABLE `tdeployment_hosts` ADD COLUMN `deploy_port` INT UNSIGNED NOT NULL DEFAULT 22;
ALTER TABLE `tdeployment_hosts` ADD COLUMN `server_port` INT UNSIGNED NOT NULL DEFAULT 41121;
ALTER TABLE `tdeployment_hosts` ADD COLUMN `temp_folder` VARCHAR(500) DEFAULT '/tmp';

UPDATE
    `tdeployment_hosts`, `tconfig_os`
SET
    `tdeployment_hosts`.`deploy_method` = 'HTTP',
    `tdeployment_hosts`.`deploy_port` = 5985,
    `tdeployment_hosts`.`temp_folder` = 'C:&#92;Widnows&#92;Temp'
WHERE
    `tdeployment_hosts`.`id_os` = `tconfig_os`.`id_os` AND `tconfig_os`.`name` = 'Windows' AND `tdeployment_hosts`.`deployed` = 0;

-- Find the name of the foreign key constraint
SELECT @constraint_name := `constraint_name`
FROM `information_schema`.`key_column_usage`
WHERE `table_name` = 'tdeployment_hosts' AND `column_name` = 'id_os';

-- Drop the foreign key constraint using dynamic SQL
SET @drop_fk_query = CONCAT('ALTER TABLE `tdeployment_hosts` DROP FOREIGN KEY ', @constraint_name);
PREPARE stmt FROM @drop_fk_query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Drop unused columns in tdeployment_hosts
ALTER TABLE `tdeployment_hosts` DROP COLUMN `id_os`;
ALTER TABLE `tdeployment_hosts` DROP COLUMN `os_version`;
ALTER TABLE `tdeployment_hosts` DROP COLUMN `arch`;

-- Update all deployment recon tasks port
UPDATE `trecon_task` SET `field4` = 41121 WHERE `type` = 9;

COMMIT;