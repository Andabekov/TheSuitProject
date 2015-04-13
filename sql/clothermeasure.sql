CREATE TABLE clothermeasure
(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  clother_id INT,
  customer_id INT,
  growth VARCHAR(100) COMMENT 'Рост',
  weight VARCHAR(100) COMMENT 'Вес',
  chest_finished VARCHAR(100) COMMENT 'Грудь (Chest Finished)',
  stomach_finished VARCHAR(100) COMMENT 'Живот (Stomach Finished)',
  jacket_seat_finished VARCHAR(100) COMMENT 'Зад Пиджака (Seat Finished)',
  biceps_finished VARCHAR(100) COMMENT 'Бицепс (Biceps Finished)',
  left_sleeve_finished VARCHAR(100) COMMENT 'Левый рукав (Left sleeve Finished)',
  right_sleeve_finished VARCHAR(100) COMMENT 'Правый рукав (Right sleeve Finished)',
  back_length_finished VARCHAR(100) COMMENT 'Длина изделия со спины (Back length Finished)',
  front_length_finished VARCHAR(100) COMMENT 'Длина изделия спереди (Front length Finished)',
  shoulder_finished VARCHAR(100) COMMENT 'Ширина спины (Shoulder Finished)',
  waist_finished VARCHAR(100) COMMENT 'Брючная талия (Waist Finished)',
  seat_finished VARCHAR(100) COMMENT 'Зад Брюк (Seat Finished)',
  thigh_finished VARCHAR(100) COMMENT 'Бедро (Thigh Finished)',
  outseam_l_and_r_finished VARCHAR(100) COMMENT 'Длина брюк (Outseam L and R Finished)',
  knee_finished VARCHAR(100) COMMENT 'Колено (Knee Finished)',
  pant_bottom_finished VARCHAR(100) COMMENT 'Низ брюк (Pant bottom Finished)',
  u_rise_finished VARCHAR(100) COMMENT 'Шов сиденья (U-rise Finished)',

  INDEX clothermeasure_clother_id_ind (clother_id),
  FOREIGN KEY (clother_id)  REFERENCES clothers(id),
  INDEX clothermeasure_customer_id_ind (customer_id),
  FOREIGN KEY (customer_id)  REFERENCES customer(id)
);


