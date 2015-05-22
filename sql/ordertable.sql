CREATE TABLE ordertable
(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  customer_id INT,
  dateofsale DATE,
  cityofsale_id VARCHAR(100),
  pointofsale VARCHAR(100),
  seller_id INT,
  status INT,
  FOREIGN KEY (customer_id) REFERENCES customer (id),
  FOREIGN KEY (seller_id) REFERENCES userstable (id)
);
CREATE INDEX ordertable_customer_id_ind ON ordertable (customer_id);
CREATE INDEX ordertable_seller_id_ind ON ordertable (seller_id);
