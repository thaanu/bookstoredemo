select q.*, y.specialty_code, y.Speciality_name from  (
select v.person_id,  hcp_id, username, mcr, room_code, location_code, name as room_name, employee_id, enabled from (
select * from (
select * from (
select NH_hcp.hcp_id, NH_person.person_id,
NH_person.name as username, NH_hcp.mcr, NH_hcp.room_code
from tth_prod.NH_hcp
inner join TTH_PROD.NH_person on NH_person.person_id = NH_hcp.person_id  ) p
left join ( select location_code, name, parent_location_id from  tth_prod.nh_location) f on f.location_code =  p.room_code) where mcr is not null) d
left join (select employee_id, person_id, enabled from (
select employee_id, person_id  from TTH_PROD.NH_EMPLOYEE)  z  inner join (select employee_id as compid, enabled from tth_prod.nh_employee_company ) r on  z.employee_id = r.compid)  v on v.person_id = d.person_id ) q left join (
select NH_SPECIALTY.specialty_id, segment1 as Speciality_name, specialty_code, hcp_id, primary_flg from tth_prod.NH_SPECIALTY inner join 
 tth_prod.NH_doc_specialty on NH_doc_specialty.specialty_id = NH_SPECIALTY.specialty_id) y on y.hcp_id = q.hcp_id