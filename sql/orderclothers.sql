CREATE TABLE orderclothes
(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  order_id INT,
  product_id INT,
  cycle_id INT,
  preferred_date DATE,
  textile_id INT,
  pricelistnum VARCHAR(100),
  actual_amount DECIMAL(10,0),
  paytype_id VARCHAR(100),
  typeof_measure VARCHAR(100),
  label_brand INT,
  style_id VARCHAR(100),
  first_monogram_location VARCHAR(100),
  first_monogram_font VARCHAR(100),
  first_monogram_font_color VARCHAR(100),
  first_monogram_caption VARCHAR(100),
  second_monogram_location VARCHAR(100),
  second_monogram_font VARCHAR(100),
  second_monogram_font_color VARCHAR(100),
  second_monogram_caption VARCHAR(100),
  seller_comment VARCHAR(100),
  status_id INT DEFAULT 1 NOT NULL,
  FOREIGN KEY (cycle_id) REFERENCES cyclestable (id),
  FOREIGN KEY (order_id) REFERENCES ordertable (id),
  FOREIGN KEY (product_id) REFERENCES clothers (id),
  FOREIGN KEY (textile_id) REFERENCES fabricstable (id)
);
CREATE INDEX orderclothers_cycle_id_ind ON orderclothes (cycle_id);
CREATE INDEX orderclothers_order_id_ind ON orderclothes (order_id);
CREATE INDEX orderclothers_product_id_ind ON orderclothes (product_id);
CREATE INDEX orderclothers_textile_id_ind ON orderclothes (textile_id);
