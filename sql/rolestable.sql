CREATE TABLE rolestable
(
    id INT PRIMARY KEY NOT NULL,
    role_name VARCHAR(200) NOT NULL
);

INSERT INTO pidzhak.rolestable (id, role_name) VALUES (1, 'Продавец');
INSERT INTO pidzhak.rolestable (id, role_name) VALUES (2, 'Редактор');
INSERT INTO pidzhak.rolestable (id, role_name) VALUES (3, 'Бухгалтер');
INSERT INTO pidzhak.rolestable (id, role_name) VALUES (4, 'Директор');
INSERT INTO pidzhak.rolestable (id, role_name) VALUES (5, 'Курьер');
INSERT INTO pidzhak.rolestable (id, role_name) VALUES (6, 'Администратор');
