<?php
session_set_save_handler('_open',
                         '_close',
                         '_read',
                         '_write',
                         '_destroy',
                         '_clean');
						 

						 


						 
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

 
	$query = "INSERT INTO session_temp VALUES('$sessionID', now()+interval '1440 seconds', '$session_data')";
	$result = pg_query($query);
 
	if (! $result) {
 
		$query = "UPDATE session_temp SET expiration =  now()+interval '1440 seconds', data = '$session_data' WHERE sessionID = '$sessionID' AND (expiration > now())";
 
		$result = pg_query($query);
	}
}
	
 
 
 /*
 * pg_session_read()
 * Reads the session data from the database
 */
 function pg_session_read($sessionID) {
	$query = "SELECT data FROM session_temp WHERE sessionID = '$sessionID' AND (expiration > now())";
	$result = pg_query($query);

	if (pg_num_rows($result)) {
		$row=pg_fetch_assoc($result);
		$data = $row['data'];
		return $data;
	} 
	else {
	return "";
	}
 } 
 
 
 
 /*
 * pg_session_destroy()
 * Deletes all session information having input sessionID (only one row)
 */
 function pg_session_destroy($sessionID) {
 
	$query = "DELETE FROM session_temp WHERE sessionID = '$sessionID'";
 	$result = pg_query($query);
 
 }  
 
 
 /*
 * pg_session_garbage_collect()
 * Deletes all sessions that have expired.
 */
 function pg_session_garbage_collect($maxlifetime) {
 
	$query = "DELETE FROM session_temp WHERE expiration <now()"; 
 	$result = pg_query($query);
	
	return pg_affected_rows($result);
 
 } 
 
?>