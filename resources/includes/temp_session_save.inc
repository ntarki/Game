<?php




$dbname ='optic';
$user = 'EuwXP76HrU';
$password_db ='5ss3V4Lk5G5K3D473nCwV4gU';
$db = pg_connect("host=localhost port=5432 dbname=$dbname user=$user password=$password_db");

 /*
 * pg_session_open()
 * Opens a persistent server connection and selects the database.
 */

 function pg_session_open($session_savepath, $session_name) {
	$dbname ='optic';
	$user = 'EuwXP76HrU';
	$password_db ='5ss3V4Lk5G5K3D473nCwV4gU';
	$db = pg_connect("host=localhost port=5432 dbname=$dbname user=$user password=$password_db");
}


 /*
 * pg_session_close()
 * Doesn't actually do anything since the server connection is
 * persistent. Keep in mind that although this function
 * doesn't do anything in this particular implementation, it
 * must nonetheless be defined.
 */

 function pg_session_close() {
	return true;
 }

  /*
 * pg_session_write()
 * This function writes the session data to the database.
 * If that SID already exists, then the existing data will be updated.
 */


 function pg_session_write($sessionID, $session_data) {

	$query = "SELECT sessionID FROM temp_login WHERE sessionID = '$sessionID'";
	$result = pg_num_rows(pg_query($query));


	/*Check if a cookie exists*/
	if(isset($_COOKIE['persrandom'])){

		/*Check if a cookie exists*/
		if (preg_match("/^[a-zA-Z0-9]{256,256}$/",  sanitize_input($_COOKIE['persrandom']))==1) {
			$cookie_value=sanitize_input($_COOKIE['persrandom']);
			/*Look for it in the database*/
			$cookie_retrieval = pg_query("SELECT  pers_used FROM persistent_login WHERE pers_token='".$cookie_value."' AND (pers_expiry > now()) ;");

			/*Check if a match is found*/
			if ($cookie_retrieval){
				$savetodb='on';
			}else{
					$savetodb='';
			}
		}else{
				$savetodb='';
		}
	}else{
			$savetodb='';
	}

	if ($result==1) {
	$query = "UPDATE temp_login SET expiration =  now()+interval '1440 seconds', data = '".$session_data."' WHERE sessionID = '".$sessionID."' ";
 	$result = pg_query($query);

	if ($savetodb==='on'){

		$query = "UPDATE persistent_login SET pers_expiry =  now()+interval '1 month', pers_data = '".$session_data."' WHERE pers_token = '".$cookie_value."' ";
	 	$result = pg_query($query);

	}

	}
	else{

		$query = "INSERT INTO temp_login(sessionID,expiration,data) VALUES('".$sessionID."', now()+interval '1440 seconds', '".$session_data."')";
		$result = pg_query($query);

		if ($savetodb==='on'){

			$query = "UPDATE persistent_login SET pers_expiry =  now()+interval '1 month', pers_data = '".$session_data."' WHERE pers_token = '".$cookie_value."' ";
			$result = pg_query($query);

		}

	}


	if (! $result) {


	}
}



 /*
 * pg_session_read()
 * Reads the session data from the database
 */

 function pg_session_read($sessionID) {
	$query = "SELECT data FROM temp_login WHERE sessionID = '".$sessionID."'  AND (expiration > now())";
	$result = pg_query($query);
	if (pg_num_rows($result)) {
		$row=pg_fetch_assoc($result);
		$value = $row['data'];
		return $value;
	} else {
	return "";
	}
 }





 /*
 * pg_session_destroy()
 * Deletes all session information having input SID (only one row)
 */
 function pg_session_destroy($sessionID) {

 $query = "DELETE FROM temp_login WHERE sessionID = '".$sessionID."'";

 $result = pg_query($query);

 } // end pg_session_destroy()



 /*
 * pg_session_garbage_collect()
 * Deletes all sessions that have expired.
 */
 function pg_session_garbage_collect($lifetime) {

 $query = "DELETE FROM temp_login WHERE (expiration > now());";
 $result = pg_query($query);

 return pg_affected_rows($result);

 } // end pg_session_garbage_collect()



?>
