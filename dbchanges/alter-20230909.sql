-- 2023-09-09 00:53:16
ALTER TABLE `tbl_appointments`
ADD COLUMN `appt_cancelled_reason` text NULL AFTER `parent_appt_id`,
ADD COLUMN `appt_sync_dt` datetime NULL AFTER `parent_appt_id`,
ADD COLUMN `appt_cancelled_by` varchar(255) NULL AFTER `parent_appt_id`;


-- 2023-09-09 00:56:17
CREATE TABLE `tbl_cronjobs` (`cronjob_id` int AUTO_INCREMENT,`cronjob_type` varchar(45),`cronjob_dt` datetime,`cronjob_run_dt` datetime,`cronjob_instructions` text, PRIMARY KEY (cronjob_id));

-- 2023-09-09 01:22:13
ALTER TABLE `tbl_appointments` ADD COLUMN `appt_room_no` varchar(45) NULL AFTER `appt_cancelled_reason`;

-- 2023-09-10 01:12:25
ALTER TABLE `tbl_appointments` ADD COLUMN `appt_desposit_col` tinyint(4) NULL AFTER `appt_room_no`;

-- 2023-09-10 01:26:25
ALTER TABLE `tbl_cronjobs` ADD COLUMN `cronjob_server_resp` text NULL AFTER `cronjob_instructions`;

-- 2023-09-10 01:46:34
ALTER TABLE `tbl_cronjobs` ADD COLUMN `cronjob_status` varchar(45) NULL AFTER `cronjob_server_resp`;