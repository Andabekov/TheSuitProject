CREATE TABLE stylestable
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    style_num INT NOT NULL,
    cloth_type INT NOT NULL,
    style_code VARCHAR(20) NOT NULL,
    style_code_fabric VARCHAR(20) NOT NULL,
    style_code_desc VARCHAR(500) NOT NULL
);
CREATE UNIQUE INDEX unique_id ON stylestable (id);
CREATE UNIQUE INDEX unique_style_num ON stylestable (style_num);


INSERT INTO pidzhak.stylestable (id, style_num, cloth_type, style_code, style_code_fabric, style_code_desc) VALUES (2, 1, 1, '3', '4', 'Новая ткань для нового стиля');
