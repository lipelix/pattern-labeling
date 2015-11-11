/* ---------------------------------------------------------------------- */
/* Script generated with: DeZign for Databases v6.3.4                     */
/* Target DBMS:           PostgreSQL 9                                    */
/* Project file:          db.dez                                          */
/* Project name:                                                          */
/* Author:                                                                */
/* Script type:           Database drop script                            */
/* Created on:            2015-11-11 12:30                                */
/* ---------------------------------------------------------------------- */


/* ---------------------------------------------------------------------- */
/* Drop foreign key constraints                                           */
/* ---------------------------------------------------------------------- */

ALTER TABLE data_users DROP CONSTRAINT data_data_users;

ALTER TABLE data_users DROP CONSTRAINT users_data_users;

ALTER TABLE tags_data DROP CONSTRAINT tags_tags_data;

ALTER TABLE tags_data DROP CONSTRAINT data_tags_data;

/* ---------------------------------------------------------------------- */
/* Drop table "tags_data"                                                 */
/* ---------------------------------------------------------------------- */

/* Drop constraints */

ALTER TABLE tags_data DROP CONSTRAINT PK_tags_data;

/* Drop table */

DROP TABLE tags_data;

/* ---------------------------------------------------------------------- */
/* Drop table "tags"                                                      */
/* ---------------------------------------------------------------------- */

/* Drop constraints */

ALTER TABLE tags DROP CONSTRAINT PK_tags;

/* Drop table */

DROP TABLE tags;

/* ---------------------------------------------------------------------- */
/* Drop table "data_users"                                                */
/* ---------------------------------------------------------------------- */

/* Drop constraints */

ALTER TABLE data_users DROP CONSTRAINT PK_data_users;

/* Drop table */

DROP TABLE data_users;

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

DROP SEQUENCE tags_id;

DROP SEQUENCE data_users_id;

DROP SEQUENCE tags_data_id;
