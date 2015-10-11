/* ---------------------------------------------------------------------- */
/* Script generated with: DeZign for Databases v6.3.4                     */
/* Target DBMS:           PostgreSQL 9                                    */
/* Project file:          db.dez                                          */
/* Project name:                                                          */
/* Author:                                                                */
/* Script type:           Database drop script                            */
/* Created on:            2015-09-23 13:55                                */
/* ---------------------------------------------------------------------- */


/* ---------------------------------------------------------------------- */
/* Drop foreign key constraints                                           */
/* ---------------------------------------------------------------------- */

ALTER TABLE data_category DROP CONSTRAINT data_data_category;

ALTER TABLE data_users DROP CONSTRAINT data_data_users;

ALTER TABLE data_users DROP CONSTRAINT users_data_users;

/* ---------------------------------------------------------------------- */
/* Drop table "data_users"                                                */
/* ---------------------------------------------------------------------- */

/* Drop constraints */

ALTER TABLE data_users DROP CONSTRAINT PK_data_users;

/* Drop table */

DROP TABLE data_users;

/* ---------------------------------------------------------------------- */
/* Drop table "data_category"                                             */
/* ---------------------------------------------------------------------- */

/* Drop constraints */

ALTER TABLE data_category DROP CONSTRAINT PK_data_category;

/* Drop table */

DROP TABLE data_category;

/* ---------------------------------------------------------------------- */
/* Drop table "data"                                                      */
/* ---------------------------------------------------------------------- */

/* Drop constraints */

ALTER TABLE data DROP CONSTRAINT PK_data;

/* Drop table */

DROP TABLE data;

/* ---------------------------------------------------------------------- */
/* Drop table "users"                                                     */
/* ---------------------------------------------------------------------- */

/* Drop constraints */

ALTER TABLE users DROP CONSTRAINT PK_users;

/* Drop table */

DROP TABLE users;

/* ---------------------------------------------------------------------- */
/* Drop sequences                                                         */
/* ---------------------------------------------------------------------- */

DROP SEQUENCE users_id;

DROP SEQUENCE data_id;

DROP SEQUENCE data_category_id;
