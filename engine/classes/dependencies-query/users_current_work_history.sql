 CREATE TABLE `vine_pison2`.`users_current_work_history` ( `id` varchar(33) NOT NULL , `serial_num` int(11) NOT NULL, `creator_role` varchar(200) DEFAULT NULL, `created_by` varchar(200) DEFAULT NULL, `creation_date` int(11) DEFAULT NULL, `modified_by` varchar(200) DEFAULT NULL, `modification_date` int(11) DEFAULT NULL, `ip_address` varchar(200) DEFAULT NULL, `device_id` text NOT NULL, `record_status` varchar(100) DEFAULT NULL ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ; 
ALTER TABLE `vine_pison2`.`users_current_work_history` ADD PRIMARY KEY (`serial_num`), ADD UNIQUE KEY `id` (`id`) ; 
ALTER TABLE `vine_pison2`.`users_current_work_history` MODIFY `serial_num` int(11) NOT NULL AUTO_INCREMENT ; 

ALTER TABLE `vine_pison2`.`users_current_work_history` ADD `users_current_work_history3998` TEXT DEFAULT NULL AFTER `users_current_work_history1992`


ALTER TABLE `vine_pison2`.`users_current_work_history` ADD `users_current_work_history4001` TEXT DEFAULT NULL AFTER `users_current_work_history1986`
ALTER TABLE `vine_pison2`.`users_current_work_history` ADD `users_current_work_history4002` TEXT DEFAULT NULL AFTER `users_current_work_history1984`
ALTER TABLE `vine_pison2`.`users_current_work_history` ADD `users_current_work_history4003` TEXT DEFAULT NULL AFTER `users_current_work_history4002`
ALTER TABLE `vine_pison2`.`users_current_work_history` ADD `users_current_work_history4004` TEXT DEFAULT NULL AFTER `users_current_work_history4003`
ALTER TABLE `vine_pison2`.`users_current_work_history` ADD `users_current_work_history4005` TEXT DEFAULT NULL AFTER `users_current_work_history4004`
ALTER TABLE `vine_pison2`.`users_current_work_history` ADD `users_current_work_history4006` TEXT DEFAULT NULL AFTER `users_current_work_history4005`
