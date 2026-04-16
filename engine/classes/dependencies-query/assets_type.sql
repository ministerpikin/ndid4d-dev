 CREATE TABLE `assets_type` ( `id` varchar(55) NOT NULL , `assets_type7197` varchar(200) DEFAULT NULL , `assets_type7198` varchar(100) DEFAULT NULL , `assets_type7199` varchar(100) DEFAULT NULL , `assets_type7200` varchar(100) DEFAULT NULL , `assets_type7201` decimal(20,4) DEFAULT NULL , `assets_type7202` decimal(20,4) DEFAULT NULL , `assets_type7203` decimal(20,4) DEFAULT NULL , `assets_type7204` varchar(100) DEFAULT NULL , `assets_type7205` text DEFAULT NULL , `serial_num` int(11) NOT NULL, `creator_role` varchar(100) DEFAULT NULL, `created_source` varchar(100) DEFAULT NULL, `created_by` varchar(100) DEFAULT NULL, `creation_date` int(11) DEFAULT NULL, `modified_source` varchar(100) DEFAULT NULL, `modified_by` varchar(100) DEFAULT NULL, `modification_date` int(11) DEFAULT NULL, `ip_address` varchar(100) DEFAULT NULL, `device_id` text NULL, `record_status` varchar(100) DEFAULT NULL ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ; 
ALTER TABLE `assets_type` ADD PRIMARY KEY (`serial_num`), ADD UNIQUE KEY `id` (`id`) ; 
ALTER TABLE `assets_type` MODIFY `serial_num` int(11) NOT NULL AUTO_INCREMENT ; 
ALTER TABLE `assets_type` ADD INDEX(`assets_type7198`) ; 
ALTER TABLE `assets_type` ADD INDEX(`assets_type7199`) ; 
ALTER TABLE `assets_type` ADD INDEX(`assets_type7200`) ; 
ALTER TABLE `assets_type` ADD INDEX(`assets_type7204`) ; 
