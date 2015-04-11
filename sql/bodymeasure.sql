CREATE TABLE bodymeasure
(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  clother_id INT,
  customer_id INT,
  growth VARCHAR(100) COMMENT 'Рост',
  weight VARCHAR(100) COMMENT 'Вес',
  arm_position VARCHAR(100) COMMENT 'Положение рук (Arm position)',
  neck VARCHAR(100) COMMENT 'Шея (Neck)',
  chest VARCHAR(100) COMMENT 'Грудь (Chest)',
  stomach VARCHAR(100) COMMENT 'Живот (Stomach)',
  seat VARCHAR(100) COMMENT 'Зад (Seat)',
  thigh VARCHAR(100) COMMENT 'Бедро (Thigh)',
  knee_finished VARCHAR(100) COMMENT 'Колено (Knee Finished)',
  pant_bottom_finished VARCHAR(100) COMMENT 'Низ брюк (Pant bottom Finished)',
  u_rise VARCHAR(100) COMMENT 'Шов сиденья (U-rise)',
  otseam_l_and_r VARCHAR(100) COMMENT 'Длина брюк (Otseam L and R)',
  nape_to_waist VARCHAR(100) COMMENT 'Длина до талии спины (ДТС) (Nape to waist)',
  front_waist_length VARCHAR(100) COMMENT 'Длина до талии полочки (ДТП) (Front waist length)',
  back_waist_height VARCHAR(100) COMMENT 'Расстояние от талии спины до пояса брюк (Back waist height)',
  front_waist_height VARCHAR(100) COMMENT 'Расстояние от талии полочки до пояса брюк (Front waist height)',
  biceps VARCHAR(100) COMMENT 'Бицепс (Biceps)',
  back_shoulder VARCHAR(100) COMMENT 'Ширина спины (Back shoulder)',
  right_sleeve VARCHAR(100) COMMENT 'Правый рукав (Right sleeve)',
  left_sleeve VARCHAR(100) COMMENT 'Левый рукав (Left sleeve)',
  back_length VARCHAR(100) COMMENT 'Длина изделия со спины (Back length)',
  overcoat_back_length VARCHAR(100) COMMENT 'Длина пальто cо спины (Overcoat back length)',
  waist VARCHAR(100) COMMENT 'Брючная талия (Waist)',
  right_wrist VARCHAR(100) COMMENT 'Запястье (правое) (Right wrist)',
  left_wrist VARCHAR(100) COMMENT 'Запястье (левое) (Left wrist)',
  style VARCHAR(100) COMMENT 'Приталенность (Style)',

  INDEX bodymeasure_clother_id_ind (clother_id),
  FOREIGN KEY (clother_id)  REFERENCES clothers(id),
  INDEX bodymeasure_customer_id_ind (customer_id),
  FOREIGN KEY (customer_id)  REFERENCES customer(id)
);

