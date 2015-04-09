CREATE TABLE customer
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    firstname VARCHAR(100),
    lastname VARCHAR(100),
    middlename VARCHAR(100),
    birthday DATE,
    mobilephone VARCHAR(15),
    email VARCHAR(50),
    country VARCHAR(100),
    city VARCHAR(100),
    address VARCHAR(100),
    homephone VARCHAR(15),
    work VARCHAR(100),
    position VARCHAR(100),
    workaddress VARCHAR(100),
    workphone VARCHAR(15)
);
