--
-- PostgreSQL database dump
--

-- Dumped from database version 9.4.4
-- Dumped by pg_dump version 9.4.4
-- Started on 2015-10-11 21:28:09

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 179 (class 3079 OID 11855)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2036 (class 0 OID 0)
-- Dependencies: 179
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- TOC entry 173 (class 1259 OID 24601)
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
-- TOC entry 176 (class 1259 OID 24614)
-- Name: data; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE data (
    id integer DEFAULT nextval('data_id'::regclass) NOT NULL,
    category_id integer,
    content bytea,
    created_at time with time zone DEFAULT now()
);


ALTER TABLE data OWNER TO postgres;

--
-- TOC entry 177 (class 1259 OID 24623)
-- Name: data_category; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE data_category (
    id integer NOT NULL,
    name character varying(40) NOT NULL
);


ALTER TABLE data_category OWNER TO postgres;

--
-- TOC entry 174 (class 1259 OID 24603)
-- Name: data_category_id; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE data_category_id
    START WITH 0
    INCREMENT BY 1
    MINVALUE 0
    NO MAXVALUE
    CACHE 1;


ALTER TABLE data_category_id OWNER TO postgres;

--
-- TOC entry 178 (class 1259 OID 24628)
-- Name: data_users; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE data_users (
    data_id integer NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE data_users OWNER TO postgres;

--
-- TOC entry 172 (class 1259 OID 24599)
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
-- TOC entry 175 (class 1259 OID 24605)
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
    role character varying(40)
);


ALTER TABLE users OWNER TO postgres;

--
-- TOC entry 2026 (class 0 OID 24614)
-- Dependencies: 176
-- Data for Name: data; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY data (id, category_id, content, created_at) FROM stdin;
0	\N	\\x32363b35310d0a39383b3432330d0a31323b39360d0a36363b38380d0a34373b3132330d0a3336383b3534370d0a3534323b38360d0a3132333b34350d0a39383b363635	15:26:03.75+02
2	\N	\\x31303b33320d0a3435363b32330d0a3738393b3536340d0a3232333b31370d0a3937343b32330d0a3939373b35360d0a3132333b3435360d0a32303b38380d0a31343b36380d0a32333b3734380d0a36353b31320d0a38393b323431	20:19:51.889+02
\.


--
-- TOC entry 2027 (class 0 OID 24623)
-- Dependencies: 177
-- Data for Name: data_category; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY data_category (id, name) FROM stdin;
\.


--
-- TOC entry 2037 (class 0 OID 0)
-- Dependencies: 174
-- Name: data_category_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('data_category_id', 0, false);


--
-- TOC entry 2038 (class 0 OID 0)
-- Dependencies: 173
-- Name: data_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('data_id', 2, true);


--
-- TOC entry 2028 (class 0 OID 24628)
-- Dependencies: 178
-- Data for Name: data_users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY data_users (data_id, user_id) FROM stdin;
\.


--
-- TOC entry 2025 (class 0 OID 24605)
-- Dependencies: 175
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY users (id, login, password, email, first_name, last_name, gender, age, role) FROM stdin;
1	b	$2y$10$VVlWjTvlqF8ZPMMuuHsIeu9U0o3dwnnLfe3ltT5wHYCdTTiTH7rga	\N				0	member
0	a	$2y$10$8IJM/TdUYGAP1tYJWqPe3.Ee4hdxLjkgKqwbC00rxe6jsWU53OLmW	\N				0	admin
2	d	$2y$10$ZO45uFoWh5xYHO7ItQb9a.zHJ2Bhvxr2c8yIP0wANGYYgqyObx4XW	\N				0	member
3	e	$2y$10$Cni5y/wtgBgrif7Vmf/6F.QZAT2u72zVyqZZAUmJ8ZaP4nZJ1P5fe	\N				0	member
4	f	$2y$10$5Xwd1E1eBMq09zoc95cBoeac4BQiHZMfY1cs47hVqfFQJ1sHM5K.m	\N				0	member
5	g	$2y$10$iJqfTaShFoLjuMMdGui5Bec9K58v7mYD2BtVgXA5uMbWU0EE7ibpC	\N				0	member
\.


--
-- TOC entry 2039 (class 0 OID 0)
-- Dependencies: 172
-- Name: users_id; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('users_id', 5, true);


--
-- TOC entry 1905 (class 2606 OID 24622)
-- Name: pk_data; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY data
    ADD CONSTRAINT pk_data PRIMARY KEY (id);


--
-- TOC entry 1907 (class 2606 OID 24627)
-- Name: pk_data_category; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY data_category
    ADD CONSTRAINT pk_data_category PRIMARY KEY (id);


--
-- TOC entry 1909 (class 2606 OID 24632)
-- Name: pk_data_users; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY data_users
    ADD CONSTRAINT pk_data_users PRIMARY KEY (data_id, user_id);


--
-- TOC entry 1903 (class 2606 OID 24613)
-- Name: pk_users; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT pk_users PRIMARY KEY (id);


--
-- TOC entry 1910 (class 2606 OID 24633)
-- Name: data_data_category; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY data_category
    ADD CONSTRAINT data_data_category FOREIGN KEY (id) REFERENCES data(id);


--
-- TOC entry 1911 (class 2606 OID 24638)
-- Name: data_data_users; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY data_users
    ADD CONSTRAINT data_data_users FOREIGN KEY (data_id) REFERENCES data(id);


--
-- TOC entry 1912 (class 2606 OID 24643)
-- Name: users_data_users; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY data_users
    ADD CONSTRAINT users_data_users FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2035 (class 0 OID 0)
-- Dependencies: 5
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2015-10-11 21:28:09

--
-- PostgreSQL database dump complete
--

