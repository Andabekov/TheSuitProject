CREATE TABLE countriestable
(
    id INT PRIMARY KEY NOT NULL,
    country_name VARCHAR(200) NOT NULL
);
CREATE UNIQUE INDEX unique_id ON countriestable (id);


INSERT INTO pidzhak.countriestable (id, country_name) VALUES (1, 'Казахстан');
INSERT INTO pidzhak.countriestable (id, country_name) VALUES (2, 'Россия');
INSERT INTO pidzhak.countriestable (id, country_name) VALUES (3, 'Украина');
INSERT INTO pidzhak.countriestable (id, country_name) VALUES (4, 'Кыргызстан');
INSERT INTO pidzhak.countriestable (id, country_name) VALUES (5, 'Узбекистан');
