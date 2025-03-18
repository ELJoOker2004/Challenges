CREATE DATABASE IF NOT EXISTS vault_db;
USE vault_db;

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL
);

INSERT INTO users (username, password) VALUES
    ('admin', 'FLAG{Yo4_GOt_It_SQLi_Unl0ck_Fl4ggggg}'), -- THE FLAG
    ('john', 'password123'),
    ('test', 'testpass');
