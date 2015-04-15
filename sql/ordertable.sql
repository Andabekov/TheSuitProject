CREATE TABLE ordertable (
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  dateofsale date,
  pricelistnum int(11),
  payamount decimal(10,0),
  paytype varchar(100),
  cashlocation varchar(100),
  cityofsale varchar(100),
  pointofsale varchar(100),
  seller varchar(100)
);

