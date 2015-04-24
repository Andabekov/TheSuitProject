CREATE TABLE clothstatustable
(
    id INT PRIMARY KEY NOT NULL,
    status_name VARCHAR(200) NOT NULL
);
CREATE UNIQUE INDEX unique_id ON clothstatustable (id);


INSERT INTO pidzhak.clothstatustable (id, status_name) VALUES (1, 'В обработке');
INSERT INTO pidzhak.clothstatustable (id, status_name) VALUES (2, 'На сверке кодов системы');
INSERT INTO pidzhak.clothstatustable (id, status_name) VALUES (3, 'Проверка продавцом');
INSERT INTO pidzhak.clothstatustable (id, status_name) VALUES (4, 'В ожидании производства');
INSERT INTO pidzhak.clothstatustable (id, status_name) VALUES (5, 'Готово к отправке');
INSERT INTO pidzhak.clothstatustable (id, status_name) VALUES (6, 'В пути до Астаны');
INSERT INTO pidzhak.clothstatustable (id, status_name) VALUES (7, 'В офисе');
INSERT INTO pidzhak.clothstatustable (id, status_name) VALUES (8, 'Пригласили на примерку');
INSERT INTO pidzhak.clothstatustable (id, status_name) VALUES (9, 'Изделие выдано');
