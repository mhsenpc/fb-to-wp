<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  @session_start();
  if(empty($_SESSION["username"]))
  	exit("You Should Login First!");	
  	
  include_once("getcmsinfo.php");

class Cms extends CI_Controller {
	public function  publishnow(){
	   

  		$this->load->helper('text');
 		//first fill needed fields
  		if(!empty($_POST['image']))
  			$image="<img src=\'https://graph.facebook.com/".$_POST['image']."/picture\' />" ;
  		else
 			$image="";

  		$text= @addslashes($_POST['text']);

  		$post_content = $image."\n".$text; 
 		$post_title= @word_limiter( $text,4);
  		
 		$publishDate  = date('Y-m-d H:i:s');  
  		
  		echo "This Post Is On Your Site Now! ";
  		$this->PublishPost($post_title,$post_content,$publishDate,"publish");

		

	}
    
	public function  publishin(){
		$this->load->helper('text');
		//first fill needed fields
		if(!empty($_POST['image']))
			$image="<img src=\'https://graph.facebook.com/".$_POST['image']."/picture\' />" ;
		else
			$image="";
			
		$text= @addslashes($_POST['text']);
		$post_content = $image."\n".$text; 
		$post_title=word_limiter( $text,4);
		
		//get date and reformat it
		$publishDate =  $_POST['publishdate'];
		$timestamp= strtotime($publishDate);
		$publishDate=  date('Y-m-d H:i:s',$timestamp);  
		
		echo "This Post Will Sent To your Site in ".$publishDate;
		$this->PublishPost($post_title,$post_content,$publishDate,"future");
	}
    
	public function  sendtoqueue(){
		
		$this->load->helper('text');
		$this->load->config();
		
		//first fill needed fields
		if(!empty($_POST['image']))
			$image="<img src=\'https://graph.facebook.com/".$_POST['image']."/picture\' />" ;
		else
			$image="";
			
		$text= @addslashes($_POST['text']);

		$post_content = $image."\n".$text; 
		$post_title=word_limiter( $text,4);
		
		$cmsdata= getcmsinfo();
		//connect ot db
		$cnn= mysql_connect($cmsdata['dbhost'],$cmsdata['dbuser'],$cmsdata['dbpass']);
		mysql_select_db($cmsdata['dbname']);
		mysql_query('SET NAMES \'utf8\'');
		mysql_set_charset('utf8');
		
		//get last date 
		$sql="select max(id) from ".$cmsdata['tbl_prefix']."posts";
		//echo $sql;
		$t1= mysql_query($sql,$cnn);
		$lastid = mysql_fetch_array($t1);
		$lastid = $lastid[0]; 
		
		$publishDate="";
		if(empty($lastid)){
			$publishDate  = date('Y-m-d H:i:s');  
		}
		else{
			$t2=mysql_query("select post_date from ".$cmsdata['tbl_prefix']."posts where id=".$lastid , $cnn);
			$lastpublishdate= mysql_fetch_assoc($t2);
			$lastpublishdate = $lastpublishdate["post_date"];
			
			$publishDate= date('Y-m-d H:i:s', strtotime($lastpublishdate .' + '.$this->config->item("interval").' hours'));
		}
		

		echo "This Post Will Sent To your Site in ".$publishDate;
		$this->PublishPost($post_title,$post_content,$publishDate,"future");
		
	}

	private function PublishPost($title,$content,$publishDate,$post_status){
		$this->load->helper('text');
		$this->load->config();
		
		$cmsdata= getcmsinfo();
		$cnn= mysql_connect($cmsdata['dbhost'],$cmsdata['dbuser'],$cmsdata['dbpass']);
		mysql_select_db($cmsdata['dbname'],$cnn);
		mysql_query('SET NAMES \'utf8\'');
		mysql_set_charset('utf8');
		
		$link=mysql_query( "select id from ".$cmsdata['tbl_prefix']."users",$cnn);
		$authorid=mysql_fetch_assoc( $link);
		$authorid=$authorid["id"];
		$post_name=md5($title);
		$post_type="post";
		
		$tblname=$cmsdata['tbl_prefix']."posts";
		
		$guid="";
		//get last date 
		$t1= mysql_query("select max(id) from ".$cmsdata['tbl_prefix']."posts",$cnn);
		$lastid = mysql_fetch_array($t1);
		$lastid=$lastid[0];

		if(empty($lastid))
			$guid= $this->config->item("cms_url")."?p=1";
		else
			$guid=$this->config->item("cms_url")."?p=".($lastid+1);
		
		//insert new row in wordpress database
		$sql="insert into $tblname (post_author,post_date,post_date_gmt,post_content,post_title,post_status,post_name,guid,post_type) ";
		$sql.="values('$authorid','$publishDate','$publishDate','$content','$title','$post_status','$post_name','$guid','$post_type')";  
		
//echo $sql;
		mysql_query($sql,$cnn);
		//echo "Query Success Fully Executed!";
	}	

}