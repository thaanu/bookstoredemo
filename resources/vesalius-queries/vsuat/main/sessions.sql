SELECT
    cal_day,
    cal_date,
    username AS doctor_name,
    mcr,
    start_time,
    end_time,
    clinic_name,
    max_appt_count
FROM
    (
        SELECT
            *
        FROM
            (
                SELECT
                    cal_day,
                    cal_date,
                    room_code,
                    username,
                    mcr,
                    max_appt_count,
                    to_char(c.start_time, 'HH24:MI:SS') AS start_time,
                    to_char(c.end_time, 'HH24:MI:SS')   AS end_time
                FROM
                    (
                        SELECT
                            *
                        FROM
                            tth_uat.nh_rsc_schedule_det
                        WHERE
                                cal_date = '[::DATE::]'
                            AND inactive_flg <> 'Y'
                    ) c
                    LEFT JOIN (
                        SELECT
                            nh_hcp.hcp_id,
                            nh_person.person_id,
                            nh_person.name AS username,
                            nh_hcp.mcr
                        FROM
                                 tth_uat.nh_hcp
                            INNER JOIN tth_uat.nh_person ON nh_person.person_id = nh_hcp.person_id
                    ) x ON c.hcp_id = x.hcp_id
            ) p
            LEFT JOIN (
                SELECT
                    location_code,
                    name,
                    parent_location_id
                FROM
                    tth_uat.nh_location
            ) f ON f.location_code = p.room_code
    ) r
    LEFT JOIN (
        SELECT
            location_id,
            location_code,
            name AS clinic_name,
            parent_location_id
        FROM
            tth_uat.nh_location
    ) s ON s.location_id = r.parent_location_id
    WHERE mcr = '[::MCR::]'