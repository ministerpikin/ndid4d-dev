ALTER TABLE `vine_pison2`.`debit_and_credit`  ADD `debit_and_credit7170` varchar(100) DEFAULT NULL AFTER `debit_and_credit019` ; 
ALTER TABLE `vine_pison2`.`debit_and_credit`  DROP `debit_and_credit016` ,  DROP `debit_and_credit020` ,  DROP `debit_and_credit021` ,  DROP `debit_and_credit022` ,  DROP `debit_and_credit023` ,  DROP `debit_and_credit024` ; 
ALTER TABLE `vine_pison2`.`debit_and_credit` ADD INDEX(`debit_and_credit7170`) ; 
