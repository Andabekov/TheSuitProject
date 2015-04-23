CREATE TABLE paymenttypes
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    payment_type_name VARCHAR(200) NOT NULL
);
CREATE UNIQUE INDEX unique_id ON paymenttypes (id);

INSERT INTO pidzhak.paymenttypes (id, payment_type_name) VALUES (1, 'Наличные');
INSERT INTO pidzhak.paymenttypes (id, payment_type_name) VALUES (2, 'Кредитная карта');
INSERT INTO pidzhak.paymenttypes (id, payment_type_name) VALUES (3, 'В рассрочку');
