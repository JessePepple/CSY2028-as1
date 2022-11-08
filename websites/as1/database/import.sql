CREATE DATABASE IF NOT EXISTS assignment1;

use assignment1;

CREATE TABLE IF NOT EXISTS users(id int not null auto_increment, name varchar(40) not null, email varchar(80) not null unique, password varchar(80) not null, is_admin int not null default '0', primary key(id));

-- password: 123456 
INSERT IGNORE INTO users(id, name, email, password, is_admin)
VALUES('1', 'admin', 'admin@test.com', '$2y$10$4gUDYfIyHBDsiqdUy90MUu.IJvQYs3kXS7Xk/lHLG3Bn/.h7oln4m', '1');
