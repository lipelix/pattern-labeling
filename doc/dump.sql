--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: data_id; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE data_id
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE data_id OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: data; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE data (
    id integer DEFAULT nextval('data_id'::regclass) NOT NULL,
    content bytea,
    created_at timestamp without time zone DEFAULT now()
);


ALTER TABLE data OWNER TO postgres;

--
-- Name: data_users_id; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE data_users_id
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE data_users_id OWNER TO postgres;

--
-- Name: data_users; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE data_users (
    id integer DEFAULT nextval('data_users_id'::regclass) NOT NULL,
    data_id integer NOT NULL,
    user_id integer,
    polygon character varying NOT NULL,
    marked_data bytea NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE data_users OWNER TO postgres;

--
-- Name: groups_id; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE groups_id
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE groups_id OWNER TO postgres;

--
-- Name: tags_id; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tags_id
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tags_id OWNER TO postgres;

--
-- Name: tags; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tags (
    id integer DEFAULT nextval('tags_id'::regclass) NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE tags OWNER TO postgres;

--
-- Name: tags_data_id; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tags_data_id
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tags_data_id OWNER TO postgres;

--
-- Name: tags_data; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tags_data (
    id integer DEFAULT nextval('tags_data_id'::regclass) NOT NULL,
    tag_id integer NOT NULL,
    data_id integer NOT NULL
);


ALTER TABLE tags_data OWNER TO postgres;

--
-- Name: users_id; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE users_id
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE users_id OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE users (
    id integer DEFAULT nextval('users_id'::regclass) NOT NULL,
    login character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    email character varying(255),
    first_name character varying(255),
    last_name character varying(255),
    gender character varying(1),
    age smallint,
    role character varying(40),
    groups character varying(255)
);


ALTER TABLE users OWNER TO postgres;

--
-- Data for Name: data; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Name: data_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('data_id', 5, true);


--
-- Data for Name: data_users; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Name: data_users_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('data_users_id', 13, true);


--
-- Name: groups_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('groups_id', 0, false);


--
-- Data for Name: tags; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: tags_data; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Name: tags_data_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tags_data_id', 7, true);


--
-- Name: tags_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tags_id', 4, true);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO users VALUES (101, 'admin', '$2y$10$O/Mukq86UyH/0BfPmj3X5.evPAx2yWWT2zfanh5mg.BFd1jrhH.8m', 'admin@datamarker.info', 'admin', 'admin', '', 0, 'admin', NULL);


--
-- Name: users_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('users_id', 101, true);


--
-- Name: pk_data; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY data
    ADD CONSTRAINT pk_data PRIMARY KEY (id);


--
-- Name: pk_data_users; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY data_users
    ADD CONSTRAINT pk_data_users PRIMARY KEY (id);


--
-- Name: pk_tags; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tags
    ADD CONSTRAINT pk_tags PRIMARY KEY (id);


--
-- Name: pk_tags_data; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tags_data
    ADD CONSTRAINT pk_tags_data PRIMARY KEY (id);


--
-- Name: pk_users; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT pk_users PRIMARY KEY (id);


--
-- Name: data_data_users; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY data_users
    ADD CONSTRAINT data_data_users FOREIGN KEY (data_id) REFERENCES data(id);


--
-- Name: data_tags_data; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tags_data
    ADD CONSTRAINT data_tags_data FOREIGN KEY (data_id) REFERENCES data(id);


--
-- Name: tags_tags_data; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tags_data
    ADD CONSTRAINT tags_tags_data FOREIGN KEY (tag_id) REFERENCES tags(id);


--
-- Name: users_data_users; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY data_users
    ADD CONSTRAINT users_data_users FOREIGN KEY (user_id) REFERENCES users(id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

