 CREATE TABLE `meta_data2`.`states` ( `id` varchar(33) NOT NULL ,  `country` varchar(100) DEFAULT NULL ,  `name` varchar(200) DEFAULT NULL ,  `code` varchar(200) DEFAULT NULL ,  `geo_zone` varchar(100) DEFAULT NULL ,  `geo_ip` varchar(200) DEFAULT NULL ,  `geo_code` varchar(200) DEFAULT NULL ,  `status` varchar(100) DEFAULT NULL , `serial_num` int(11) NOT NULL, `creator_role` varchar(100) DEFAULT NULL, `created_source` varchar(100) DEFAULT NULL, `created_by` varchar(100) DEFAULT NULL, `creation_date` int(11) DEFAULT NULL, `modified_source` varchar(100) DEFAULT NULL, `modified_by` varchar(100) DEFAULT NULL, `modification_date` int(11) DEFAULT NULL, `ip_address` varchar(100) DEFAULT NULL, `device_id` text NOT NULL, `record_status` varchar(2) DEFAULT NULL ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ; 
ALTER TABLE `meta_data2`.`states` ADD PRIMARY KEY (`serial_num`), ADD UNIQUE KEY `id` (`id`) ; 
ALTER TABLE `meta_data2`.`states` MODIFY `serial_num` int(11) NOT NULL AUTO_INCREMENT ; 
ALTER TABLE `meta_data2`.`states` ADD INDEX(`country`) ; 
ALTER TABLE `meta_data2`.`states` ADD INDEX(`geo_zone`) ; 
ALTER TABLE `meta_data2`.`states` ADD INDEX(`status`) ; 
ALTER TABLE `meta_data2`.`states` ADD INDEX(`record_status`) ; 
