START TRANSACTION;

ALTER TABLE `tdeployment_hosts` ADD COLUMN `deploy_method` ENUM('SSH', 'HTTP', 'HTTPS') DEFAULT 'SSH';
ALTER TABLE `tdeployment_hosts` ADD COLUMN `deploy_port` INT UNSIGNED NOT NULL DEFAULT 22;
ALTER TABLE `tdeployment_hosts` ADD COLUMN `server_port` INT UNSIGNED NOT NULL DEFAULT 41121;
ALTER TABLE `tdeployment_hosts` ADD COLUMN `temp_folder` VARCHAR(500) DEFAULT '/tmp';

UPDATE
    `tdeployment_hosts`, `tconfig_os`
SET
    `tdeployment_hosts`.`deploy_method` = 'HTTP',
    `tdeployment_hosts`.`deploy_port` = 5985,
    `tdeployment_hosts`.`temp_folder` = '$env:TEMP'
WHERE
    `tdeployment_hosts`.`id_os` = `tconfig_os`.`id_os` AND `tconfig_os`.`name` = 'Windows' AND `tdeployment_hosts`.`deployed` = 0;

UPDATE `trecon_task` SET `field4` = 41121 WHERE `type` = 9;

COMMIT;