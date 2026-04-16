ALTER TABLE `meta_data`.`reports_bay` ADD INDEX(`record_status`) ; 

ALTER TABLE `reports_bay` ADD `reports_bay7850` MEDIUMTEXT DEFAULT NULL AFTER `reports_bay7800` ;
