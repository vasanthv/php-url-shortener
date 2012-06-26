<?php

class hycus_urlshortener{

	function hycus_urlshortener(){

		//the url of your website or the url from where you are runing this script.
		$this->baseurl = 'http://localhost/url-shortener/';
		//uidlength should be minimum 5
		$this->uidlength = '6';
		$this->dbhost = 'localhost';
		$this->dbuser = 'root';
		$this->dbpass = 'poiu7890';
		$this->dbname = 'urlshortener';
		$this->dbtablename = 'shorturls';

	}
	function get_uid(){

		//set the random id length
		$random_id_length = $this->uidlength;

		//generate a random id encrypt it and store it in $rnd_id
		$rnd_id = crypt(uniqid(rand(),1));

		//to remove any slashes that might have come
		$rnd_id = strip_tags(stripslashes($rnd_id));

		//Removing any . or / and reversing the string
		$rnd_id = str_replace(".","",$rnd_id);
		$rnd_id = strrev(str_replace("/","",$rnd_id));

		//finally I take the first 10 characters from the $rnd_id
		$rnd_id = substr($rnd_id,0,$random_id_length);

		return $rnd_id;
	}
	function create_shorturl($longurl){
		$shorturl_suffix = $this->get_uid();
		$query = 'INSERT into '.$this->dbtablename.' (shorturlid, longurl) values ("'.$shorturl_suffix.'", "'.$longurl.'")';
		$result = mysql_query($query);

		return $shorturl_suffix;
	}
	function get_shorturl($longurl){
		$db = $this->connectdb();
		$query = 'SELECT shorturlid FROM '.$this->dbtablename.' WHERE longurl = "'.$longurl.'"';
		$result = mysql_query($query);
		$data = mysql_fetch_object($result);
		if($data){
			$shorturlid = $data->shorturlid;
		}else{
			$shorturlid = $this->create_shorturl($longurl);
		}
		return $this->baseurl.''.$shorturlid;
	}
	function get_longurl($shorturl){

		$shorturlid = str_replace($this->baseurl,'',$shorturl) ;

		$db = $this->connectdb();
		$query = 'SELECT id, longurl FROM '.$this->dbtablename.' WHERE shorturlid = "'.$shorturlid.'"';
		$result = mysql_query($query);
		$data = mysql_fetch_object($result);

		if($data){
			$longurl = $data->longurl;

			//updating the number of hits
			$query = 'UPDATE '.$this->dbtablename.' SET hits= hits + 1 WHERE id="'.$data->id.'"';
			$result = mysql_query($query);

			return $data->longurl;
		}else{
			return false;
		}
	}
	function goto_url($shorturl){
		$longurl = $this->get_longurl($shorturl);

		if($longurl){
			if (headers_sent()) {
				//Javascript redirection script
				echo "<script type=\"text/javascript\">document.location.href='$longurl';</script>\n";
			} else {
				header( 'HTTP/1.1 301 Moved Permanently' );
				header( 'Location: ' . $longurl );
			}
		}else{
			echo 'No related Long URL in our Database.';
		}
	}
	function get_hits($shorturl){
		$shorturlid = str_replace($this->baseurl,'',$shorturl) ;

		$db = $this->connectdb();
		$query = 'SELECT hits FROM '.$this->dbtablename.' WHERE shorturlid = "'.$shorturlid.'"';
		$result = mysql_query($query);
		$data = mysql_fetch_object($result);
		return $data->hits;
	}
	function connectdb(){

		//connecting to the database
		$link = mysql_connect($this->dbhost, $this->dbuser, $this->dbpass);
		mysql_select_db($this->dbname);

		return $link;
	}
}
?>
