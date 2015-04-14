CREATE TABLE 'userstable' (
  'ID' int(10) NOT NULL AUTO_INCREMENT,
  'USERNAME' varchar(30) NOT NULL,
  'PASSWORD' varchar(10) NOT NULL,
  'NAME' varchar(100) NOT NULL,
  'SURNAME' varchar(200) NOT NULL,
  'PHONE' varchar(20) NOT NULL,
  'ACCESS_TYPE_ID' int(10) NOT NULL,
  'EMAIL' varchar(45) NOT NULL,
  PRIMARY KEY ('ID'),
  KEY 'ACCESS_TYPE_ID_idx' ('ACCESS_TYPE_ID'),
  CONSTRAINT 'FK_ACCESS_TYPE_ID' FOREIGN KEY ('ACCESS_TYPE_ID') REFERENCES 'accesstypes' ('ID') ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;


INSERT INTO 'userstable' ('ID','USERNAME','PASSWORD','NAME','SURNAME','PHONE','ACCESS_TYPE_ID','EMAIL') VALUES (1,'Abu','510510','Abu','Andabekov','510510',1,'andabekov_az@mail.ru');
INSERT INTO 'userstable' ('ID','USERNAME','PASSWORD','NAME','SURNAME','PHONE','ACCESS_TYPE_ID','EMAIL') VALUES (2,'Ernar','510510','Ernar','Meiramgali','510510',2,'meiramgali@mail.ru');
INSERT INTO 'userstable' ('ID','USERNAME','PASSWORD','NAME','SURNAME','PHONE','ACCESS_TYPE_ID','EMAIL') VALUES (3,'Admin','510510','Admin','Admin','510510',6,'admin@mail.ru');
INSERT INTO 'userstable' ('ID','USERNAME','PASSWORD','NAME','SURNAME','PHONE','ACCESS_TYPE_ID','EMAIL') VALUES (4,'Director','510510','Director','Director','510510',4,'director@mail.ru');

