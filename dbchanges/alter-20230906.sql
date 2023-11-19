ALTER TABLE `tbl_doctors`
CHANGE `doctor_last_updated_on` `doctor_last_updated_on` datetime NULL DEFAULT NULL,
CHANGE `doctor_sync_on` `doctor_sync_on` datetime NULL DEFAULT NULL,
CHANGE `doctor_has_session` `doctor_has_session` tinyint(4) NULL DEFAULT NULL,
CHANGE `doctor_is_dummy` `doctor_is_dummy` tinyint(4) NULL DEFAULT NULL,
CHANGE `doctor_is_active` `doctor_is_active` tinyint(4) NULL DEFAULT NULL,
ADD COLUMN `doctor_tag_service_appt` tinyint(4) NULL AFTER `doctor_has_session`,
ADD COLUMN `doctor_has_service_appt` tinyint(4) NULL AFTER `doctor_has_session`;

ALTER TABLE `tbl_doctors`
ADD COLUMN `doctor_clinic` varchar(1000) NULL AFTER `doctor_tag_service_appt`;