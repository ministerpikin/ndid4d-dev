ALTER TABLE `meta_data`.`es_monitoring`  ADD `es_monitoring7441` varchar(100) DEFAULT NULL AFTER `es_monitoring7440` ; 
ALTER TABLE `meta_data`.`es_monitoring` ADD INDEX(`es_monitoring7441`) ; 
ALTER TABLE `meta_data`.`es_monitoring` ADD INDEX(`record_status`) ; 
