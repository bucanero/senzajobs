<?php
error_reporting(E_ALL & ~E_NOTICE);
/*****************************************************************************
/*Copyright (C) 2011 Damian Parrino [ dparrino@gmail.com ]
/*****************************************************************************

Basado en:
	* Taifa Jobs >> http://sourceforge.net/projects/taifajobs/
	* Job Finder >> http://sourceforge.net/projects/jobfinder/

/*****************************************************************************/

require_once('config.php');

function db_connect()
{
	$conn = mysql_connect(HOST .":". PORT, USER, PASS);
	if (!$conn)
		die('Could not connect: ' . mysql_error());

	mysql_select_db(DB);
	return $conn;
}

//close a connection
function db_close($conn)
{
	mysql_close($conn);
}

function query($strsql,$conn)
{
	$rs = mysql_query($strsql,$conn);
	return $rs;
}

function num_rows($rs)
{
	return @mysql_num_rows($rs); 
}

function fetch_array($rs)
{
	return mysql_fetch_array($rs);
}

function fetch_object($rs)
{
	return mysql_fetch_object($rs);
}

function free_result($rs)
{
	@mysql_free_result($rs);
}

function data_seek($rs,$cnt)
{
	@mysql_data_seek($rs, $cnt);
}

function error()
{
	return mysql_error();
}

function dbRowExists($sql)
{
	$conn=db_connect();
	$results=query($sql,$conn);
	$numrows = num_rows($results);
	free_result($results);		
	return ($numrows > 0);
}

?>
