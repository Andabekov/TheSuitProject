CREATE TABLE pricestable
(
    id INT PRIMARY KEY NOT NULL,
    fabric_class INT NOT NULL,
    cloth_type INT NOT NULL,
    price INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL
);
CREATE UNIQUE INDEX unique_id ON pricestable (id);


INSERT INTO pidzhak.pricestable (id, fabric_class, cloth_type, price, start_date, end_date) VALUES (1, 1, 1, 1, '2015-04-09', '2015-04-22');
