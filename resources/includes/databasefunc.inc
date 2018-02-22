<?php
$dbname ='optic';
$user = 'EuwXP76HrU';
$password_db ='5ss3V4Lk5G5K3D473nCwV4gU';
$db = pg_connect("host=localhost port=5432 dbname=$dbname user=$user password=$password_db") or error('Could not connect to database: '. pg_last_error());

function optic_query($db,$query) {
	$result = pg_query($db,$query) or error ('Query failed: '. pg_last_error());
	return $result;
}

function error($type) {
echo $type;
}

function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
}

function getToken($length){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    for($i=0;$i<$length;$i++){
        $token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
    }
    return $token;
}


function tokenSession(){

    $out= getToken(128);
	$dbname ='optic';
$user = 'EuwXP76HrU';
$password_db ='5ss3V4Lk5G5K3D473nCwV4gU';
$db = pg_connect("host=localhost port=5432 dbname=$dbname user=$user password=$password_db") or error('Could not connect to database: '. pg_last_error());
	$query = "SELECT sessionID FROM temp_login WHERE sessionID = '$out';";
	$token_exists = pg_num_rows(optic_query($db,$query));
			
	/*In case the cookie fails to be inserted try again*/
	while ($token_exists >0) {
				
		/*Create new cookie key*/
		 $out= getToken(128);
				
		/*Check if a  session with the same key exists in database*/
		$query = "SELECT sessionID FROM temp_login WHERE sessionID = '$out';";
		$token_created = optic_query($db,$query);
	}
	return $out;
}	

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   #check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   #to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


// Sanitize input data
function sanitize_input($data) {
	$data = trim($data);
	$data = htmlspecialchars($data);
	return $data;
}

function is_logged_cookie() {

$dbname ='optic';
$user = 'EuwXP76HrU';
$password_db ='5ss3V4Lk5G5K3D473nCwV4gU';
$db = pg_connect("host=localhost port=5432 dbname=$dbname user=$user password=$password_db") or error('Could not connect to database: '. pg_last_error());

$cookie_name = 'persrandom';

/*Check if a cookie exists*/
if(isset($_COOKIE[$cookie_name])){
	
	/*Check if a cookie exists*/	
	if (preg_match("/^[a-zA-Z0-9]{256,256}$/",  sanitize_input($_COOKIE[$cookie_name]))==1) {
		$cookie_value=sanitize_input($_COOKIE[$cookie_name]);
		/*Look for it in the database*/
		$cookie_retrieval = optic_query($db,"SELECT pers_data, pers_used FROM persistent_login WHERE pers_token='".$cookie_value."' AND (pers_expiry > now()) ;");
		
		/*Check if a match is found*/
		if ($cookie_retrieval){
		
		    /*Store the data retrieved from database in temporary variables*/
			$temp_save = pg_fetch_row($cookie_retrieval);
			$cookie_token_used = $temp_save[1];
			$cookie_session_data = $temp_save[0];
			
		    /*Check if a valid unused token exists*/
			if ($cookie_token_used==='0') {
		
				/*Load data into session*/
				session_decode($cookie_session_data);
			
				/*Create new cookie for single use persistent login*/
				$cookie_token_generated = getToken(256);
				$query = "INSERT INTO persistent_login (pers_token, pers_data, pers_used, pers_expiry) VALUES ('".$cookie_token_generated."','".$cookie_session_data."' ,0, now()+interval '30 days')";
				$cookie_save_db = optic_query($db,$query);
			
				/*In case the cookie fails to be inserted try again*/
				while (!$cookie_save_db) {
				
					/*Create new cookie key*/
					$cookie_token_generated=getToken(256);
				
					/*Store key in database*/
					$query = "INSERT INTO persistent_login (pers_token, pers_data, pers_used) VALUES ('".$cookie_token_generated."','".$cookie_session_data."' ,0) ";
					$cookie_save_db = optic_query($db,$query);
				}
			
				/*Set the cookie for use login*/			
				setcookie($cookie_name, $cookie_token_generated, time() + (86400 * 30), "/");
				
				/*Delete the old cookie for user*/
				$query = "DELETE FROM persistent_login WHERE  pers_token='".$cookie_value."';";
				$cookie_delete = optic_query($db,$query);	
	return true;				
			
			}

		    /*Check if a valid used token exists*/	
			elseif ($cookie_token_used==='1'){
			
				/*Maybe an attack*/
				/*Delete the token for user*/
				$query = "DELETE * FROM persistent_login WHERE  pers_token='".$cookie_value."';";
				$cookie_delete = optic_query($db,$query);
				
				/*Delete cookie for user*/
				setcookie($cookie_name, "", time() - 3600);	
				
				/*Destroy session*/
				session_destroy();
			
				/*Suspects table*/
				$query = "INSERT INTO suspects_cookie (type, ip_add) VALUES ('Old token','".getRealIpAddr()."');";
				$cookie_retrieval =optic_query($db,$query);
			return false;
			}
		}	
		
		/*Do this for all invalid tokens*/		
		else {
		
			/*Possible attack*/
			/*Delete all cookies*/
			setcookie($cookie_name, "", time() - 3600);	
				
			/*Destroy session*/
			session_destroy();
				
			/*Suspects table*/
			$query = "INSERT INTO suspects_cookie (type, ip_add) VALUES ('Invalid token','".getRealIpAddr()."');";
			$cookie_retrieval =optic_query($db,$query);
				return false;
		}

	}
	
	/*This motherfucker messing with cookies*/
	else {
		
		/*Delete his cookie*/
		setcookie($cookie_name, "", time() - 3600);	
		
		/*Destroy session*/
		session_destroy();
		
		/*Add him to suspects table*/
		$query = "INSERT INTO suspects_cookie (type, ip_add) VALUES ('Possible Inject Attempt','".getRealIpAddr()."');";
		$cookie_retrieval =optic_query($db,$query);
			return false;
	}

}	

else{

    /* No cookie*/
	$_SESSION['cookie']['status']='Disabled';	
		return false;
}
	


	
	

	

}

