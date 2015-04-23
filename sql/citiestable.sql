CREATE TABLE citiestable
(
    id INT PRIMARY KEY NOT NULL,
    country_id INT NOT NULL,
    city_name VARCHAR(200) NOT NULL
);
CREATE UNIQUE INDEX unique_id ON citiestable (id);


INSERT INTO pidzhak.citiestable (id, country_id, city_name) VALUES (1, 1, 'Астана');
INSERT INTO pidzhak.citiestable (id, country_id, city_name) VALUES (2, 1, 'Алматы');
INSERT INTO pidzhak.citiestable (id, country_id, city_name) VALUES (3, 1, 'Караганды');
INSERT INTO pidzhak.citiestable (id, country_id, city_name) VALUES (4, 1, 'Кокшетау');
INSERT INTO pidzhak.citiestable (id, country_id, city_name) VALUES (5, 1, 'Темиртау');
INSERT INTO pidzhak.citiestable (id, country_id, city_name) VALUES (6, 2, 'Москва');
INSERT INTO pidzhak.citiestable (id, country_id, city_name) VALUES (7, 2, 'Омск');
INSERT INTO pidzhak.citiestable (id, country_id, city_name) VALUES (8, 2, 'Санкт-Петербург');
