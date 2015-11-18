/* ---------------------------------------------------------------------- */
/* Script generated with: DeZign for Databases v6.3.4                     */
/* Target DBMS:           PostgreSQL 9                                    */
/* Project file:          db.dez                                          */
/* Project name:                                                          */
/* Author:                                                                */
/* Script type:           Database creation script                        */
/* Created on:            2015-11-18 19:21                                */
/* ---------------------------------------------------------------------- */


/* ---------------------------------------------------------------------- */
/* Sequences                                                              */
/* ---------------------------------------------------------------------- */

CREATE SEQUENCE users_id INCREMENT 1 MINVALUE 0 START 0;

CREATE SEQUENCE data_id INCREMENT 1 MINVALUE 0 START 0;

CREATE SEQUENCE tags_id INCREMENT 1 MINVALUE 0 START 0;

CREATE SEQUENCE data_users_id INCREMENT 1 MINVALUE 0 START 0;

CREATE SEQUENCE tags_data_id INCREMENT 1 MINVALUE 0 START 0;

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
    content BYTEA,
    created_at TIMESTAMP DEFAULT NOW();,
    CONSTRAINT PK_data PRIMARY KEY (id)
);

/* ---------------------------------------------------------------------- */
/* Add table "data_users"                                                 */
/* ---------------------------------------------------------------------- */

CREATE TABLE data_users (
    id INTEGER DEFAULT nextval('data_users_id')  NOT NULL,
    data_id INTEGER  NOT NULL,
    user_id INTEGER,
    path BYTEA  NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()  NOT NULL,
    CONSTRAINT PK_data_users PRIMARY KEY (id)
);

/* ---------------------------------------------------------------------- */
/* Add table "tags"                                                       */
/* ---------------------------------------------------------------------- */

CREATE TABLE tags (
    id INTEGER DEFAULT nextval('tags_id')  NOT NULL,
    name CHARACTER VARYING(255)  NOT NULL,
    CONSTRAINT PK_tags PRIMARY KEY (id)
);

/* ---------------------------------------------------------------------- */
/* Add table "tags_data"                                                  */
/* ---------------------------------------------------------------------- */

CREATE TABLE tags_data (
    id INTEGER DEFAULT nextval('tags_data_id')  NOT NULL,
    tag_id INTEGER  NOT NULL,
    data_id INTEGER  NOT NULL,
    CONSTRAINT PK_tags_data PRIMARY KEY (id)
);

/* ---------------------------------------------------------------------- */
/* Foreign key constraints                                                */
/* ---------------------------------------------------------------------- */

ALTER TABLE data_users ADD CONSTRAINT data_data_users 
    FOREIGN KEY (data_id) REFERENCES data (id);

ALTER TABLE data_users ADD CONSTRAINT users_data_users 
    FOREIGN KEY (user_id) REFERENCES users (id);

ALTER TABLE tags_data ADD CONSTRAINT tags_tags_data 
    FOREIGN KEY (tag_id) REFERENCES tags (id);

ALTER TABLE tags_data ADD CONSTRAINT data_tags_data 
    FOREIGN KEY (data_id) REFERENCES data (id);
