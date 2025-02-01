CREATE TABLE IF NOT EXISTS public.roles (
	id_role serial4 NOT NULL,
	name varchar NOT NULL,
	CONSTRAINT roles_pk PRIMARY KEY (id_role),
	CONSTRAINT roles_unique UNIQUE ("name")
);

INSERT INTO public.roles ("name") VALUES 
    ('manager'),
    ('employee');

CREATE TABLE public.users (
	id_user serial4 NOT NULL,
	firstname varchar NOT NULL,
	lastname varchar NOT NULL,
	email varchar NOT NULL,
	"password" varchar NOT NULL,
	"role" int4 DEFAULT 2 NOT NULL,
	CONSTRAINT users_email_key UNIQUE (email),
	CONSTRAINT users_pkey PRIMARY KEY (id_user),
	CONSTRAINT users_roles_fk FOREIGN KEY ("role") REFERENCES public.roles(id_role)
);

INSERT INTO public.users (firstname,lastname,email,"password","role") VALUES
	 ('Dariusz','Kowalczyk','dkowalczyk@gmail.com','$2y$10$T8J4xhAg5MjqABBeFK/i8e.6Gexeda8b3fDHboGVp6CAmtzEAH4y6',1),
	 ('Piotr','Chmiel','pchmiel@gmail.com','$2y$10$N6Ax5Pfe6SPyQ3/zYE4NZORUH9bW9VFXo4SjrEVe4PGCZZk2zV0vi',2),
	 ('Magdalena','Nowak','mnowak@gmail.com','$2y$10$64hYBvCwTOpqtp/BB6rcKu.SWR3.XWepkzpKT5YOx/uC8koY00l5O',2),
	 ('Paweł','Figus','pfigus@gmail.com','$2y$10$kjRGHGZt7fbdCNrg7h21uu/7IT5UsyeIo79KPqgK0ywLiSr/gSgCy',2),
	 ('Izabela','Opał','iopal@gmail.com','$2y$10$15GrTesDX8PD6pfLv3HG0.XyIwEiXX7iQuJe7EbAyMUXgCeDPZhx.',2),
	 ('Mariusz','Turek','mturek@gmail.com','$2y$10$wN9yDIXDEt4o/jXADrTQTes45QUsO/F8Mxg1WC22Qr5kL4i9lcflO',2),
	 ('Anna','Watorska','awatorska@gmail.com','$2y$10$grw6YdztpGcpX0T6TATtouhJxM74a4LBnYpvE9kQ2/m/OKCC3QYKC',2),
	 ('Adam','Słowak','aslowak@gmail.com','$2y$10$ifHr62SsBf7VcYIvni3RsuE.fhHpuFRLWAd9YHWMJlIRZj.xqAuXS',2),
	 ('Monika','Grabowska','mgrabowska@gmail.com','$2y$10$KEJK41uGzu7CgBlWhCosW.U38QWE/zTg7hugPWU6SAyxRam9txyrG',2);


CREATE TABLE public.working_status (
	id_status serial4 NOT NULL,
	"name" varchar NOT NULL,
	CONSTRAINT working_status_pk PRIMARY KEY (id_status)
);

INSERT INTO public.working_status ("name") VALUES
	 ('started'),
	 ('finished');

CREATE TABLE public.work_sessions (
	id_session serial4 NOT NULL,
	id_user int4 NOT NULL,
	time_start timestamp NOT NULL,
	time_end timestamp NULL,
	status int4 NOT NULL,
	CONSTRAINT work_sessions_pk PRIMARY KEY (id_session),
	CONSTRAINT work_sessions_users_fk FOREIGN KEY (id_user) REFERENCES public.users(id_user),
	CONSTRAINT work_sessions_working_status_fk FOREIGN KEY (status) REFERENCES public.working_status(id_status)
);

