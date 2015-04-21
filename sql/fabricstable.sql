CREATE TABLE fabricstable
(
    id INT PRIMARY KEY NOT NULL,
    fabric_class VARCHAR(100) NOT NULL,
    fabric_available_start_date DATE NOT NULL
);
CREATE UNIQUE INDEX unique_id ON fabricstable (id);

INSERT INTO pidzhak.fabricstable (id, fabric_class, fabric_available_start_date) VALUES (1, 'бестТкань', '2015-04-18');
