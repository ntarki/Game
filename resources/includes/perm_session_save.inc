<?php
session_set_save_handler('_open',
                         '_close',
                         '_read',
                         '_write',
                         '_destroy',
                         '_clean');
						 

function _open()
{
	$dbname ='optic';
	$user = 'EuwXP76HrU';
	$password_db ='5ss3V4Lk5G5K3D473nCwV4gU';
	$db = pg_connect("host=localhost port=5432 dbname=$dbname user=$user password=$password_db")
    
}
 
function _close()
{
    pg_close();
}

						 
						 
?>