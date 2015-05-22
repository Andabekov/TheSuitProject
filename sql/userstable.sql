CREATE TABLE userstable (
  id int(10) NOT NULL AUTO_INCREMENT,
  username varchar(30) NOT NULL,
  password varchar(10) NOT NULL,
  name varchar(100) NOT NULL,
  surname varchar(200) NOT NULL,
  phone varchar(20) NOT NULL,
  access_type_id int(10) NOT NULL,
  email varchar(45) NOT NULL,
  PRIMARY KEY (id),
  KEY ACCESS_TYPE_ID_idx (access_type_id),
  CONSTRAINT FK_ACCESS_TYPE_ID FOREIGN KEY (access_type_id) REFERENCES accesstypes (ID)
) ;


INSERT INTO userstable (ID,USERNAME,PASSWORD,NAME,SURNAME,PHONE,ACCESS_TYPE_ID,EMAIL) VALUES (1,'Abu','510510','Abu','Andabekov','510510',1,'andabekov_az@mail.ru');
INSERT INTO userstable (ID,USERNAME,PASSWORD,NAME,SURNAME,PHONE,ACCESS_TYPE_ID,EMAIL) VALUES (2,'Ernar','510510','Ernar','Meiramgali','510510',2,'meiramgali@mail.ru');
INSERT INTO userstable (ID,USERNAME,PASSWORD,NAME,SURNAME,PHONE,ACCESS_TYPE_ID,EMAIL) VALUES (4,'Director','510510','Director','Director','510510',4,'director@mail.ru');

INSERT INTO userstable (ID,USERNAME,PASSWORD,NAME,SURNAME,PHONE,ACCESS_TYPE_ID,EMAIL) VALUES (1,'admin','510510','Admin','Admin','77016538609',6,'admin@mail.ru');
