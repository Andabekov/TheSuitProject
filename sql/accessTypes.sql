CREATE TABLE accesstypes (
  ID int(11) NOT NULL,
  ACCESS_TYPE_NAME varchar(45) NOT NULL,
  PRIMARY KEY (ID)
) ;

INSERT INTO accesstypes (ID,ACCESS_TYPE_NAME) VALUES (1,'seller');
INSERT INTO accesstypes (ID,ACCESS_TYPE_NAME) VALUES (2,'redactor');
INSERT INTO accesstypes (ID,ACCESS_TYPE_NAME) VALUES (3,'accountant');
INSERT INTO accesstypes (ID,ACCESS_TYPE_NAME) VALUES (4,'director');
INSERT INTO accesstypes (ID,ACCESS_TYPE_NAME) VALUES (5,'delivery');
INSERT INTO accesstypes (ID,ACCESS_TYPE_NAME) VALUES (6,'admin');