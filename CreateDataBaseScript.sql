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
    iNum integer auto_increment,
	iName varchar(30) not null,
	iDesc TEXT,
	iCost float,
	iImage varchar(80),
    index (iNum)
);

create table orders (
    oNum integer auto_increment,
	oDiscount_id integer,
	oItem_id integer not null,
	oQuantity integer,
	oCusName varchar(30) not null,
	oCusPhone varchar(30),
	oCusEmail varchar(80),
    index (oNum),
	foreign key (oItem_id) references items(iNum)
);

create table formula (
    fNum integer auto_increment,
	fTimeLeft integer,
	fQuantityStep integer,
	fDiscountStep float,
	fMaxDiscount float,
	fDiscountType varchar(1),
    index (fNum)
);

create table discount (
    dNum integer auto_increment,
	dItem_id integer not null,
	dFormula_id integer,
	dCode varchar(50),
	dStart integer,
	dEnd integer,
    index (dNum),
	unique key discount_code (dCode),
	foreign key (dItem_id) references items(iNum)
);
    
# populate the database tables
insert into items (iname, icost, iImage) values ('Pencil', 0.53, 'pencil.jpg' );
insert into items (iname, icost, iImage) values ('Eraser', 0.73, 'eraser.jpg' );
insert into items (iname, icost, iImage) values ('Paper', 1.50, 'paper.jpg' );

insert into formula (ftimeLeft,fquantityStep,fdiscountStep, fmaxDiscount) values ( 10, 5, 8.0, 40.0);

insert into discount (ditem_id, dformula_id, dCode, dStart,dEnd) values (1,1, 'dXc17sP', 041320,042020);

insert into orders (odiscount_id, oitem_id, oQuantity, oCusName, oCusPhone, oCusEmail) values (1,1,10, 'PencilMan', '555-5555', 'pencilman@pencils.com');
select * from items;
select * from formula;
select * from discount;
select * from orders;

