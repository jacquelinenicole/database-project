# SQL commands to create and populate the MySQL database for
# COP 4710 Group Project
#
# delete the database if it already exists
drop database if exists COP4710;

#create a new database named COP4710
create database COP4710;

#switch to the new database
use COP4710;

#create the schemas for the four relations in this database
create table items (
    inum integer auto_increment,
	iname varchar(30) not null,
	icost float,
	iImage varchar(80),
    index (inum)
);

create table orders (
    onum integer auto_increment,
	discount_id integer,
	item_id integer not null,
	oQuantity integer,
	oCusName varchar(30) not null,
	oCusPhone varchar(30),
	oCusEmail varchar(80),
    index (onum),
	foreign key (item_id) references items(inum)
);

create table formula (
    fnum integer auto_increment,
	timeLeft integer,
	quantityStep integer,
	discountStep float,
	maxDiscount float,
    index (fnum)
);

create table discount (
    dnum integer auto_increment,
	item_id integer not null,
	formula_id integer,
	dCode varchar(50),
	dStart integer,
	dEnd integer,
    index (dnum),
	unique key discount_code (dCode),
	foreign key (item_id) references items(inum)
);
    
# populate the database tables
insert into items (iname, icost, iImage) values ('Pencil', 0.53, 'pencil.jpg' );
insert into items (iname, icost, iImage) values ('Eraser', 0.73, 'eraser.jpg' );
insert into items (iname, icost, iImage) values ('Paper', 1.50, 'paper.jpg' );

insert into formula (timeLeft,quantityStep,discountStep, maxDiscount) values ( 10, 5, 8.0, 40.0);

insert into discount (item_id, formula_id, dCode, dStart,dEnd) values (1,1, 'dXc17sP', 041320,042020);

insert into orders (discount_id, item_id, oQuantity, oCusName, oCusPhone, oCusEmail) values (1,1,10, 'PencilMan', '555-5555', 'pencilman@pencils.com');
select * from items;
select * from formula;
select * from discount;
select * from orders;

