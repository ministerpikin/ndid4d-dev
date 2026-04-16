 CREATE TABLE `meta_data2`.`countries` ( `id` varchar(33) NOT NULL ,  `name` varchar(200) DEFAULT NULL ,  `code` varchar(200) DEFAULT NULL ,  `usd_exchange_rate` decimal(20,4) DEFAULT NULL ,  `currency` varchar(200) DEFAULT NULL ,  `language` varchar(100) DEFAULT NULL ,  `currency_iso_code` varchar(100) DEFAULT NULL , `serial_num` int(11) NOT NULL, `creator_role` varchar(100) DEFAULT NULL, `created_source` varchar(100) DEFAULT NULL, `created_by` varchar(100) DEFAULT NULL, `creation_date` int(11) DEFAULT NULL, `modified_source` varchar(100) DEFAULT NULL, `modified_by` varchar(100) DEFAULT NULL, `modification_date` int(11) DEFAULT NULL, `ip_address` varchar(100) DEFAULT NULL, `device_id` text NOT NULL, `record_status` varchar(2) DEFAULT NULL ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ; 
ALTER TABLE `meta_data2`.`countries` ADD PRIMARY KEY (`serial_num`), ADD UNIQUE KEY `id` (`id`) ; 
ALTER TABLE `meta_data2`.`countries` MODIFY `serial_num` int(11) NOT NULL AUTO_INCREMENT ; 
ALTER TABLE `meta_data2`.`countries` ADD INDEX(`language`) ; 
ALTER TABLE `meta_data2`.`countries` ADD INDEX(`currency_iso_code`) ; 
ALTER TABLE `meta_data2`.`countries` ADD INDEX(`record_status`) ; 
