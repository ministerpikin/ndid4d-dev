 CREATE TABLE `meta_data2`.`enrolment_partner` ( `id` varchar(33) NOT NULL ,  `date` int(11) DEFAULT NULL ,  `name` varchar(200) DEFAULT NULL ,  `type` varchar(100) DEFAULT NULL ,  `country` varchar(100) DEFAULT NULL ,  `state` varchar(100) DEFAULT NULL ,  `lga` varchar(100) DEFAULT NULL ,  `comment` text DEFAULT NULL ,  `data` text DEFAULT NULL , `serial_num` int(11) NOT NULL, `creator_role` varchar(100) DEFAULT NULL, `created_source` varchar(100) DEFAULT NULL, `created_by` varchar(100) DEFAULT NULL, `creation_date` int(11) DEFAULT NULL, `modified_source` varchar(100) DEFAULT NULL, `modified_by` varchar(100) DEFAULT NULL, `modification_date` int(11) DEFAULT NULL, `ip_address` varchar(100) DEFAULT NULL, `device_id` text NOT NULL, `record_status` varchar(2) DEFAULT NULL ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ; 
ALTER TABLE `meta_data2`.`enrolment_partner` ADD PRIMARY KEY (`serial_num`), ADD UNIQUE KEY `id` (`id`) ; 
ALTER TABLE `meta_data2`.`enrolment_partner` MODIFY `serial_num` int(11) NOT NULL AUTO_INCREMENT ; 
ALTER TABLE `meta_data2`.`enrolment_partner` ADD INDEX(`type`) ; 
ALTER TABLE `meta_data2`.`enrolment_partner` ADD INDEX(`country`) ; 
ALTER TABLE `meta_data2`.`enrolment_partner` ADD INDEX(`state`) ; 
ALTER TABLE `meta_data2`.`enrolment_partner` ADD INDEX(`lga`) ; 
ALTER TABLE `meta_data2`.`enrolment_partner` ADD INDEX(`record_status`) ; 
