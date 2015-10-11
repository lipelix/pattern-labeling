/* ---------------------------------------------------------------------- */
/* Script generated with: DeZign for Databases v6.3.4                     */
/* Target DBMS:           PostgreSQL 9                                    */
/* Project file:          db.dez                                          */
/* Project name:                                                          */
/* Author:                                                                */
/* Script type:           Database creation script                        */
/* Created on:            2015-09-23 13:55                                */
/* ---------------------------------------------------------------------- */


/* ---------------------------------------------------------------------- */
/* Sequences                                                              */
/* ---------------------------------------------------------------------- */

CREATE SEQUENCE users_id INCREMENT 1 MINVALUE 0 START 0;

CREATE SEQUENCE data_id INCREMENT 1 MINVALUE 0 START 0;

CREATE SEQUENCE data_category_id INCREMENT 1 MINVALUE 0 START 0;

/* ---------------------------------------------------------------------- */
/* Tables                                                                 */
/* ---------------------------------------------------------------------- */

/* ---------------------------------------------------------------------- */
/* Add table "users"                                                      */
/* ---------------------------------------------------------------------- */

CREATE TABLE users (
    id INTEGER DEFAULT nextval('users_id')  NOT NULL,
    login CHARACTER VARYING(255)  NOT NULL,
    password CHARACTER VARYING(255)  NOT NULL,
    email CHARACTER VARYING(255),
    first_name CHARACTER VARYING(255),
    last_name CHARACTER VARYING(255),
    gender CHARACTER VARYING(1),
    age SMALLINT,
    role CHARACTER VARYING(40),
    CONSTRAINT PK_users PRIMARY KEY (id)
);

/* ---------------------------------------------------------------------- */
/* Add table "data"                                                       */
/* ---------------------------------------------------------------------- */

CREATE TABLE data (
    id INTEGER DEFAULT nextval('data_id')  NOT NULL,
    category_id INTEGER,
    content BYTEA,
    CONSTRAINT PK_data PRIMARY KEY (id)
);

/* ---------------------------------------------------------------------- */
/* Add table "data_category"                                              */
/* ---------------------------------------------------------------------- */

CREATE TABLE data_category (
    id INTEGER  NOT NULL,
    name CHARACTER VARYING(40)  NOT NULL,
    CONSTRAINT PK_data_category PRIMARY KEY (id)
);

/* ---------------------------------------------------------------------- */
/* Add table "data_users"                                                 */
/* ---------------------------------------------------------------------- */

CREATE TABLE data_users (
    data_id INTEGER  NOT NULL,
    user_id INTEGER  NOT NULL,
    CONSTRAINT PK_data_users PRIMARY KEY (data_id, user_id)
);

/* ---------------------------------------------------------------------- */
/* Foreign key constraints                                                */
/* ---------------------------------------------------------------------- */

ALTER TABLE data_category ADD CONSTRAINT data_data_category 
    FOREIGN KEY (id) REFERENCES data (id);

ALTER TABLE data_users ADD CONSTRAINT data_data_users 
    FOREIGN KEY (data_id) REFERENCES data (id);

ALTER TABLE data_users ADD CONSTRAINT users_data_users 
    FOREIGN KEY (user_id) REFERENCES users (id);
