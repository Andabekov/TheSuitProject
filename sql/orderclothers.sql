CREATE TABLE orderclothes
(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  order_id INT,
  cycle_number VARCHAR(100) COMMENT 'Номер цикла',
  product_name VARCHAR(100) COMMENT 'Наименование изделия',
  textile_class VARCHAR(100) COMMENT 'Класс Ткани',
  textile_number VARCHAR(100) COMMENT 'Номер ткани',
  typeof_measure VARCHAR(100) COMMENT 'Вид замера',
  label_brand VARCHAR(100) COMMENT 'Этикетка бранда',
  style_number VARCHAR(100) COMMENT 'Номер стиля',
  first_monogram_location VARCHAR(100) COMMENT '1. Монограм (расположение)',
  first_monogram_font VARCHAR(100) COMMENT '1. Монограм (шрифт)',
  first_monogram_font_color VARCHAR(100) COMMENT '1. Монограм (цвет шрифта)',
  first_monogram_caption VARCHAR(100) COMMENT '1. Монограм (Надпись)',
  second_monogram_location VARCHAR(100) COMMENT '2. Монограм (расположение)',
  second_monogram_font VARCHAR(100) COMMENT '2. Монограм (шрифт)',
  second_monogram_font_color VARCHAR(100) COMMENT '2. Монограм (цвет шрифта)',
  second_monogram_caption VARCHAR(100) COMMENT '2. Монограм (Надпись)',
  seller_comment VARCHAR(100) COMMENT 'Комментарии Продавца',

  INDEX orderclothers_order_id_ind (order_id),
  FOREIGN KEY (order_id)  REFERENCES ordertable(id)
);