INSERT INTO public.work_sessions (id_user,time_start,time_end,status) VALUES
	 (9,'2025-01-31 14:07:40.314','2025-01-31 17:09:28.875',2),
	 (9,'2025-01-30 09:07:40.314','2025-01-30 17:09:28.875',2),
	 (1,'2025-01-30 09:07:40.314','2025-01-30 17:09:28.875',2),
	 (1,'2025-02-01 17:12:56.010282','2025-02-01 17:13:30.171793',2),
	 (1,'2025-01-30 09:07:40.314','2025-01-30 17:09:28.875',2),
	 (2,'2025-01-31 09:07:40.314','2025-01-31 17:09:28.875',2),
	 (3,'2025-01-30 09:07:40.314','2025-01-30 17:09:28.875',2),
	 (4,'2025-01-30 09:07:40.314','2025-01-30 17:09:28.875',2),
	 (5,'2025-01-30 09:07:40.314','2025-01-30 17:09:28.875',2),
	 (6,'2025-01-30 09:07:40.314','2025-01-30 17:09:28.875',2);
INSERT INTO public.work_sessions (id_user,time_start,time_end,status) VALUES
	 (7,'2025-01-30 09:07:40.314','2025-01-30 17:09:28.875',2),
	 (8,'2025-01-30 09:07:40.314','2025-01-30 17:09:28.875',2),
	 (9,'2025-01-30 09:07:40.314','2025-01-30 17:09:28.875',2),
	 (1,'2025-02-01 09:07:40.314','2025-02-01 17:09:28.875',2),
	 (9,'2025-02-01 17:09:57.636472','2025-02-01 18:17:00',1),
	 (1,'2025-02-01 17:17:42.994843','2025-02-01 17:18:46.815424',2);

CREATE TABLE public.leave_type (
	id_leave_type serial4 NOT NULL,
	"name" varchar NOT NULL,
	CONSTRAINT leave_type_pk PRIMARY KEY (id_leave_type),
	CONSTRAINT leave_type_unique UNIQUE ("name")
);

INSERT INTO public.leave_type ("name") VALUES
	 ('Vacation leave'),
	 ('Leave on demand'),
	 ('Unpaid leave'),
	 ('Special leave'),
	 ('Parental leave'),
	 ('Sick leave'),
	 ('Child care leave');


CREATE TABLE public.leave_status (
	id_status serial4 NOT NULL,
	"name" varchar NOT NULL,
	CONSTRAINT leave_status_type_pk PRIMARY KEY (id_status),
	CONSTRAINT leave_status_unique UNIQUE ("name")
);

INSERT INTO public.leave_status ("name") VALUES
	 ('pending'),
	 ('approved'),
	 ('rejected');


CREATE TABLE public.leaves (
	id_leave serial4 NOT NULL,
	id_user int4 NOT NULL,
	leave_type int4 NOT NULL,
	date_start date NOT NULL,
	date_end date NOT NULL,
	reason varchar NULL,
	additional_notes varchar NULL,
	status int4 DEFAULT 1 NOT NULL,
	manager_info varchar NULL,
	date_of_manager_confirmation timestamp NULL,
	CONSTRAINT leaves_pk PRIMARY KEY (id_leave),
	CONSTRAINT leaves_leave_status_fk FOREIGN KEY (status) REFERENCES public.leave_status(id_status),
	CONSTRAINT leaves_leave_type_fk FOREIGN KEY (leave_type) REFERENCES public.leave_type(id_leave_type),
	CONSTRAINT leaves_users_fk FOREIGN KEY (id_user) REFERENCES public.users(id_user)
);

INSERT INTO public.leaves (id_user,leave_type,date_start,date_end,reason,additional_notes,status,manager_info,date_of_manager_confirmation) VALUES
	 (1,1,'2025-02-16','2025-02-20','Family trip',NULL,1,NULL,NULL),
	 (1,2,'2025-03-05','2025-03-10','Need personal time','Will be away from the city',1,NULL,NULL),
	 (1,6,'2025-03-15','2025-03-16','Flu symptoms',NULL,1,NULL,NULL),
	 (1,3,'2025-01-10','2025-01-12','Family obligations','Short notice leave',2,'Approved by Manager Dariusz',NULL),
	 (1,7,'2025-01-22','2025-01-24','Taking care of sick child',NULL,2,'Approved by Manager Dariusz',NULL),
	 (1,4,'2025-01-28','2025-01-30','Personal event','Request was not justified',3,'Rejected by Manager Dariusz',NULL),
	 (2,1,'2025-02-17','2025-02-19','Weekend trip',NULL,1,NULL,NULL),
	 (2,2,'2025-03-02','2025-03-03','Family function','Need a quick leave',1,NULL,NULL),
	 (2,6,'2025-03-20','2025-03-22','Feeling unwell',NULL,1,NULL,NULL),
	 (2,5,'2025-01-05','2025-01-10','New baby arrival','Extended time needed',2,'Approved by Manager Dariusz',NULL);
