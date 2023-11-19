-- 2023-09-12 13:16:08
ALTER TABLE `tbl_appointments` CHANGE `appt_desposit_col` `appt_deposit_col` tinyint(4) NULL DEFAULT NULL;

-- 2023-09-12 13:26:51
ALTER TABLE `tbl_appointments` ADD COLUMN `appt_deposit_amount` decimal(10,2) NULL AFTER `appt_deposit_col`;