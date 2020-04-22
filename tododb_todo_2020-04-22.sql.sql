----
-- phpLiteAdmin database dump (https://www.phpliteadmin.org/)
-- phpLiteAdmin version: 1.9.8.2
-- Exported: 4:00pm on April 22, 2020 (CEST)
-- database file: ./tododb.db
----
BEGIN TRANSACTION;

----
-- Table structure for todo
----
CREATE TABLE 'todo' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  'title' TEXT NOT NULL, 'status' BOOLEAN, 'created_at' DATETIME, 'updated_at' DATETIME);

CREATE TABLE 'user' (
  'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  'username' varchar(50) NOT NULL,
  'password' varchar(250) NOT NULL,
  'email' varchar(255) DEFAULT NULL,
  'status' int(11) NOT NULL DEFAULT '0',
  'auth_key' varchar(250) NOT NULL,
  'access_token' varchar(250) NOT NULL,
  'password_reset_token' varchar(512) DEFAULT NULL
);

----
-- Data dump for todo, a total of 0 rows
----
COMMIT;
