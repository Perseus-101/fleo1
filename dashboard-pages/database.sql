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

CREATE TABLE financial_record (
    dataid SERIAL PRIMARY KEY,
    userid INT REFERENCES users(userid),
    amount NUMERIC(10,2),
    transaction_date DATE,
    currency VARCHAR(3),
    account_type VARCHAR(50),
    category VARCHAR(50),
    description VARCHAR(255)
);
 
CREATE TABLE portfolio (
    portfolioid SERIAL PRIMARY KEY,
    userid INT REFERENCES users(userid),
    assetname CHAR(50),
    quantity INT,
    purchasevalue FLOAT,
    currentvalue FLOAT
);
