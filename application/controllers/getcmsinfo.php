<?php
	function getcmsinfo(){
		$filename="../wp-config.php";
	    if(file_exists($filename)){
	        include_once($filename);
			global $table_prefix;
	        $cmsdata= array('dbhost'=>DB_HOST,'dbname'=>DB_NAME,'dbuser'=>DB_USER,'dbpass'=>DB_PASSWORD,'tbl_prefix'=>$table_prefix,'connected'=>'1');
	    }
	    else{
	        $cmsdata=array('connected'=>'0');
	    }
		return $cmsdata;
	}

?>