ALTER TABLE `vine_cloud`.`void_transactions`  ADD `void_transactions7101` decimal(20,10) DEFAULT NULL AFTER `void_transactions119` ,  ADD `void_transactions7102` varchar(100) DEFAULT NULL AFTER `void_transactions7101` ,  ADD `void_transactions7103` decimal(20,4) DEFAULT NULL AFTER `void_transactions7102` ,  ADD `void_transactions7104` varchar(100) DEFAULT NULL AFTER `void_transactions115` ; 
ALTER TABLE `vine_cloud`.`void_transactions` ADD INDEX(`void_transactions7102`) ; 
ALTER TABLE `vine_cloud`.`void_transactions` ADD INDEX(`void_transactions7104`) ; 