INSERT INTO public.leaves (id_user,leave_type,date_start,date_end,reason,additional_notes,status,manager_info,date_of_manager_confirmation) VALUES
	 (2,3,'2025-01-18','2025-01-19','Financial constraints',NULL,2,'Approved by Manager Dariusz',NULL),
	 (2,7,'2025-01-25','2025-01-27','Child’s school event','Event got canceled eventually',3,'Rejected by Manager Dariusz',NULL),
	 (3,2,'2025-02-16','2025-02-17','Urgent personal tasks',NULL,1,NULL,NULL),
	 (3,1,'2025-03-06','2025-03-08','Short vacation','Visiting parents',1,NULL,NULL),
	 (3,4,'2025-03-18','2025-03-19','Attending a ceremony',NULL,1,NULL,NULL),
	 (3,6,'2025-01-15','2025-01-16','High fever','Medical certificate provided',2,'Approved by Manager Dariusz',NULL),
	 (3,2,'2025-01-20','2025-01-21','Personal errands','Urgent tasks',2,'Approved by Manager Dariusz',NULL),
	 (3,5,'2025-01-28','2025-02-01','Parental duties','Needed for childcare',3,'Rejected by Manager Dariusz',NULL),
	 (4,7,'2025-02-20','2025-02-22','Looking after child',NULL,1,NULL,NULL),
	 (4,3,'2025-03-10','2025-03-12','Financial issues at home','Need unpaid days',1,NULL,NULL);
INSERT INTO public.leaves (id_user,leave_type,date_start,date_end,reason,additional_notes,status,manager_info,date_of_manager_confirmation) VALUES
	 (4,1,'2025-03-15','2025-03-18','Visiting family',NULL,1,NULL,NULL),
	 (4,2,'2025-01-08','2025-01-09','Immediate personal matter','Short trip',2,'Approved by Manager Dariusz',NULL),
	 (4,6,'2025-01-20','2025-01-22','Migraine attacks','Doctor advised rest',2,'Approved by Manager Dariusz',NULL),
	 (4,4,'2025-01-25','2025-01-26','Family celebration',NULL,3,'Rejected by Manager Dariusz',NULL),
	 (5,5,'2025-02-16','2025-02-18','Taking care of newborn',NULL,1,NULL,NULL),
	 (5,4,'2025-03-01','2025-03-02','Special event','Wedding in the family',1,NULL,NULL),
	 (5,6,'2025-03-10','2025-03-13','Medical rest',NULL,1,NULL,NULL),
	 (5,2,'2025-01-12','2025-01-13','Emergency at home',NULL,2,'Approved by Manager Dariusz',NULL),
	 (5,1,'2025-01-15','2025-01-20','Vacation plan','Honeymoon trip',2,'Approved by Manager Dariusz',NULL),
	 (5,7,'2025-01-25','2025-01-28','Child’s sports event','Did not provide enough details',3,'Rejected by Manager Dariusz',NULL);
INSERT INTO public.leaves (id_user,leave_type,date_start,date_end,reason,additional_notes,status,manager_info,date_of_manager_confirmation) VALUES
	 (6,1,'2025-02-20','2025-02-22','Tour with friends',NULL,1,NULL,NULL),
	 (6,2,'2025-03-05','2025-03-07','Urgent personal errands','Relocation tasks',1,NULL,NULL),
	 (6,3,'2025-03-15','2025-03-18','Unpaid holiday extension',NULL,1,NULL,NULL),
	 (6,6,'2025-01-10','2025-01-12','Severe cold','Consulting doctor',2,'Approved by Manager Dariusz',NULL),
	 (6,5,'2025-01-18','2025-01-22','Parental duties at home','Busy schedule',2,'Approved by Manager Dariusz',NULL),
	 (6,4,'2025-01-25','2025-01-26','Attending workshop','Company policy not matched',3,'Rejected by Manager Dariusz',NULL),
	 (7,6,'2025-03-02','2025-03-05','Medical leave','Doctor recommended rest',1,NULL,NULL),
	 (7,5,'2025-03-10','2025-03-13','Newborn care',NULL,1,NULL,NULL),
	 (7,4,'2025-01-12','2025-01-14','Special family event','Sister’s wedding',2,'Approved by Manager Dariusz',NULL),
	 (7,3,'2025-01-20','2025-01-21','Unpaid request','Request accepted quickly',2,'Approved by Manager Dariusz',NULL);
