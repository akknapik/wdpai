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


create trigger trigger_update_manager_confirmation_date before
update
    on
    public.leaves for each row
    when ((old.status is distinct
from
    new.status)) execute function update_manager_confirmation_date();

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