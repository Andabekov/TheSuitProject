CREATE TABLE cyclestable
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    order_accept_start_date DATE NOT NULL,
    order_accept_finish_date DATE NOT NULL,
    order_check_deadline_date DATE NOT NULL,
    submit_deadline_date DATE NOT NULL,
    ship_deadline_date DATE NOT NULL,
    arrive_deadline_date DATE NOT NULL,
    cycle_active TINYINT DEFAULT 1 NOT NULL
);
CREATE UNIQUE INDEX unique_id ON cyclestable (id);


INSERT INTO pidzhak.cyclestable (id, order_accept_start_date, order_accept_finish_date, order_check_deadline_date, submit_deadline_date, ship_deadline_date, arrive_deadline_date, cycle_active) VALUES (2, '2015-04-01', '2015-04-30', '2015-04-12', '2015-04-19', '2015-04-26', '2015-05-15', 1);
