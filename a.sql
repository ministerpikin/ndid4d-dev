-- 07 - Aug - 2023
CREATE TABLE `endpoint_log` ( `id` varchar(33) NOT NULL ,  `endpoint_log6999` varchar(200) DEFAULT NULL ,  `endpoint_log7000` varchar(200) DEFAULT NULL ,  `endpoint_log7001` varchar(100) DEFAULT NULL ,  `endpoint_log7002` varchar(200) DEFAULT NULL ,  `endpoint_log7003` mediumtext DEFAULT NULL ,  `endpoint_log7008` mediumtext DEFAULT NULL ,  `endpoint_log7004` varchar(200) DEFAULT NULL ,  `endpoint_log7005` varchar(200) DEFAULT NULL ,  `endpoint_log7006` varchar(200) DEFAULT NULL ,  `endpoint_log7007` text DEFAULT NULL , `serial_num` int(11) NOT NULL, `creator_role` varchar(100) DEFAULT NULL, `created_source` varchar(100) DEFAULT NULL, `created_by` varchar(100) DEFAULT NULL, `creation_date` int(11) DEFAULT NULL, `modified_source` varchar(100) DEFAULT NULL, `modified_by` varchar(100) DEFAULT NULL, `modification_date` int(11) DEFAULT NULL, `ip_address` varchar(100) DEFAULT NULL, `device_id` text NOT NULL, `record_status` varchar(100) DEFAULT NULL ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ; 
ALTER TABLE `endpoint_log` ADD PRIMARY KEY (`serial_num`), ADD UNIQUE KEY `id` (`id`) ; 
ALTER TABLE `endpoint_log` MODIFY `serial_num` int(11) NOT NULL AUTO_INCREMENT ; 
ALTER TABLE `endpoint_log` ADD INDEX(`endpoint_log7001`) ; 


-- 01 - September 2023
CREATE TABLE `audit_trail` ( `id` varchar(33) NOT NULL, `audit_trail7003` int(11) DEFAULT NULL, `audit_trail6999` varchar(200) DEFAULT NULL, `audit_trail7000` varchar(200) DEFAULT NULL, `audit_trail7002` varchar(200) DEFAULT NULL, `audit_trail7001` varchar(200) DEFAULT NULL, `audit_trail7004` mediumtext DEFAULT NULL, `audit_trail7005` mediumtext DEFAULT NULL, `serial_num` int(11) NOT NULL, `creator_role` varchar(100) DEFAULT NULL, `created_source` varchar(100) DEFAULT NULL, `created_by` varchar(100) DEFAULT NULL, `creation_date` int(11) DEFAULT NULL, `modified_source` varchar(100) DEFAULT NULL, `modified_by` varchar(100) DEFAULT NULL, `modification_date` int(11) DEFAULT NULL, `ip_address` varchar(100) DEFAULT NULL, `device_id` text NOT NULL, `record_status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`serial_num`),
  ADD UNIQUE KEY `id` (`id`);

ALTER TABLE `audit_trail`
  MODIFY `serial_num` int(11) NOT NULL AUTO_INCREMENT;

  -- 07 09 2023

  ALTER TABLE `endpoint`  ADD `endpoint7006` int(11) DEFAULT NULL AFTER `endpoint6991` ; 

  ALTER TABLE `endpoint_log` ADD `endpoint_log7009` varchar(200) DEFAULT NULL AFTER `endpoint_log7005`;

  -- 10 09 2023

  ALTER TABLE `endpoint_log` ADD `endpoint_log7010` varchar(200) DEFAULT NULL AFTER `endpoint_log7007`;


  -- 29 08 2023
  ALTER TABLE `report_config` MODIFY `report_config7018` varchar(4000) DEFAULT NULL;

  -- 09 10 2024
  INSERT INTO `project_settings` (`id`, `project_settings2420`, `project_settings2421`, `project_settings2422`, `project_settings2423`, `project_settings2424`, `serial_num`, `creator_role`, `created_source`, `created_by`, `creation_date`, `modified_source`, `modified_by`, `modification_date`, `ip_address`, `device_id`, `record_status`) VALUES ('audit_trail_config', 'AUDIT_TRAIL_CONFIG', '{\"rention_period\":\"0.08\",\"push_to_es\":\"on\",\"zip_path\":\"C:\\\\Users\\\\steph\\\\Downloads\\\\\",\"audit_backup\":\"on\",\"audit_backup_retention\":\"1\"}', '', '', 'audit_trail', NULL, '1300130013', 'users', '35991362173', '1728478399', 'users', '35991362173', '1728477715', '::1', '', '1');
  UPDATE `reports_bay` SET `record_status` = '0' , `modification_date` = '1728551187' , `modified_by` = '35991362173' WHERE `id` = 'ray26u3346722931' OR `id` = 'ray26u3346720981' OR `id` = 'ray20u3354563591'