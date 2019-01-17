<?php
###---EVERYTHING YOU CHANGE/WRITE, TYPE LIKE YOU SEE, BETWEEN ' '---###
return [
	'host' => '127.0.0.1',      	    #addres for server where the data will be stored
	'port' => '5432',					#port to connect to the server
	'dbname' => 'Chomik',				#name of databese for data
	'user' => 'postgres',				#login for postgres user
	'pass' => 'postgres',				#password for pustgres user
    'table' => '"HOMES"',				#main table name, where advertise data will be stored
    'datatable' => 'LASTUPDATE',			#name of table witch keeps date and service name of last_update

//------------------------------------	#there are names of columns for data under this line, at the right side
	'tab1col1' => '"ITEMID"',				#default type of this column is: varchar(5)
	'tab1col2' => '"DATATRACKID"',		#default type of this column is: bigint 		//(longint)
	'tab1col3' => '"CITY"',				#default type of this column is: varchar(99)
	'tab1col4' => '"VALUE"',				#default type of this column is: int
	'tab1col5' => '"FIELD"',				#default type of this column is: real
	'tab1col6' => '"ROOMS"',				#default type of this column is: varchar(3)
	'tab1col7' => '"URL"',				#default type of this column is: varchar(255)
	'tab1col8' => '"DATE"',				#default type of this column is: timestamptz
//	'tab1col9' => '',					#!!!---UNUSED COLUMN---!!!
	'datatabcol1' => '"ID"',				#default type of this column is: smallint		//(shortint)
	'datatabcol2' => '"VENDOR"',			#default type of this column is: varchar(30)
	'datatabcol3' => '"DATE"'				#default type of this column is: varchar(20)
	];
/*
$host="127.0.0.1";
$port="5432";
$dbname="testowa";
$user="postgres";
$pass="4dministracj!";
*/
?>
