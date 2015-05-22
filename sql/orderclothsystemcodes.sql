CREATE TABLE orderclothsystemcodes
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    order_cloth_id INT NOT NULL,
    code VARCHAR(20) NOT NULL,
    fabric_optional VARCHAR(20),
    description VARCHAR(1000) NOT NULL
);

INSERT INTO pidzhak.orderclothsystemcodes (id, order_cloth_id, code, fabric_optional, description) VALUES (39, 4, '57A6', null, 'dasda');
INSERT INTO pidzhak.orderclothsystemcodes (id, order_cloth_id, code, fabric_optional, description) VALUES (40, 4, '25D5', null, '123312');
