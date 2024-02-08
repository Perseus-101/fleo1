CREATE TABLE users (
    userid SERIAL PRIMARY KEY,
    username VARCHAR(50),
    email VARCHAR(100),
    password VARCHAR(100),
    firstname VARCHAR(50),
    lastname VARCHAR(50),
    birthdate DATE,
    address VARCHAR(255),
    phone VARCHAR(10),
    regdate DATE
);
