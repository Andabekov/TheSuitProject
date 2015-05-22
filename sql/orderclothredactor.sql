CREATE TABLE orderclothredactor
(
    order_cloth_id INT PRIMARY KEY NOT NULL,
    cloth_type INT NOT NULL,
    measurement_type INT,
    fabric_id INT NOT NULL,
    brand_label VARCHAR(500),
    monogram1_pos VARCHAR(1000),
    monogram1_font VARCHAR(1000),
    monogram1_color_font VARCHAR(1000),
    monogram1_text VARCHAR(1000),
    monogram2_pos VARCHAR(1000),
    monogram2_font VARCHAR(1000),
    monogram2_color_font VARCHAR(1000),
    monogram2_text VARCHAR(1000),
    redactor_comment VARCHAR(1000)
);

INSERT INTO pidzhak.orderclothredactor (order_cloth_id, cloth_type, measurement_type, fabric_id, brand_label, monogram1_pos, monogram1_font, monogram1_color_font, monogram1_text, monogram2_pos, monogram2_font, monogram2_color_font, monogram2_text, redactor_comment) VALUES (4, 1, 1, 1, '1', 'in the middle new', '', '', '', '', '', '', '', '');