INSERT INTO public.leaves (id_user,leave_type,date_start,date_end,reason,additional_notes,status,manager_info,date_of_manager_confirmation) VALUES
	 (7,1,'2025-01-25','2025-01-28','Vacation to countryside','Insufficient reason provided',3,'Rejected by Manager Dariusz',NULL),
	 (8,2,'2025-03-15','2025-03-16','Personal emergency',NULL,1,NULL,NULL),
	 (8,4,'2025-01-10','2025-01-11','Special conference','Company policy supported',2,'Approved by Manager Dariusz',NULL),
	 (8,1,'2025-01-18','2025-01-20','Vacation plan','Honeymoon trip',2,'Approved by Manager Dariusz',NULL),
	 (8,5,'2025-01-25','2025-01-28','Parental responsibilities','No manager approval initially',3,'Rejected by Manager Dariusz',NULL),
	 (9,3,'2025-02-16','2025-02-18','Unpaid trip extension',NULL,1,NULL,NULL),
	 (9,4,'2025-03-02','2025-03-03','Special occasion','Need a short break',1,NULL,NULL),
	 (9,1,'2025-03-10','2025-03-12','Family vacation',NULL,1,NULL,NULL),
	 (9,2,'2025-01-08','2025-01-09','On-demand leave needed','Manager was informed in advance',2,'Approved by Manager Dariusz',NULL),
	 (9,6,'2025-01-15','2025-01-17','Bad headache','Medical certificate provided',2,'Approved by Manager Dariusz',NULL);
INSERT INTO public.leaves (id_user,leave_type,date_start,date_end,reason,additional_notes,status,manager_info,date_of_manager_confirmation) VALUES
	 (9,7,'2025-01-25','2025-01-27','Child’s special event','Insufficient documentation',3,'Rejected by Manager Dariusz',NULL);


CREATE OR REPLACE FUNCTION public.get_current_session_work_time(user_id_param integer)
 RETURNS integer
 LANGUAGE plpgsql
AS $function$
DECLARE
    current_minutes INT;
BEGIN
    SELECT CASE
               WHEN time_end IS NULL THEN
                   EXTRACT(EPOCH FROM (NOW() - time_start)) / 60
               ELSE
                   NULL
           END
    INTO current_minutes
    FROM work_sessions
    WHERE id_user = user_id_param
    ORDER BY time_start DESC
    LIMIT 1; 

    RETURN current_minutes::INT; 
END;
$function$
;

CREATE OR REPLACE FUNCTION public.get_daily_work_time(id_user_param integer)
 RETURNS integer
 LANGUAGE plpgsql
AS $function$
DECLARE
    total_minutes INT := 0; 
BEGIN
    SELECT COALESCE(SUM(EXTRACT(EPOCH FROM (time_end - time_start)) / 60), 0)
    INTO total_minutes
    FROM work_sessions
    WHERE id_user = id_user_param
      AND DATE(time_start) = CURRENT_DATE
      AND status = 2;

    IF EXISTS (
        SELECT 1
        FROM work_sessions
        WHERE id_user = id_user_param
          AND status = 1
          AND DATE(time_start) = CURRENT_DATE
    ) THEN
        SELECT total_minutes + (EXTRACT(EPOCH FROM (NOW() - time_start)) / 60)
        INTO total_minutes
        FROM work_sessions
        WHERE id_user = id_user_param
          AND status = 1 
          AND DATE(time_start) = CURRENT_DATE;
    END IF;

    RETURN total_minutes::INT; 
END;
$function$
;

CREATE OR REPLACE FUNCTION public.get_weekly_work_time(id_user_param integer)
 RETURNS integer
 LANGUAGE plpgsql
AS $function$
DECLARE
    total_minutes INT := 0; 
