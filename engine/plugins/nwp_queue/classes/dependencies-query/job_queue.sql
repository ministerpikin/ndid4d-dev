ALTER TABLE `job_queue`  ADD `job_queue7014` varchar(200) DEFAULT NULL AFTER `job_queue7010` ; 
ALTER TABLE `job_queue`  ADD `job_queue7012` varchar(200) DEFAULT NULL AFTER `job_queue7011` ; 
ALTER TABLE `job_queue`  ADD `job_queue7015` varchar(200) DEFAULT NULL AFTER `job_queue7012` ; 
ALTER TABLE `job_queue`  ADD `job_queue7013` varchar(200) DEFAULT NULL AFTER `job_queue7015` ; 
ALTER TABLE `job_queue`  ADD `job_queue7016` varchar(200) DEFAULT NULL AFTER `job_queue7013` ; 
ALTER TABLE `vine_pison2`.`job_queue` ADD INDEX(`record_status`) ; 
