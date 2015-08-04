<?php

	$db_host='sql.byethost22.org';
	$db_name='kurdabdo_fbtoblog_main';
	$db_username='kurdabdo_mohsen';
	$db_passowrd='12)!1s~PMV87';
	
    $username= addslashes( strip_tags( $_POST['username']));
    $password= addslashes( strip_tags( $_POST['password']));
    $siteaddress= addslashes( strip_tags( $_POST['siteaddress']));
    
    $cnn= mysql_connect($db_host,$db_username,$db_passowrd);
    mysql_select_db($db_name,$cnn);
    $link= mysql_query("select count(*) from users where username='$username' and password='$password' and siteaddress='$siteaddress'",$cnn);
    $record=mysql_fetch_array($link);
    if($record[0]>0)
        echo "1";
    else
        echo "0";