BEGIN
    SELECT COALESCE(SUM(EXTRACT(EPOCH FROM (time_end - time_start)) / 60), 0)
    INTO total_minutes
    FROM work_sessions
    WHERE id_user = id_user_param
      AND DATE(time_start) >= DATE_TRUNC('week', CURRENT_DATE)
      AND status = 2;

    IF EXISTS (
        SELECT 1
        FROM work_sessions
        WHERE id_user = id_user_param
          AND status = 1
          AND DATE(time_start) >= DATE_TRUNC('week', CURRENT_DATE)
    ) THEN
        SELECT total_minutes + (EXTRACT(EPOCH FROM (NOW() - time_start)) / 60)
        INTO total_minutes
        FROM work_sessions
        WHERE id_user = id_user_param
          AND status = 1
          AND DATE(time_start) >= DATE_TRUNC('week', CURRENT_DATE);
    END IF;

    RETURN total_minutes::INT;
END;
$function$
;

CREATE OR REPLACE FUNCTION public.get_monthly_work_time(id_user_param integer)
 RETURNS integer
 LANGUAGE plpgsql
AS $function$
DECLARE
    total_minutes INT := 0; 
BEGIN
    SELECT COALESCE(SUM(EXTRACT(EPOCH FROM (time_end - time_start)) / 60), 0)
    INTO total_minutes
    FROM work_sessions
    WHERE id_user = id_user_param
      AND DATE(time_start) >= DATE_TRUNC('month', CURRENT_DATE)
      AND status = 2;

    IF EXISTS (
        SELECT 1
        FROM work_sessions
        WHERE id_user = id_user_param
          AND status = 1
          AND DATE(time_start) >= DATE_TRUNC('month', CURRENT_DATE)
    ) THEN
        SELECT total_minutes + (EXTRACT(EPOCH FROM (NOW() - time_start)) / 60)
        INTO total_minutes
        FROM work_sessions
        WHERE id_user = id_user_param
          AND status = 1
          AND DATE(time_start) >= DATE_TRUNC('month', CURRENT_DATE);
    END IF;

    RETURN total_minutes::INT;
END;
$function$
;

CREATE OR REPLACE FUNCTION public.get_yearly_work_time(id_user_param integer)
 RETURNS integer
 LANGUAGE plpgsql
AS $function$
DECLARE
    total_minutes INT := 0; 
BEGIN
    SELECT COALESCE(SUM(EXTRACT(EPOCH FROM (time_end - time_start)) / 60), 0)
    INTO total_minutes
    FROM work_sessions
    WHERE id_user = id_user_param
      AND DATE(time_start) >= DATE_TRUNC('year', CURRENT_DATE)
      AND status = 2;

    IF EXISTS (
        SELECT 1
        FROM work_sessions
        WHERE id_user = id_user_param
          AND status = 1
          AND DATE(time_start) >= DATE_TRUNC('year', CURRENT_DATE)
    ) THEN
        SELECT total_minutes + (EXTRACT(EPOCH FROM (NOW() - time_start)) / 60)
        INTO total_minutes
        FROM work_sessions
        WHERE id_user = id_user_param
          AND status = 1
          AND DATE(time_start) >= DATE_TRUNC('year', CURRENT_DATE);
    END IF;

    RETURN total_minutes::INT;
END;
$function$
;

CREATE OR REPLACE VIEW public.vw_employees AS
SELECT 
    u.id_user,
    u.firstname,
    u.lastname,
    u.role,
    COALESCE(COUNT(l.id_leave), 0) AS pending_leaves
FROM users u
LEFT JOIN leaves l ON l.id_user = u.id_user AND l.status = 1
GROUP BY u.id_user, u.firstname, u.lastname, u.role;

CREATE OR REPLACE VIEW vw_user_info AS
SELECT
  u.id_user,
  u.firstname,
  u.lastname,
  u.email,
  r.name AS role_name
FROM users u
JOIN roles r ON u.role = r.id_role;

CREATE OR REPLACE FUNCTION update_manager_confirmation_date()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.status IS DISTINCT FROM OLD.status THEN
        NEW.date_of_manager_confirmation = NOW(); 
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_update_manager_confirmation_date
BEFORE UPDATE ON public.leaves
FOR EACH ROW
WHEN (OLD.status IS DISTINCT FROM NEW.status)
EXECUTE FUNCTION update_manager_confirmation_date();