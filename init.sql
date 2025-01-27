CREATE table IF NOT EXISTS public.users (
    id_user SERIAL PRIMARY KEY,
    firstname varchar not null,
    lastname varchar not null,
    email varchar NOT NULL UNIQUE,
    password varchar NOT NULL,
    role int DEFAULT 2 NOT NULL
);

CREATE TABLE IF NOT EXISTS public.roles (
	id_role serial4 NOT NULL,
	name varchar NOT NULL,
	CONSTRAINT roles_pk PRIMARY KEY (id_role),
	CONSTRAINT roles_unique UNIQUE ("name")
);

INSERT INTO public.roles ("name") VALUES 
    ('manager'),
    ('employee');

CREATE TABLE public.work_sessions (
	id_session serial4 NOT NULL,
	id_user int NOT NULL,
	time_start timestamp NOT NULL,
	time_end timestamp NULL,
	status int NOT NULL,
	CONSTRAINT work_sessions_pk PRIMARY KEY (id_session)
);

CREATE TABLE public.working_status (
	id_status serial4 NOT NULL,
	"name" varchar NOT NULL,
	CONSTRAINT working_status_pk PRIMARY KEY (id_status)
);

INSERT INTO public.working_status ("name") VALUES
	 ('started'),
	 ('finished');
