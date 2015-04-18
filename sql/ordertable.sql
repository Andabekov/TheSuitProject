CREATE TABLE ordertable (
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  customer_id INT,
  dateofsale date,
  pricelistnum int(11),
  payamount decimal(10,0),
  paytype varchar(100),
  cashlocation varchar(100),
  cityofsale varchar(100),
  pointofsale varchar(100),
  seller varchar(100),
  INDEX ordertable_customer_id_ind (customer_id),
  FOREIGN KEY (customer_id)  REFERENCES customer(id)
);

