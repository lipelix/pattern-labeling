/* ---------------------------------------------------------------------- */
/* Script generated with: DeZign for Databases v6.3.4                     */
/* Target DBMS:           PostgreSQL 9                                    */
/* Project file:          db.dez                                          */
/* Project name:                                                          */
/* Author:                                                                */
/* Script type:           Database creation script                        */
/* Created on:            2015-09-17 13:18                                */
/* ---------------------------------------------------------------------- */


/* ---------------------------------------------------------------------- */
/* Sequences                                                              */
/* ---------------------------------------------------------------------- */

CREATE SEQUENCE users_id INCREMENT 1 MINVALUE 0 START 0;

/* ---------------------------------------------------------------------- */
/* Tables                                                                 */
/* ---------------------------------------------------------------------- */

/* ---------------------------------------------------------------------- */
/* Add table "users"                                                      */
/* ---------------------------------------------------------------------- */

CREATE TABLE users (
    id INTEGER DEFAULT nextval('users_id')  NOT NULL,
    login CHARACTER(255)  NOT NULL,
    password CHARACTER(255)  NOT NULL,
    email CHARACTER(255),
    first_name CHARACTER(255),
    last_name CHARACTER(255),
    gender CHARACTER(1),
    age SMALLINT,
    role CHARACTER(40),
    CONSTRAINT PK_users PRIMARY KEY (id)
);
