CREATE DATABASE IF NOT EXISTS assignment1;

use assignment1;
CREATE TABLE IF NOT EXISTS users(id int not null auto_increment, name varchar(40) not null, email varchar(80) not null unique, password varchar(255) not null, is_admin int not null default '0', primary key(id));

-- password: 123456 
INSERT IGNORE INTO users(id, name, email, password, is_admin)
VALUES('1', 'admin', 'admin@test.com', '$2y$10$4gUDYfIyHBDsiqdUy90MUu.IJvQYs3kXS7Xk/lHLG3Bn/.h7oln4m', '1');

CREATE TABLE if not exists category(id INT NOT null auto_increment, name varchar(60) not null, primary key(id));

insert ignore into category(id, name) values('1', 'Electronics');
insert ignore into category(id, name) values('2', 'Home and garden');
insert ignore into category(id, name) values('3', 'Fashion');
insert ignore into category(id, name) values('4', 'Sport');

CREATE TABLE if not exists auction(id int not null auto_increment, title varchar(255) not null, description text not null,
categoryId int not null, endDate date not null, image varchar(100) not null, foreign key(categoryId) references category(id), primary key(id));