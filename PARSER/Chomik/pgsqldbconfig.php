<?php
###---EVERYTHING YOU CHANGE/WRITE, TYPE LIKE YOU SEE, BETWEEN ' '---###
return [
	'host' => '127.0.0.1',      	    #addres for server where the data will be stored
	'port' => '5432',					#port to connect to the server
	'dbname' => 'testowka',				#name of databese for data
	'user' => 'postgres',				#login for postgres user
	'pass' => '4dministracj!',			#password for pustgres user
    'table' => 'homesss',				#main table name, where advertise data will be stored
    'datatable' => 'lastupdate',		#name of table witch keeps date and service name of last_update

//------------------------------------	#there are names of columns for data under this line, at the right side
	'tab1col1' => 'itemid',				#default type of this column is: varchar(5)
	'tab1col2' => 'datatrackid',		#default type of this column is: bigint 		//(longint)
	'tab1col3' => 'city',				#default type of this column is: varchar(99)
	'tab1col4' => 'value',				#default type of this column is: int
	'tab1col5' => 'field',				#default type of this column is: real
	'tab1col6' => 'rooms',				#default type of this column is: varchar(3)
	'tab1col7' => 'url',				#default type of this column is: varchar(255)
	'tab1col8' => 'date',				#default type of this column is: timestamptz
//	'tab1col9' => '',					#!!!---UNUSED COLUMN---!!!
	'datatabcol1' => 'id',				#default type of this column is: smallint		//(shortint)
	'datatabcol2' => 'vendor',			#default type of this column is: varchar(30)
	'datatabcol3' => 'date'				#default type of this column is: varchar(20)
	];
/*
$host="127.0.0.1";
$port="5432";
$dbname="testowa";
$user="postgres";
$pass="4dministracj!";
*/
?>