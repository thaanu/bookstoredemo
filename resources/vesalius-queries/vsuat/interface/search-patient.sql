select 
        ad.*,
        INTFO_PATIENT_BIODATA.EVENT_SEQUENCE_NO,   
            INTFO_PATIENT_BIODATA.PATIENT_PRN,     
                INTFO_PATIENT_BIODATA.PATIENT_ID_NUMBER,     
                INTFO_PATIENT_BIODATA.PATIENT_TITLE,     
                INTFO_PATIENT_BIODATA.PATIENT_GENDER,     
                INTFO_PATIENT_BIODATA.DOB,     
                TO_CHAR(INTFO_PATIENT_BIODATA.DOB, 'YYYY-MM-DD') AS DOB_FORMATTED,    
                INTFO_PATIENT_BIODATA.FIRST_NAME,     
                INTFO_PATIENT_BIODATA.MIDDLE_NAME,     
                INTFO_PATIENT_BIODATA.LAST_NAME,     
                INTFO_PATIENT_BIODATA.HOME_PHONE,     
                INTFO_PATIENT_BIODATA.NATIONALITY,     
                INTFO_PATIENT_BIODATA.RELIGION,     
                INTFO_PATIENT_BIODATA.MARRITAL_STATUS,     
                INTFO_PATIENT_BIODATA.CHARGE_CATEGORY,     
                INTFO_PATIENT_BIODATA.PAYMENT_CLASS,     
                INTFO_PATIENT_BIODATA.WEIGHT,     
                INTFO_PATIENT_BIODATA.HEIGHT,     
                INTFO_PATIENT_BIODATA.BMI,     
                INTFO_PATIENT_BIODATA.EMAIL_CONTACT from (  
                    select to_number(event_sequence_no) as event_no,  prn, account_number , event_name, process_Date_time  FROM intfo_events   
                    where  event_type = 'ADT' and ( event_name = 'E1') and (processed_flg = 'Y') ) ad left join   
                    INTFO_PATIENT_BIODATA on INTFO_PATIENT_BIODATA.event_sequence_no = ad.event_no where PATIENT_ID_NUMBER = '[::SEARCH_QUERY::]' or prn = '[::SEARCH_QUERY::]' order by event_no desc FETCH FIRST 1 ROWS ONLY