function is_logged() {
	$cookie_name='persrandom';
	$cookie_name_temp = 'PHPSESSID';
	
	
	if(isset($_COOKIE[$cookie_name_temp])){
	
		session_start();	
		
		if (isset($_SESSION['cookie']['status']) && $_SESSION['cookie']['status']==='Enabled'){
			
			return true;
		}
		else
		
		return false;
	
	}elseif (isset($_COOKIE[$cookie_name])) {
		
		session_start();
	
		/*If he doesn't enable login form*/
		return is_logged_cookie();
	
	}
	
	else{
					 return false;
	}
	}
	if (isset($_COOKIE['acceptcookies']) && ($_COOKIE['acceptcookies']==='yes')) {


	}
	else{
	
	if (isset($_POST['cookie'])){
		setcookie("acceptcookies","yes", time() + (10 * 365 * 24 * 60 * 60));
	}	
	else {	?>
	<style>.fixedElement {
    background-color: #c0c0c0;
    position:fixed;
text-align:center;
font-size:200%;
    top:0;
    width:100%;
    z-index:100;
	}</style>
	 <script>
	 $(window).scroll(function(e){ 
	$el = $('.fixedElement'); 
	if ($(this).scrollTop() > 200 && $el.css('position') != 'fixed'){ 
    $('.fixedElement').css({'position': 'fixed', 'top': '0px'}); 
	}
	if ($(this).scrollTop() < 200 && $el.css('position') == 'fixed')
	{
    $('.fixedElement').css({'position': 'static', 'top': '0px'}); 
	} 
	});
	 </script>
	 
	<div class="fixedElement"><p>
	<form class="pure-form" name ="cookies" method="post" >
    <label>We use cookies for the login system, we store no personal details&nbsp;&nbsp;&nbsp;&nbsp;<label><button type="submit"  name="cookie" id="cookie" class="pure-button pure-button-primary">I understand</button>
	</form></p>
	</div>
	<?php }
			
	
	
}

function user_logout(){
$dbname ='optic';
$user = 'EuwXP76HrU';
$password_db ='5ss3V4Lk5G5K3D473nCwV4gU';
$db = pg_connect("host=localhost port=5432 dbname=$dbname user=$user password=$password_db") or error('Could not connect to database: '. pg_last_error());

$cookie_name = 'persrandom';
$cookie_name_temp = 'PHPSESSID'; 
	session_destroy();
	pg_session_destroy(session_id());	
	setcookie($cookie_name_temp, "", time() - 3600);

	
	if(isset($_COOKIE[$cookie_name])){
	
		if (preg_match("/^[a-zA-Z0-9]{256,256}$/",  sanitize_input($_COOKIE[$cookie_name]))==1) {
			
			$cookie_value=sanitize_input($_COOKIE[$cookie_name]);
			/*Look for it in the database*/
			$cookie_retrieval = optic_query($db,"SELECT pers_data, pers_used FROM persistent_login WHERE pers_token='".$cookie_value."' AND (pers_expiry > now()) ;");
		
			/*Check if a match is found*/
			if ($cookie_retrieval){
			
				$query = "DELETE FROM persistent_login WHERE pers_token ='".$cookie_value."'";
				$cookie_save_db =  optic_query($db,$query);
				/*Delete his cookie*/
				setcookie($cookie_name, "", time() - 3600);	

			}
		}
	}
	
	header('Location: login.php');
}


?>