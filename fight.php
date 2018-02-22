<?php
// Including the database function
$start = microtime(true);
$datachange['status']='enabled';
$use=0;


// Including the database function
include './resources/includes/databasefunc.inc';
// Including the database function
include './resources/includes/temp_session_save.inc';

include './resources/includes/items.php';
include './resources/includes/abilities.php';

session_set_save_handler('pg_session_open','pg_session_close','pg_session_read','pg_session_write','pg_session_destroy','pg_session_garbage_collect', 'tokenSession');

//Need to check if the user is already logged in
$user_logged_in = is_logged();

if (!$user_logged_in) {

	header('location: login.php');

}

if (!$_SESSION['character']['char_name']) {
	header('location: character.php');
}


if (isset($_POST['single'])) {
	header('location: single.php');
}

if (isset($_POST['pvp'])) {
	header('location: multi.php');
}

if (isset($_POST['tour'])) {
	header('location: character.php');
}

//add str
if (isset($_POST['add_strength'])) {
	pg_query("UPDATE characters_main  SET points=points-1,strength=strength+1 WHERE (char_name='".$_SESSION['character']['char_name']."' AND  (points-1>=0))");
}

//add agi
if (isset($_POST['add_agility'])) {
	pg_query("UPDATE characters_main  SET points=points-1,agility=agility+1 WHERE (char_name='".$_SESSION['character']['char_name']."' AND (points-1>=0))");
}

//add int
if (isset($_POST['add_intelligence'])) {
	pg_query("UPDATE characters_main  SET points=points-1,intelligence=intelligence+1 WHERE (char_name='".$_SESSION['character']['char_name']."' AND  (points-1>=0))");
}

//add vit
if (isset($_POST['add_vitality'])) {
	pg_query("UPDATE characters_main  SET points=points-1,vitality=vitality+1 WHERE (char_name='".$_SESSION['character']['char_name']."' AND  (points-1>=0))");
}

//add armor
if (isset($_POST['add_armor'])) {
	pg_query("UPDATE characters_main  SET points=points-1,armor=armor+1 WHERE (char_name='".$_SESSION['character']['char_name']."' AND  (points-1>=0))");
}

//add dodge
if (isset($_POST['add_dodge'])) {
	pg_query("UPDATE characters_main  SET points=points-1,dodge=dodge+1 WHERE (char_name='".$_SESSION['character']['char_name']."' AND  (points-1>=0))");
}

//add hit
if (isset($_POST['add_hit'])) {
	pg_query("UPDATE characters_main  SET points=points-1,hit=hit+1 WHERE (char_name='".$_SESSION['character']['char_name']."' AND  (points-1>=0))");
}

//add critical
if (isset($_POST['add_critical'])) {
	pg_query("UPDATE characters_main  SET points=points-1,critical=critical+1 WHERE (char_name='".$_SESSION['character']['char_name']."' AND  (points-1>=0))");
}

//add expgain
if (isset($_POST['add_expgain'])) {
	pg_query("UPDATE characters_main  SET points=points-1,expgain=expgain+1 WHERE (char_name='".$_SESSION['character']['char_name']."' AND  (points-1>=0))");
}

//add goldfind
if (isset($_POST['add_luck'])) {
	pg_query("UPDATE characters_main  SET points=points-1,luck =luck +1 WHERE (char_name='".$_SESSION['character']['char_name']."' AND (points-1>=0))");
}

//set the act based on what is selected for each




//set the  active stats
if (isset($_POST['setactive'])){


	//-------------------
	//weapon1
	//-------------------


	//Return
	$result=pg_fetch_row(pg_query("SELECT act_wep1 FROM characters_main WHERE char_name='".$_SESSION['character']['char_name']."' "));
	if ($result[0]!=='NONE'){
		pg_query("UPDATE characters_weapons SET ".$result[0]."=".$result[0]."+1  WHERE username='".$_SESSION['login']['username']."'");
	}
	//Set None
	pg_query("UPDATE characters_main  SET act_wep1='NONE' WHERE char_name='".$_SESSION['character']['char_name']."' ");
	//Weapons
	if ($_POST['weapon1'] !== 'NONE'){
		//SANITIZE INPUT
		if (preg_match("/^[a-zA-Z0-9_]{1,26}$/",  sanitize_input($_POST['weapon1']))) {
			$_POST['weapon1']=sanitize_input($_POST['weapon1']);
		//
			$result = pg_fetch_row(pg_query( "SELECT ".$_POST['weapon1']." FROM characters_weapons WHERE username='".$_SESSION['login']['username']."' "));

			if ($result[0]>0 ){
				pg_query("UPDATE characters_main  SET act_wep1='".$_POST['weapon1']."' WHERE char_name='".$_SESSION['character']['char_name']."' ");
				pg_query("UPDATE characters_weapons SET ".$_POST['weapon1']."=".$_POST['weapon1']."-1  WHERE username='".$_SESSION['login']['username']."'");
			}
		}
	}



	//-------------------
	//weapon2
	//-------------------


	//Return
	$result=pg_fetch_row(pg_query("SELECT act_wep2 FROM characters_main WHERE char_name='".$_SESSION['character']['char_name']."' "));
	if ($result[0]!=='NONE'){
		pg_query("UPDATE characters_weapons SET ".$result[0]."=".$result[0]."+1  WHERE username='".$_SESSION['login']['username']."'");
	}
	//Set NONE
	pg_query("UPDATE characters_main  SET act_wep2='NONE' WHERE char_name='".$_SESSION['character']['char_name']."' ");
	//Weapon2
	if ($_POST['weapon2'] !== 'NONE'){
		//SANITIZE INPUT
		if (preg_match("/^[a-zA-Z0-9_]{1,26}$/",  sanitize_input($_POST['weapon2']))) {
			$_POST['weapon2']=sanitize_input($_POST['weapon2']);
		//
			$result = pg_fetch_row(pg_query( "SELECT ".$_POST['weapon2']." FROM characters_weapons WHERE username='".$_SESSION['login']['username']."' "));
			if ($result[0]>0){
				if ($_POST['weapon1']===$_POST['weapon2']) {
					$warning_weapon='true';
				}
				else {
					pg_query("UPDATE characters_main  SET act_wep2='".$_POST['weapon2']."' WHERE char_name='".$_SESSION['character']['char_name']."' ");
					pg_query("UPDATE characters_weapons SET ".$_POST['weapon2']."=".$_POST['weapon2']."-1  WHERE username='".$_SESSION['login']['username']."'");
				}
			}
		}
	}

	//Armor


	//-------------------
	//helm
	//-------------------


	//Return
	$result=pg_fetch_row(pg_query("SELECT act_helmet FROM characters_main WHERE char_name='".$_SESSION['character']['char_name']."' "));
	if ($result[0]!=='NONE'){
		pg_query("UPDATE characters_armor SET ".$result[0]."=".$result[0]."+1  WHERE username='".$_SESSION['login']['username']."'");
	}
	//Set None
	pg_query("UPDATE characters_main  SET act_helmet='NONE' WHERE char_name='".$_SESSION['character']['char_name']."' ");

	//helm
	if ($_POST['helmet'] !== 'NONE'){
		//SANITIZE INPUT
		if (preg_match("/^[a-zA-Z0-9_]{1,26}$/",  sanitize_input($_POST['helmet']))) {
			$_POST['helmet']=sanitize_input($_POST['helmet']);
		//
			$result = pg_fetch_row(pg_query( "SELECT ".$_POST['helmet']." FROM characters_armor WHERE username='".$_SESSION['login']['username']."' "));
			if ($result[0]>0){
				pg_query("UPDATE characters_main  SET act_helmet='".$_POST['helmet']."' WHERE char_name='".$_SESSION['character']['char_name']."' ");
				pg_query("UPDATE characters_armor SET ".$_POST['helmet']."=".$_POST['helmet']."-1  WHERE username='".$_SESSION['login']['username']."'");
			}
		}
	}


	//-------------------
	//chest
	//-------------------


	//Return
	$result=pg_fetch_row(pg_query("SELECT act_chest FROM characters_main WHERE char_name='".$_SESSION['character']['char_name']."' "));
	if ($result[0]!=='NONE'){
		pg_query("UPDATE characters_armor SET ".$result[0]."=".$result[0]."+1  WHERE username='".$_SESSION['login']['username']."'");
	}
	//Set None
	pg_query("UPDATE characters_main  SET act_chest='NONE' WHERE char_name='".$_SESSION['character']['char_name']."' ");

	//chest
	if ($_POST['chest'] !== 'NONE'){
		//SANITIZE INPUT
		if (preg_match("/^[a-zA-Z0-9_]{1,26}$/",  sanitize_input($_POST['chest']))) {
			$_POST['chest']=sanitize_input($_POST['chest']);
		//
			$result = pg_fetch_row(pg_query( "SELECT ".$_POST['chest']." FROM characters_armor WHERE username='".$_SESSION['login']['username']."' "));
			if ($result[0]>0){
				pg_query("UPDATE characters_main  SET act_chest='".$_POST['chest']."' WHERE char_name='".$_SESSION['character']['char_name']."' ");
				pg_query("UPDATE characters_armor SET ".$_POST['chest']."=".$_POST['chest']."-1  WHERE username='".$_SESSION['login']['username']."'");
			}
		}
	}


	//-------------------
	//gloves
	//-------------------


	//Return
	$result=pg_fetch_row(pg_query("SELECT act_gloves FROM characters_main WHERE char_name='".$_SESSION['character']['char_name']."' "));
	if ($result[0]!=='NONE'){
		pg_query("UPDATE characters_armor SET ".$result[0]."=".$result[0]."+1  WHERE username='".$_SESSION['login']['username']."'");
	}
	//Set NONE
	pg_query("UPDATE characters_main  SET act_gloves='NONE' WHERE char_name='".$_SESSION['character']['char_name']."' ");	//gloves
	if ($_POST['gloves'] !== 'NONE'){
		//SANITIZE INPUT
		if (preg_match("/^[a-zA-Z0-9_]{1,26}$/",  sanitize_input($_POST['gloves']))) {
			$_POST['gloves']=sanitize_input($_POST['gloves']);
		//
			$result = pg_fetch_row(pg_query( "SELECT ".$_POST['gloves']." FROM characters_armor WHERE username='".$_SESSION['login']['username']."' "));
			if ($result[0]>0){
				pg_query("UPDATE characters_main  SET act_gloves='".$_POST['gloves']."' WHERE char_name='".$_SESSION['character']['char_name']."' ");
				pg_query("UPDATE characters_armor SET ".$_POST['gloves']."=".$_POST['gloves']."-1  WHERE username='".$_SESSION['login']['username']."'");
			}
		}
	}

	//-------------------
	//pants
	//-------------------

	//Return
	$result=pg_fetch_row(pg_query("SELECT act_pants FROM characters_main WHERE char_name='".$_SESSION['character']['char_name']."' "));
	if ($result[0]!=='NONE'){
		pg_query("UPDATE characters_armor SET ".$result[0]."=".$result[0]."+1  WHERE username='".$_SESSION['login']['username']."'");
	}
	//Set NONE
	pg_query("UPDATE characters_main  SET act_pants='NONE' WHERE char_name='".$_SESSION['character']['char_name']."' ");


	if ($_POST['pants'] !== 'NONE'){
		//SANITIZE INPUT
		if (preg_match("/^[a-zA-Z0-9_]{1,26}$/",  sanitize_input($_POST['pants']))) {
			$_POST['pants']=sanitize_input($_POST['pants']);
		//
			$result = pg_fetch_row(pg_query( "SELECT ".$_POST['pants']." FROM characters_armor WHERE username='".$_SESSION['login']['username']."' "));
			if ($result[0]>0){
				pg_query("UPDATE characters_main  SET act_pants='".$_POST['pants']."' WHERE char_name='".$_SESSION['character']['char_name']."' ");
				pg_query("UPDATE characters_armor SET ".$_POST['pants']."=".$_POST['pants']."-1  WHERE username='".$_SESSION['login']['username']."'");
			}
		}
	}


	//Return
	$result=pg_fetch_row(pg_query("SELECT act_boots FROM characters_main WHERE char_name='".$_SESSION['character']['char_name']."' "));
	if ($result[0]!=='NONE'){
		pg_query("UPDATE characters_armor SET ".$result[0]."=".$result[0]."+1  WHERE username='".$_SESSION['login']['username']."'");
	}
	//Set NONE
	pg_query("UPDATE characters_main  SET act_boots='NONE' WHERE char_name='".$_SESSION['character']['char_name']."' ");

	//boots
	if ($_POST['boots'] !== 'NONE'){
		//SANITIZE INPUT
		if (preg_match("/^[a-zA-Z0-9_]{1,26}$/",  sanitize_input($_POST['boots']))) {
			$_POST['boots']=sanitize_input($_POST['boots']);
		//
			$result = pg_fetch_row(pg_query( "SELECT ".$_POST['boots']." FROM characters_armor WHERE username='".$_SESSION['login']['username']."' "));
			if ($result[0]>0){
				pg_query("UPDATE characters_main  SET act_boots='".$_POST['boots']."' WHERE char_name='".$_SESSION['character']['char_name']."' ");
				pg_query("UPDATE characters_armor SET ".$_POST['boots']."=".$_POST['boots']."-1  WHERE username='".$_SESSION['login']['username']."'");
			}
		}
	}



	//Return
	$result=pg_fetch_row(pg_query("SELECT act_ring FROM characters_main WHERE char_name='".$_SESSION['character']['char_name']."' "));
	if ($result[0]!=='NONE'){
		pg_query("UPDATE characters_armor SET ".$result[0]."=".$result[0]."+1  WHERE username='".$_SESSION['login']['username']."'");
	}
	//Set NONE
	pg_query("UPDATE characters_main  SET act_ring='NONE' WHERE char_name='".$_SESSION['character']['char_name']."' ");


	//ring
	if ($_POST['ring'] !== 'NONE'){
		//SANITIZE INPUT
		if (preg_match("/^[a-zA-Z0-9_]{1,26}$/",  sanitize_input($_POST['ring']))) {
			$_POST['ring']=sanitize_input($_POST['ring']);
		//
			$result = pg_fetch_row(pg_query( "SELECT ".$_POST['ring']." FROM characters_armor WHERE username='".$_SESSION['login']['username']."' "));
			if ($result[0]>0){
				pg_query("UPDATE characters_main  SET act_ring='".$_POST['ring']."' WHERE char_name='".$_SESSION['character']['char_name']."' ");
				pg_query("UPDATE characters_armor SET ".$_POST['ring']."=".$_POST['ring']."-1  WHERE username='".$_SESSION['login']['username']."'");
			}
		}
	}


	//Return
	$result=pg_fetch_row(pg_query("SELECT act_trinket FROM characters_main WHERE char_name='".$_SESSION['character']['char_name']."' "));
	if ($result[0]!=='NONE'){
		pg_query("UPDATE characters_armor SET ".$result[0]."=".$result[0]."+1  WHERE username='".$_SESSION['login']['username']."'");
	}
	//Set NONE
	pg_query("UPDATE characters_main  SET act_trinket='NONE' WHERE char_name='".$_SESSION['character']['char_name']."' ");

	//trinket
	if ($_POST['trinket'] !== 'NONE'){
		//SANITIZE INPUT
		if (preg_match("/^[a-zA-Z0-9_]{1,26}$/",  sanitize_input($_POST['trinket']))) {
			$_POST['trinket']=sanitize_input($_POST['trinket']);
		//
			$result = pg_fetch_row(pg_query( "SELECT ".$_POST['trinket']." FROM characters_armor WHERE username='".$_SESSION['login']['username']."' "));
			if ($result[0]>0){
				pg_query("UPDATE characters_main  SET act_trinket='".$_POST['trinket']."' WHERE char_name='".$_SESSION['character']['char_name']."' ");
				pg_query("UPDATE characters_armor SET ".$_POST['trinket']."=".$_POST['trinket']."-1  WHERE username='".$_SESSION['login']['username']."'");
			}

		}
	}




	//Items

	//Return
	$result=pg_fetch_row(pg_query("SELECT act_item1 FROM characters_main WHERE char_name='".$_SESSION['character']['char_name']."' "));
	if ($result[0]!=='NONE'){
		pg_query("UPDATE characters_items SET ".$result[0]."=".$result[0]."+1  WHERE username='".$_SESSION['login']['username']."'");
	}

	//Set NONE
	pg_query("UPDATE characters_main  SET act_item1='NONE' WHERE char_name='".$_SESSION['character']['char_name']."' ");

		//item1
	if ($_POST['item1'] !== 'NONE'){
		//SANITIZE INPUT
		if (preg_match("/^[a-zA-Z0-9_]{1,26}$/",  sanitize_input($_POST['item1']))) {
			$_POST['item1']=sanitize_input($_POST['item1']);
		//
			$result = pg_fetch_row(pg_query( "SELECT ".$_POST['item1']." FROM characters_items WHERE username='".$_SESSION['login']['username']."' "));
			if ($result[0]>0){
				pg_query("UPDATE characters_main  SET act_item1='".$_POST['item1']."' WHERE char_name='".$_SESSION['character']['char_name']."' ");
				pg_query("UPDATE characters_items SET ".$_POST['item1']."=".$_POST['item1']."-1  WHERE username='".$_SESSION['login']['username']."'");

			}
		}
	}




	//Return
	$result=pg_fetch_row(pg_query("SELECT act_item2 FROM characters_main WHERE char_name='".$_SESSION['character']['char_name']."' "));
	if ($result[0]!=='NONE'){
		pg_query("UPDATE characters_items SET ".$result[0]."=".$result[0]."+1  WHERE username='".$_SESSION['login']['username']."'");
	}
	//Set NONE
	pg_query("UPDATE characters_main  SET act_item2='NONE' WHERE char_name='".$_SESSION['character']['char_name']."' ");
	//item2
	if ($_POST['item2'] !== 'NONE'){
		//SANITIZE INPUT
		if (preg_match("/^[a-zA-Z0-9_]{1,26}$/",  sanitize_input($_POST['item2']))) {
			$_POST['item2']=sanitize_input($_POST['item2']);
		//
			$result = pg_fetch_row(pg_query( "SELECT ".$_POST['item2']." FROM characters_items WHERE username='".$_SESSION['login']['username']."' "));
			if ($result[0]>0){
				if ($_POST['item2']===$_POST['item1'] || $_POST['item2']===$_POST['item3']){
					$warning_item='true';
					pg_query("UPDATE characters_main  SET act_item2='NONE' WHERE char_name='".$_SESSION['character']['char_name']."' ");
				}
				else{
					pg_query("UPDATE characters_main  SET act_item2='".$_POST['item2']."' WHERE char_name='".$_SESSION['character']['char_name']."' ");
					pg_query("UPDATE characters_items SET ".$_POST['item2']."=".$_POST['item2']."-1  WHERE username='".$_SESSION['login']['username']."'");

				}
			}
		}
	}




	//Return
	$result=pg_fetch_row(pg_query("SELECT act_item3 FROM characters_main WHERE char_name='".$_SESSION['character']['char_name']."' "));
	if ($result[0]!=='NONE'){
		pg_query("UPDATE characters_items SET ".$result[0]."=".$result[0]."+1  WHERE username='".$_SESSION['login']['username']."'");
	}
	//Set NONE
	pg_query("UPDATE characters_main  SET act_item3='NONE' WHERE char_name='".$_SESSION['character']['char_name']."' ");
	//item3
	if ($_POST['item3'] !== 'NONE'){
		//SANITIZE INPUT
		if (preg_match("/^[a-zA-Z0-9_]{1,26}$/",  sanitize_input($_POST['item3']))) {
			$_POST['item3']=sanitize_input($_POST['item3']);
		//
			$result = pg_fetch_row(pg_query( "SELECT ".$_POST['item3']." FROM characters_items WHERE username='".$_SESSION['login']['username']."' "));
			if ($result[0]>0){
				if ($_POST['item3']===$_POST['item1'] || $_POST['item3']===$_POST['item2']){
					$warning_item='true';
					pg_query("UPDATE characters_main  SET act_item3='NONE'  WHERE char_name='".$_SESSION['character']['char_name']."' ");
				}else{
					pg_query("UPDATE characters_main  SET act_item3='".$_POST['item3']."' WHERE char_name='".$_SESSION['character']['char_name']."' ");
					pg_query("UPDATE characters_items SET ".$_POST['item3']."=".$_POST['item3']."-1  WHERE username='".$_SESSION['login']['username']."'");

				}
			}

		}
	}



	//Ability
	//Return
	$result=pg_fetch_row(pg_query("SELECT act_skill1 FROM characters_main WHERE char_name='".$_SESSION['character']['char_name']."' "));
	if ($result[0]!=='NONE'){
		pg_query("UPDATE characters_abilities SET ".$result[0]."=".$result[0]."+1  WHERE username='".$_SESSION['login']['username']."'");
	}
	//Set NONE
	pg_query("UPDATE characters_main  SET act_skill1='NONE'  WHERE char_name='".$_SESSION['character']['char_name']."' ");

	//ability
	if ($_POST['ability'] !== 'NONE'){
		//SANITIZE INPUT
		if (preg_match("/^[a-zA-Z0-9_]{1,26}$/",  sanitize_input($_POST['ability']))) {
			$_POST['ability']=sanitize_input($_POST['ability']);
		//
			$result = pg_fetch_row(pg_query( "SELECT ".$_POST['ability']."' FROM characters_abilities WHERE char_name='".$_SESSION['character']['char_name']."' "));
			if ($result[0]>0){
				pg_query("UPDATE characters_main  SET act_skill1='".$_POST['ability']."' WHERE char_name='".$_SESSION['character']['char_name']."' ");
				pg_query("UPDATE characters_abilities SET ".$_POST['ability']."=".$_POST['ability']."-1  WHERE username='".$_SESSION['login']['username']."'");

			}
		}
	}


}


//load character information
$query="SELECT * FROM characters_main WHERE char_name='".$_SESSION['character']['char_name']."';";
$result=pg_query($query);
$data=pg_fetch_assoc($result);

//transfer to session
foreach ($data  as $key => $value){

	$_SESSION['character'][$key]=$value;

}






//weapon 1
if ($_SESSION['character']['act_wep1']==='NONE'){

	$_SESSION['character']['act_strength']=$_SESSION['character']['strength'];
	$_SESSION['character']['act_agility']=$_SESSION['character']['agility'];
	$_SESSION['character']['act_intelligence']=$_SESSION['character']['intelligence'];
	$_SESSION['character']['act_dodge']=$_SESSION['character']['dodge'];
	$_SESSION['character']['act_armor']=$_SESSION['character']['armor'];
	$_SESSION['character']['act_vitality']=$_SESSION['character']['vitality'];
	$_SESSION['character']['act_hit']=$_SESSION['character']['hit'];
	$_SESSION['character']['act_critical']=$_SESSION['character']['critical'];
	$_SESSION['character']['act_mastery']=$_SESSION['character']['mastery'];
	$_SESSION['character']['act_luck']=$_SESSION['character']['luck'];
	$_SESSION['character']['act_expgain']=$_SESSION['character']['expgain'];
	$_SESSION['character']['act_wep1_dmgmin']=1;
	$_SESSION['character']['act_wep1_dmgmax']=2;
	$_SESSION['character']['act_wep1_type']='NONE';
	$_SESSION['character']['act_wep2_type']='NONE';
	$_SESSION['character']['act_wep2_dmgmin']=1;
	$_SESSION['character']['act_wep2_dmgmax']=2;
}
else
{
$result=pg_query("SELECT min_dmg,max_dmg ,strength ,agility,intelligence,vitality,dodge,armor,hit,critical ,mastery,luck,expgain,type  FROM weapons WHERE id='".$_SESSION['character']['act_wep1']."' ");
$data=pg_fetch_assoc($result);

	$_SESSION['character']['act_strength']=$_SESSION['character']['strength']+$data['strength'];
	$_SESSION['character']['act_agility']=$_SESSION['character']['agility']+$data['agility'];
	$_SESSION['character']['act_intelligence']=$_SESSION['character']['intelligence']+$data['intelligence'];
	$_SESSION['character']['act_dodge']=$_SESSION['character']['dodge']+$data['dodge'];
	$_SESSION['character']['act_armor']=$_SESSION['character']['armor']+$data['armor'];
	$_SESSION['character']['act_vitality']=$_SESSION['character']['vitality']+$data['vitality'];
	$_SESSION['character']['act_hit']=$_SESSION['character']['hit']+$data['hit'];
	$_SESSION['character']['act_critical']=$_SESSION['character']['critical']+$data['critical'];
	$_SESSION['character']['act_mastery']=$_SESSION['character']['mastery']+$data['mastery'];
	$_SESSION['character']['act_luck']=$_SESSION['character']['luck']+$data['luck'];
	$_SESSION['character']['act_expgain']=$_SESSION['character']['expgain']+$data['expgain'];
	$_SESSION['character']['act_wep1_type']=$data['type'];
	$_SESSION['character']['act_wep1_dmgmin']=$data['min_dmg'];
	$_SESSION['character']['act_wep1_dmgmax']=$data['max_dmg'];
}

//weapon 2
if ($_SESSION['character']['act_wep2']==='NONE'){

}
else
{
$result=pg_query("SELECT min_dmg,max_dmg ,strength ,agility,intelligence,vitality,dodge,armor,hit,critical ,mastery,luck,expgain,type FROM weapons WHERE id='".$_SESSION['character']['act_wep2']."' ");
$data=pg_fetch_assoc($result);

	$_SESSION['character']['act_strength']=$_SESSION['character']['strength']+$data['strength'];
	$_SESSION['character']['act_agility']=$_SESSION['character']['agility']+$data['agility'];
	$_SESSION['character']['act_intelligence']=$_SESSION['character']['intelligence']+$data['intelligence'];
	$_SESSION['character']['act_dodge']=$_SESSION['character']['dodge']+$data['dodge'];
	$_SESSION['character']['act_armor']=$_SESSION['character']['armor']+$data['armor'];
	$_SESSION['character']['act_vitality']=$_SESSION['character']['vitality']+$data['vitality'];
	$_SESSION['character']['act_hit']=$_SESSION['character']['hit']+$data['hit'];
	$_SESSION['character']['act_critical']=$_SESSION['character']['critical']+$data['critical'];
	$_SESSION['character']['act_mastery']=$_SESSION['character']['mastery']+$data['mastery'];
	$_SESSION['character']['act_luck']=$_SESSION['character']['luck']+$data['luck'];
	$_SESSION['character']['act_expgain']=$_SESSION['character']['expgain']+$data['expgain'];
	$_SESSION['character']['act_wep2_type']=$data['type'];
	$_SESSION['character']['act_wep2_dmgmin']=$data['min_dmg'];
	$_SESSION['character']['act_wep2_dmgmax']=$data['max_dmg'];
}

//helmet
if ($_SESSION['character']['act_helmet']==='NONE'){

}
else
{
$result=pg_query("SELECT min_dmg,max_dmg ,strength ,agility,intelligence,vitality,dodge,armor,hit,critical ,mastery,luck,expgain FROM armor WHERE id='".$_SESSION['character']['act_helmet']."' ");
$data=pg_fetch_assoc($result);

	$_SESSION['character']['act_strength']=$_SESSION['character']['act_strength']+$data['strength'];
	$_SESSION['character']['act_agility']=$_SESSION['character']['act_agility']+$data['agility'];
	$_SESSION['character']['act_intelligence']=$_SESSION['character']['act_intelligence']+$data['intelligence'];
	$_SESSION['character']['act_dodge']=$_SESSION['character']['act_dodge']+$data['dodge'];
	$_SESSION['character']['act_armor']=$_SESSION['character']['act_armor']+$data['armor'];
	$_SESSION['character']['act_vitality']=$_SESSION['character']['act_vitality']+$data['vitality'];
	$_SESSION['character']['act_hit']=$_SESSION['character']['act_hit']+$data['hit'];
	$_SESSION['character']['act_critical']=$_SESSION['character']['act_critical']+$data['critical'];
	$_SESSION['character']['act_mastery']=$_SESSION['character']['act_mastery']+$data['mastery'];
	$_SESSION['character']['act_luck']=$_SESSION['character']['act_luck']+$data['luck'];
	$_SESSION['character']['act_expgain']=$_SESSION['character']['act_expgain']+$data['expgain'];

	$_SESSION['character']['act_wep1_dmgmin']=$_SESSION['character']['act_wep1_dmgmin']+$data['min_dmg'];
	$_SESSION['character']['act_wep1_dmgmax']=$_SESSION['character']['act_wep1_dmgmax']+$data['max_dmg'];
	$_SESSION['character']['act_wep2_dmgmin']=$_SESSION['character']['act_wep2_dmgmin']+$data['min_dmg'];
	$_SESSION['character']['act_wep2_dmgmax']=$_SESSION['character']['act_wep2_dmgmax']+$data['max_dmg'];
}

//chest
if ($_SESSION['character']['act_chest']==='NONE'){

}
else
{
$result=pg_query("SELECT min_dmg,max_dmg ,strength ,agility,intelligence,vitality,dodge,armor,hit,critical ,mastery,luck,expgain FROM armor WHERE id='".$_SESSION['character']['act_chest']."' ");
$data=pg_fetch_assoc($result);

	$_SESSION['character']['act_strength']=$_SESSION['character']['act_strength']+$data['strength'];
	$_SESSION['character']['act_agility']=$_SESSION['character']['act_agility']+$data['agility'];
	$_SESSION['character']['act_intelligence']=$_SESSION['character']['act_intelligence']+$data['intelligence'];
	$_SESSION['character']['act_dodge']=$_SESSION['character']['act_dodge']+$data['dodge'];
	$_SESSION['character']['act_armor']=$_SESSION['character']['act_armor']+$data['armor'];
	$_SESSION['character']['act_vitality']=$_SESSION['character']['act_vitality']+$data['vitality'];
	$_SESSION['character']['act_hit']=$_SESSION['character']['act_hit']+$data['hit'];
	$_SESSION['character']['act_critical']=$_SESSION['character']['act_critical']+$data['critical'];
	$_SESSION['character']['act_mastery']=$_SESSION['character']['act_mastery']+$data['mastery'];
	$_SESSION['character']['act_luck']=$_SESSION['character']['act_luck']+$data['luck'];
	$_SESSION['character']['act_expgain']=$_SESSION['character']['act_expgain']+$data['expgain'];

	$_SESSION['character']['act_wep1_dmgmin']=$_SESSION['character']['act_wep1_dmgmin']+$data['min_dmg'];
	$_SESSION['character']['act_wep1_dmgmax']=$_SESSION['character']['act_wep1_dmgmax']+$data['max_dmg'];
	$_SESSION['character']['act_wep2_dmgmin']=$_SESSION['character']['act_wep2_dmgmin']+$data['min_dmg'];
	$_SESSION['character']['act_wep2_dmgmax']=$_SESSION['character']['act_wep2_dmgmax']+$data['max_dmg'];
}

//gloves
if ($_SESSION['character']['act_gloves']==='NONE'){

}
else
{
$result=pg_query("SELECT min_dmg,max_dmg ,strength ,agility,intelligence,vitality,dodge,armor,hit,critical ,mastery,luck,expgain FROM armor WHERE id='".$_SESSION['character']['act_gloves']."' ");
$data=pg_fetch_assoc($result);

	$_SESSION['character']['act_strength']=$_SESSION['character']['act_strength']+$data['strength'];
	$_SESSION['character']['act_agility']=$_SESSION['character']['act_agility']+$data['agility'];
	$_SESSION['character']['act_intelligence']=$_SESSION['character']['act_intelligence']+$data['intelligence'];
	$_SESSION['character']['act_dodge']=$_SESSION['character']['act_dodge']+$data['dodge'];
	$_SESSION['character']['act_armor']=$_SESSION['character']['act_armor']+$data['armor'];
	$_SESSION['character']['act_vitality']=$_SESSION['character']['act_vitality']+$data['vitality'];
	$_SESSION['character']['act_hit']=$_SESSION['character']['act_hit']+$data['hit'];
	$_SESSION['character']['act_critical']=$_SESSION['character']['act_critical']+$data['critical'];
	$_SESSION['character']['act_mastery']=$_SESSION['character']['act_mastery']+$data['mastery'];
	$_SESSION['character']['act_luck']=$_SESSION['character']['act_luck']+$data['luck'];
	$_SESSION['character']['act_expgain']=$_SESSION['character']['act_expgain']+$data['expgain'];

	$_SESSION['character']['act_wep1_dmgmin']=$_SESSION['character']['act_wep1_dmgmin']+$data['min_dmg'];
	$_SESSION['character']['act_wep1_dmgmax']=$_SESSION['character']['act_wep1_dmgmax']+$data['max_dmg'];
	$_SESSION['character']['act_wep2_dmgmin']=$_SESSION['character']['act_wep2_dmgmin']+$data['min_dmg'];
	$_SESSION['character']['act_wep2_dmgmax']=$_SESSION['character']['act_wep2_dmgmax']+$data['max_dmg'];
}

//pants
if ($_SESSION['character']['act_pants']==='NONE'){

}
else
{
$result=pg_query("SELECT min_dmg,max_dmg ,strength ,agility,intelligence,vitality,dodge,armor,hit,critical ,mastery,luck,expgain FROM armor WHERE id='".$_SESSION['character']['act_pants']."' ");
$data=pg_fetch_assoc($result);

	$_SESSION['character']['act_strength']=$_SESSION['character']['act_strength']+$data['strength'];
	$_SESSION['character']['act_agility']=$_SESSION['character']['act_agility']+$data['agility'];
	$_SESSION['character']['act_intelligence']=$_SESSION['character']['act_intelligence']+$data['intelligence'];
	$_SESSION['character']['act_dodge']=$_SESSION['character']['act_dodge']+$data['dodge'];
	$_SESSION['character']['act_armor']=$_SESSION['character']['act_armor']+$data['armor'];
	$_SESSION['character']['act_vitality']=$_SESSION['character']['act_vitality']+$data['vitality'];
	$_SESSION['character']['act_hit']=$_SESSION['character']['act_hit']+$data['hit'];
	$_SESSION['character']['act_critical']=$_SESSION['character']['act_critical']+$data['critical'];
	$_SESSION['character']['act_mastery']=$_SESSION['character']['act_mastery']+$data['mastery'];
	$_SESSION['character']['act_luck']=$_SESSION['character']['act_luck']+$data['luck'];
	$_SESSION['character']['act_expgain']=$_SESSION['character']['act_expgain']+$data['expgain'];

	$_SESSION['character']['act_wep1_dmgmin']=$_SESSION['character']['act_wep1_dmgmin']+$data['min_dmg'];
	$_SESSION['character']['act_wep1_dmgmax']=$_SESSION['character']['act_wep1_dmgmax']+$data['max_dmg'];
	$_SESSION['character']['act_wep2_dmgmin']=$_SESSION['character']['act_wep2_dmgmin']+$data['min_dmg'];
	$_SESSION['character']['act_wep2_dmgmax']=$_SESSION['character']['act_wep2_dmgmax']+$data['max_dmg'];
}

//boots
if ($_SESSION['character']['act_boots']==='NONE'){

}
else
{
$result=pg_query("SELECT min_dmg,max_dmg ,strength ,agility,intelligence,vitality,dodge,armor,hit,critical ,mastery,luck,expgain FROM armor WHERE id='".$_SESSION['character']['act_boots']."' ");
$data=pg_fetch_assoc($result);

	$_SESSION['character']['act_strength']=$_SESSION['character']['act_strength']+$data['strength'];
	$_SESSION['character']['act_agility']=$_SESSION['character']['act_agility']+$data['agility'];
	$_SESSION['character']['act_intelligence']=$_SESSION['character']['act_intelligence']+$data['intelligence'];
	$_SESSION['character']['act_dodge']=$_SESSION['character']['act_dodge']+$data['dodge'];
	$_SESSION['character']['act_armor']=$_SESSION['character']['act_armor']+$data['armor'];
	$_SESSION['character']['act_vitality']=$_SESSION['character']['act_vitality']+$data['vitality'];
	$_SESSION['character']['act_hit']=$_SESSION['character']['act_hit']+$data['hit'];
	$_SESSION['character']['act_critical']=$_SESSION['character']['act_critical']+$data['critical'];
	$_SESSION['character']['act_mastery']=$_SESSION['character']['act_mastery']+$data['mastery'];
	$_SESSION['character']['act_luck']=$_SESSION['character']['act_luck']+$data['luck'];
	$_SESSION['character']['act_expgain']=$_SESSION['character']['act_expgain']+$data['expgain'];

	$_SESSION['character']['act_wep1_dmgmin']=$_SESSION['character']['act_wep1_dmgmin']+$data['min_dmg'];
	$_SESSION['character']['act_wep1_dmgmax']=$_SESSION['character']['act_wep1_dmgmax']+$data['max_dmg'];
	$_SESSION['character']['act_wep2_dmgmin']=$_SESSION['character']['act_wep2_dmgmin']+$data['min_dmg'];
	$_SESSION['character']['act_wep2_dmgmax']=$_SESSION['character']['act_wep2_dmgmax']+$data['max_dmg'];
}

//ring
if ($_SESSION['character']['act_ring']==='NONE'){

}
else
{
$result=pg_query("SELECT min_dmg,max_dmg ,strength ,agility,intelligence,vitality,dodge,armor,hit,critical ,mastery,luck,expgain FROM armor WHERE id='".$_SESSION['character']['act_ring']."' ");
$data=pg_fetch_assoc($result);

	$_SESSION['character']['act_strength']=$_SESSION['character']['act_strength']+$data['strength'];
	$_SESSION['character']['act_agility']=$_SESSION['character']['act_agility']+$data['agility'];
	$_SESSION['character']['act_intelligence']=$_SESSION['character']['act_intelligence']+$data['intelligence'];
	$_SESSION['character']['act_dodge']=$_SESSION['character']['act_dodge']+$data['dodge'];
	$_SESSION['character']['act_armor']=$_SESSION['character']['act_armor']+$data['armor'];
	$_SESSION['character']['act_vitality']=$_SESSION['character']['act_vitality']+$data['vitality'];
	$_SESSION['character']['act_hit']=$_SESSION['character']['act_hit']+$data['hit'];
	$_SESSION['character']['act_critical']=$_SESSION['character']['act_critical']+$data['critical'];
	$_SESSION['character']['act_mastery']=$_SESSION['character']['act_mastery']+$data['mastery'];
	$_SESSION['character']['act_luck']=$_SESSION['character']['act_luck']+$data['luck'];
	$_SESSION['character']['act_expgain']=$_SESSION['character']['act_expgain']+$data['expgain'];

	$_SESSION['character']['act_wep1_dmgmin']=$_SESSION['character']['act_wep1_dmgmin']+$data['min_dmg'];
	$_SESSION['character']['act_wep1_dmgmax']=$_SESSION['character']['act_wep1_dmgmax']+$data['max_dmg'];
	$_SESSION['character']['act_wep2_dmgmin']=$_SESSION['character']['act_wep2_dmgmin']+$data['min_dmg'];
	$_SESSION['character']['act_wep2_dmgmax']=$_SESSION['character']['act_wep2_dmgmax']+$data['max_dmg'];
}

//trinket
if ($_SESSION['character']['act_trinket']==='NONE'){

}
else
{
$result=pg_query("SELECT min_dmg,max_dmg ,strength ,agility,intelligence,vitality,dodge,armor,hit,critical ,mastery,luck,expgain FROM armor WHERE id='".$_SESSION['character']['act_trinket']."' ");
$data=pg_fetch_assoc($result);

	$_SESSION['character']['act_strength']=$_SESSION['character']['act_strength']+$data['strength'];
	$_SESSION['character']['act_agility']=$_SESSION['character']['act_agility']+$data['agility'];
	$_SESSION['character']['act_intelligence']=$_SESSION['character']['act_intelligence']+$data['intelligence'];
	$_SESSION['character']['act_dodge']=$_SESSION['character']['act_dodge']+$data['dodge'];
	$_SESSION['character']['act_armor']=$_SESSION['character']['act_armor']+$data['armor'];
	$_SESSION['character']['act_vitality']=$_SESSION['character']['act_vitality']+$data['vitality'];
	$_SESSION['character']['act_hit']=$_SESSION['character']['act_hit']+$data['hit'];
	$_SESSION['character']['act_critical']=$_SESSION['character']['act_critical']+$data['critical'];
	$_SESSION['character']['act_mastery']=$_SESSION['character']['act_mastery']+$data['mastery'];
	$_SESSION['character']['act_luck']=$_SESSION['character']['act_luck']+$data['luck'];
	$_SESSION['character']['act_expgain']=$_SESSION['character']['act_expgain']+$data['expgain'];

	$_SESSION['character']['act_wep1_dmgmin']=$_SESSION['character']['act_wep1_dmgmin']+$data['min_dmg'];
	$_SESSION['character']['act_wep1_dmgmax']=$_SESSION['character']['act_wep1_dmgmax']+$data['max_dmg'];
	$_SESSION['character']['act_wep2_dmgmin']=$_SESSION['character']['act_wep2_dmgmin']+$data['min_dmg'];
	$_SESSION['character']['act_wep2_dmgmax']=$_SESSION['character']['act_wep2_dmgmax']+$data['max_dmg'];
}

	$_SESSION['character']['act_hp']=$_SESSION['character']['act_vitality']*10;

	pg_query("UPDATE characters_main
	SET act_strength=".$_SESSION['character']['act_strength'].",
	act_agility=".$_SESSION['character']['act_agility'].",
	act_intelligence=".$_SESSION['character']['act_intelligence'].",
	act_dodge=".$_SESSION['character']['act_dodge'].",
	act_armor=".$_SESSION['character']['act_armor'].",
	act_vitality=".$_SESSION['character']['act_vitality'].",
	act_hit=".$_SESSION['character']['act_hit'].",
	act_critical=".$_SESSION['character']['act_critical'].",
	act_mastery=".$_SESSION['character']['act_mastery'].",
	act_luck=".$_SESSION['character']['act_luck'].",
	act_hp=".$_SESSION['character']['act_hp'].",
	act_expgain=".$_SESSION['character']['act_expgain'].",
	act_wep1_dmgmin=".$_SESSION['character']['act_wep1_dmgmin'].",
	act_wep2_dmgmin=".$_SESSION['character']['act_wep2_dmgmin'].",
	act_wep1_dmgmax=".$_SESSION['character']['act_wep1_dmgmax'].",
	act_wep2_dmgmax=".$_SESSION['character']['act_wep2_dmgmax'].",
	act_wep1_type='".$_SESSION['character']['act_wep1_type']."',
	act_wep2_type='".$_SESSION['character']['act_wep2_type']."'
	WHERE char_name='".$_SESSION['character']['char_name']."'");
?>


<!DOCTYPE html>

<html lang="en">



<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="A layout example with a side menu that hides on mobile, just like the Pure website.">

    <title>Responsive Side Menu &ndash; Layout Examples &ndash; Pure</title>

<style>

body {
    color: #777;
}

.pure-img-responsive {
    max-width: 100%;
    height: auto;
}

/*
Add transition to containers so they can push in and out.
*/
#layout,
#menu,
.menu-link {
    -webkit-transition: all 0.2s ease-out;
    -moz-transition: all 0.2s ease-out;
    -ms-transition: all 0.2s ease-out;
    -o-transition: all 0.2s ease-out;
    transition: all 0.2s ease-out;
}

/*
This is the parent `<div>` that contains the menu and the content area.
*/
#layout {
    position: relative;
    padding-left: 0;
}
    #layout.active #menu {
        left: 150px;
        width: 150px;
    }

    #layout.active .menu-link {
        left: 150px;
    }
/*
The content `<div>` is where all your content goes.
*/
.content {
    margin: 0 auto;
    padding: 0 2em;
    margin-bottom: 50px;
    line-height: 1.6em;
}

.header {
     margin: 0;
     color: #333;
     text-align: center;
     padding: 2.5em 2em 0;
     border-bottom: 1px solid #eee;
 }
    .header h1 {
        margin: 0.2em 0;
        font-size: 3em;
        font-weight: 300;
    }
     .header h2 {
        font-weight: 300;
        color: #ccc;
        padding: 0;
        margin-top: 0;
    }

.content-subhead {
    margin: 50px 0 20px 0;
    font-weight: 300;
    color: #888;
}



/*
The `#menu` `<div>` is the parent `<div>` that contains the `.pure-menu` that
appears on the left side of the page.
*/

#menu {
    margin-left: -150px; /* "#menu" width */
    width: 150px;
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    z-index: 1000; /* so the menu or its navicon stays above all content */
    background: #191818;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
}
    /*
    All anchors inside the menu should be styled like this.
    */
    #menu a {
        color: #999;
        border: none;
        padding: 0.6em 0 0.6em 0.6em;
    }

    /*
    Remove all background/borders, since we are applying them to #menu.
    */
     #menu .pure-menu,
     #menu .pure-menu ul {
        border: none;
        background: transparent;
    }

    /*
    Add that light border to separate items into groups.
    */
    #menu .pure-menu ul,
    #menu .pure-menu .menu-item-divided {
        border-top: 1px solid #333;
    }
        /*
        Change color of the anchor links on hover/focus.
        */
        #menu .pure-menu li a:hover,
        #menu .pure-menu li a:focus {
            background: #333;
        }

    /*
    This styles the selected menu item `<li>`.
    */
    #menu .pure-menu-selected,
    #menu .pure-menu-heading {
        background: #1f8dd6;
    }
        /*
        This styles a link within a selected menu item `<li>`.
        */
        #menu .pure-menu-selected a {
            color: #fff;
        }

    /*
    This styles the menu heading.
    */
    #menu .pure-menu-heading {
        font-size: 110%;
        color: #fff;
        margin: 0;
    }

/* -- Dynamic Button For Responsive Menu -------------------------------------*/

/*
The button to open/close the Menu is custom-made and not part of Pure. Here's
how it works:
*/

/*
`.menu-link` represents the responsive menu toggle that shows/hides on
small screens.
*/
.menu-link {
    position: fixed;
    display: block; /* show this only on small screens */
    top: 0;
    left: 0; /* "#menu width" */
    background: #000;
    background: rgba(0,0,0,0.7);
    font-size: 10px; /* change this value to increase/decrease button size */
    z-index: 10;
    width: 2em;
    height: auto;
    padding: 2.1em 1.6em;
}

    .menu-link:hover,
    .menu-link:focus {
        background: #000;
    }

    .menu-link span {
        position: relative;
        display: block;
    }

    .menu-link span,
    .menu-link span:before,
    .menu-link span:after {
        background-color: #fff;
        width: 100%;
        height: 0.2em;
    }

        .menu-link span:before,
        .menu-link span:after {
            position: absolute;
            margin-top: -0.6em;
            content: " ";
        }

        .menu-link span:after {
            margin-top: 0.6em;
        }


/* -- Responsive Styles (Media Queries) ------------------------------------- */

/*
Hides the menu at `48em`, but modify this based on your app's needs.
*/
@media (min-width: 48em) {

    .header,
    .content {
        padding-left: 2em;
        padding-right: 2em;
    }

    #layout {
        padding-left: 150px; /* left col width "#menu" */
        left: 0;
    }
    #menu {
        left: 150px;
    }

    .menu-link {
        position: fixed;
        left: 150px;
        display: none;
    }

    #layout.active .menu-link {
        left: 150px;
    }
}

@media (max-width: 48em) {
    /* Only apply this when the window is small. Otherwise, the following
    case results in extra padding on the left:
        * Make the window small.
        * Tap the menu to trigger the active state.
        * Make the window large again.
    */
    #layout.active {
        position: relative;
        left: 150px;
    }
}


.warning {
    color: red;
    font-weight: bold;
}



.pure-button {
	width: 216.88px;
}

	#menu .pure-menu-selected {
    background: #1f8dd6;
}

#menu .pure-menu ul, #menu .pure-menu .menu-item-divided {
    border-top: 1px solid #333;
	 border-bottom: 1px solid #333;
}


#menu li.pure-menu-selected a:hover, #menu li.pure-menu-selected a:focus {
    background: transparent none repeat scroll 0% 0%;
}



.warning {
    color: red;
    font-weight: bold;
}

.pure-img{
	display: block;
    margin-left: auto;
    margin-right: auto;
}



.charnick{
		font-size:200% ;
	    font-weight: bold;
}

.content {
    margin: 0;
    padding: 0;
}

.oneq{
width:30%;
 float: left;
 min-width:200px;
}
.oneqq{
width:40%;
min-width:200px;
 float: left;
}

.mid1{
		position:relative;

	width:60%;
 float: left;
}
.mid2{
	position:relative;
	width:40%;
 float: left;
}

.col2 {
	  text-align: right;
	  margin-right: 5px;

}
.pure-form {
	height: wrap_content;
text-align:center;
	}

 table.center {
    margin-left:auto;
    margin-right:auto;
  }


</style>
<style>
a.tooltip {
    outline: none;
    text-decoration: none;
    position: relative;
}

a.tooltip strong {
    line-height: 30px;
}

a.tooltip > span {
    min-width: 200px;
    padding: 10px 20px;
    margin-top: 25px;
    margin-left:  -150px;
    opacity: 0;
    visibility: hidden;
    z-index: ;
    position: absolute;
    font-family: Arial;
    font-size: 12px;
    font-style: normal;
    border-radius: 3px;
    box-shadow: 2px 2px 2px #999;
    -webkit-transition-property: opacity, margin-top, visibility, margin-left;
    -webkit-transition-duration: 0.4s, 0.3s, 0.4s, 0.3s;
    -webkit-transition-timing-function: ease-out,ease-out, ease-out, ease-out;
    transition-property: opacity, margin-top, visibility, margin-left;
    transition-duration: 0.4s, 0.3s, 0.4s, 0.3s;
    transition-timing-function:
        ease-out, ease-out, ease-out, ease-out;
}

/*a.tooltip > span:hover,*/

a.tooltip:hover > span {
    opacity: 1;
    text-decoration: none;
    visibility: visible;
    overflow: visible;
    margin-top: 25px;
    display: inline;
    margin-left: -150px;

}

a.tooltip span b {
    width: 12px;
    height: 15px;
    margin-left: 40px;
    margin-top: -19px;
    display: block;
    position: absolute;

}

a.tooltip > span {
	color: #FFFFFF;
	background: #333333;
	background: -webkit-linear-gradient(top, #333333, #999999);
	background: linear-gradient(top, #333333, #999999);
	border: 1px solid #000000;
}


.purple {
  color: rgb(163, 53, 238);
}
.green {
  color: rgb(0, 255, 0);
}
.orange {
  color: rgb(255, 128, 0);
}
.white{
	color: rgb(255, 255, 255);
}
.blue{
	color:rgb(0, 112, 221);
}

.brown{
	color:rgb(229, 204, 128);
}
.whitelarge{
 color: rgb(255, 255, 255);
font-size:200%;
}
.pristat{
	color:rgb(0, 112, 221);
}
.prinum{
 color:rgb(0, 112, 221);
}

.secstat{
	color:rgb(0, 112, 221);
}
.secnum{
 color:rgb(0, 112, 221);
}
.img{
	position:absolute;
	float: left;
	display: inline-block;
bottom:0px;
}

</style>

<link rel="stylesheet" type="text/css" href="resources/css/pure.css" />



 <!--[if lte IE 8]>
        <link rel="stylesheet" href="resources/css/side-menu-old-ie.css">
<![endif]-->


<!--[if gt IE 8]><!-->
        <link rel="stylesheet" href="resources/css/side-menu.css">
<!--<![endif]-->




<!--[if lt IE 9]>
    <script>
	/**
* @preserve HTML5 Shiv v3.7.0 | @afarkas @jdalton @jon_neal @rem | MIT/GPL2 Licensed
*/
;(function(window, document) {
/*jshint evil:true */
  /** version */
  var version = '3.7.0';

  /** Preset options */
  var options = window.html5 || {};

  /** Used to skip problem elements */
  var reSkip = /^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i;

  /** Not all elements can be cloned in IE **/
  var saveClones = /^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i;

  /** Detect whether the browser supports default html5 styles */
  var supportsHtml5Styles;

  /** Name of the expando, to work with multiple documents or to re-shiv one document */
  var expando = '_html5shiv';

  /** The id for the the documents expando */
  var expanID = 0;

  /** Cached data for each document */
  var expandoData = {};

  /** Detect whether the browser supports unknown elements */
  var supportsUnknownElements;

  (function() {
    try {
        var a = document.createElement('a');
        a.innerHTML = '<xyz></xyz>';
        //if the hidden property is implemented we can assume, that the browser supports basic HTML5 Styles
        supportsHtml5Styles = ('hidden' in a);

        supportsUnknownElements = a.childNodes.length == 1 || (function() {
          // assign a false positive if unable to shiv
          (document.createElement)('a');
          var frag = document.createDocumentFragment();
          return (
            typeof frag.cloneNode == 'undefined' ||
            typeof frag.createDocumentFragment == 'undefined' ||
            typeof frag.createElement == 'undefined'
          );
        }());
    } catch(e) {
      // assign a false positive if detection fails => unable to shiv
      supportsHtml5Styles = true;
      supportsUnknownElements = true;
    }

  }());

  /*--------------------------------------------------------------------------*/

  /**
   * Creates a style sheet with the given CSS text and adds it to the document.
   * @private
   * @param {Document} ownerDocument The document.
   * @param {String} cssText The CSS text.
   * @returns {StyleSheet} The style element.
   */
  function addStyleSheet(ownerDocument, cssText) {
    var p = ownerDocument.createElement('p'),
        parent = ownerDocument.getElementsByTagName('head')[0] || ownerDocument.documentElement;

    p.innerHTML = 'x<style>' + cssText + '</style>';
    return parent.insertBefore(p.lastChild, parent.firstChild);
  }

  /**
   * Returns the value of `html5.elements` as an array.
   * @private
   * @returns {Array} An array of shived element node names.
   */
  function getElements() {
    var elements = html5.elements;
    return typeof elements == 'string' ? elements.split(' ') : elements;
  }

    /**
   * Returns the data associated to the given document
   * @private
   * @param {Document} ownerDocument The document.
   * @returns {Object} An object of data.
   */
  function getExpandoData(ownerDocument) {
    var data = expandoData[ownerDocument[expando]];
    if (!data) {
        data = {};
        expanID++;
        ownerDocument[expando] = expanID;
        expandoData[expanID] = data;
    }
    return data;
  }

  /**
   * returns a shived element for the given nodeName and document
   * @memberOf html5
   * @param {String} nodeName name of the element
   * @param {Document} ownerDocument The context document.
   * @returns {Object} The shived element.
   */
  function createElement(nodeName, ownerDocument, data){
    if (!ownerDocument) {
        ownerDocument = document;
    }
    if(supportsUnknownElements){
        return ownerDocument.createElement(nodeName);
    }
    if (!data) {
        data = getExpandoData(ownerDocument);
    }
    var node;

    if (data.cache[nodeName]) {
        node = data.cache[nodeName].cloneNode();
    } else if (saveClones.test(nodeName)) {
        node = (data.cache[nodeName] = data.createElem(nodeName)).cloneNode();
    } else {
        node = data.createElem(nodeName);
    }

    // Avoid adding some elements to fragments in IE < 9 because
    // * Attributes like `name` or `type` cannot be set/changed once an element
    //   is inserted into a document/fragment
    // * Link elements with `src` attributes that are inaccessible, as with
    //   a 403 response, will cause the tab/window to crash
    // * Script elements appended to fragments will execute when their `src`
    //   or `text` property is set
    return node.canHaveChildren && !reSkip.test(nodeName) ? data.frag.appendChild(node) : node;
  }

  /**
   * returns a shived DocumentFragment for the given document
   * @memberOf html5
   * @param {Document} ownerDocument The context document.
   * @returns {Object} The shived DocumentFragment.
   */
  function createDocumentFragment(ownerDocument, data){
    if (!ownerDocument) {
        ownerDocument = document;
    }
    if(supportsUnknownElements){
        return ownerDocument.createDocumentFragment();
    }
    data = data || getExpandoData(ownerDocument);
    var clone = data.frag.cloneNode(),
        i = 0,
        elems = getElements(),
        l = elems.length;
    for(;i<l;i++){
        clone.createElement(elems[i]);
    }
    return clone;
  }

  /**
   * Shivs the `createElement` and `createDocumentFragment` methods of the document.
   * @private
   * @param {Document|DocumentFragment} ownerDocument The document.
   * @param {Object} data of the document.
   */
  function shivMethods(ownerDocument, data) {
    if (!data.cache) {
        data.cache = {};
        data.createElem = ownerDocument.createElement;
        data.createFrag = ownerDocument.createDocumentFragment;
        data.frag = data.createFrag();
    }


    ownerDocument.createElement = function(nodeName) {
      //abort shiv
      if (!html5.shivMethods) {
          return data.createElem(nodeName);
      }
      return createElement(nodeName, ownerDocument, data);
    };

    ownerDocument.createDocumentFragment = Function('h,f', 'return function(){' +
      'var n=f.cloneNode(),c=n.createElement;' +
      'h.shivMethods&&(' +
        // unroll the `createElement` calls
        getElements().join().replace(/[\w\-]+/g, function(nodeName) {
          data.createElem(nodeName);
          data.frag.createElement(nodeName);
          return 'c("' + nodeName + '")';
        }) +
      ');return n}'
    )(html5, data.frag);
  }

  /*--------------------------------------------------------------------------*/

  /**
   * Shivs the given document.
   * @memberOf html5
   * @param {Document} ownerDocument The document to shiv.
   * @returns {Document} The shived document.
   */
  function shivDocument(ownerDocument) {
    if (!ownerDocument) {
        ownerDocument = document;
    }
    var data = getExpandoData(ownerDocument);

    if (html5.shivCSS && !supportsHtml5Styles && !data.hasCSS) {
      data.hasCSS = !!addStyleSheet(ownerDocument,
        // corrects block display not defined in IE6/7/8/9
        'article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}' +
        // adds styling not present in IE6/7/8/9
        'mark{background:#FF0;color:#000}' +
        // hides non-rendered elements
        'template{display:none}'
      );
    }
    if (!supportsUnknownElements) {
      shivMethods(ownerDocument, data);
    }
    return ownerDocument;
  }

  /*--------------------------------------------------------------------------*/

  /**
   * The `html5` object is exposed so that more elements can be shived and
   * existing shiving can be detected on iframes.
   * @type Object
   * @example
   *
   * // options can be changed before the script is included
   * html5 = { 'elements': 'mark section', 'shivCSS': false, 'shivMethods': false };
   */
  var html5 = {

    /**
     * An array or space separated string of node names of the elements to shiv.
     * @memberOf html5
     * @type Array|String
     */
    'elements': options.elements || 'abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output progress section summary template time video',

    /**
     * current version of html5shiv
     */
    'version': version,

    /**
     * A flag to indicate that the HTML5 style sheet should be inserted.
     * @memberOf html5
     * @type Boolean
     */
    'shivCSS': (options.shivCSS !== false),

    /**
     * Is equal to true if a browser supports creating unknown/HTML5 elements
     * @memberOf html5
     * @type boolean
     */
    'supportsUnknownElements': supportsUnknownElements,

    /**
     * A flag to indicate that the document's `createElement` and `createDocumentFragment`
     * methods should be overwritten.
     * @memberOf html5
     * @type Boolean
     */
    'shivMethods': (options.shivMethods !== false),

    /**
     * A string to describe the type of `html5` object ("default" or "default print").
     * @memberOf html5
     * @type String
     */
    'type': 'default',

    // shivs the document according to the specified `html5` object options
    'shivDocument': shivDocument,

    //creates a shived element
    createElement: createElement,

    //creates a shived documentFragment
    createDocumentFragment: createDocumentFragment
  };

  /*--------------------------------------------------------------------------*/

  // expose html5
  window.html5 = html5;

  // shiv the document
  shivDocument(document);

}(this, document));
	</script>
<![endif]-->




</head>

<body>





<div id="layout">
    <!-- Menu toggle -->
    <a href="#menu" id="menuLink" class="menu-link">
        <!-- Hamburger icon -->
        <span></span>
    </a>

    <div id="menu">
        <div class="pure-menu">
            <a class="pure-menu-heading" href="#">Company</a>

            <ul class="pure-menu-list">

				<li class="pure-menu-item pure-menu-disabled menu-item-divided">Characters</li>
				<li class="pure-menu-item"><a href="fight.php" class="pure-menu-link">Fight</a></li>
				<li class="pure-menu-item "><a href="character.php" class ="pure-menu-link">Select</a></li>
                <li class="pure-menu-item pure-menu-selected"><a href="#" class ="pure-menu-link">Create</a></li>


				<li class="pure-menu-item pure-menu-disabled menu-item-divided">Account</li>
				<li class="pure-menu-item"><a href="inventory.php" class="pure-menu-link">Inventory</a></li>
                <li class="pure-menu-item"><a href="shop.php" class="pure-menu-link">Shop</a></li>
				<li class="pure-menu-item"><a href="settings.php" class="pure-menu-link">Settings</a></li>
				<?php if (isset($_SESSION['character']['char_name'])){ ?>
								<li class="pure-menu-item"><a href="history.php" class ="pure-menu-link">History</a></li>
				<?php }  ?>
				<li class="pure-menu-item"><a href="logout.php" class="pure-menu-link">Logout</a></li>

				<li class="pure-menu-item pure-menu-disabled menu-item-divided">News</li>
				<li class="pure-menu-item"><a href="#" class="pure-menu-link">Blog</a></li>
				<li class="pure-menu-item"><a href="#" class="pure-menu-link">Patch Notes</a></li>

				<li class="pure-menu-item pure-menu-disabled menu-item-divided">Other</li>
				<li class="pure-menu-item"><a href="replay.php" class="pure-menu-link">Replays</a></li>
				<li class="pure-menu-item"><a href="#" class="pure-menu-link">Contact Us</a></li>
				<li class="pure-menu-item"><a href="#" class="pure-menu-link">About</a></li>

            </ul>
        </div>
    </div>

    <div id="main">
        <div class="header">
            <h1>Login</h1>
            <h2>Enter the brawl!</h2>
			<form class="pure-form pure-form-aligned"   name ="select" method="post">
			<style>
.button_container{
	width:100%;
	display: block;
	 clear:both;
}
.button13{
		position:relative;

	width:33%;
 float: left;
 text-align:center;
}


</style>
<div class="button_container"></br>
</br>
</br>
<div class="button13">
     <button type="submit"  name="single" id="single" class="pure-button pure-button-primary">Single</button>

</div>
<div class="button13">
     <button type="submit"  name="pvp" id="pvp" class="pure-button pure-button-primary">PvP</button>

</div>
<div class="button13">
     <button type="submit"  name="tournament" id="tournament" class="pure-button pure-button-disabled">Enter Tournament</button>

</div>
</div>
</form></br>
        </div>

        <div class="content">


<form class="pure-form pure-form-aligned"  name ="char" method="post">


	<div class="oneq">

		<fieldset>
        <legend>Active Armor</legend>

			<table class="center">

					<tr>
						<td class="col1">
<?php    //Girefko aneshi active armor kai deixno eikona

		if (($_SESSION['character']['act_helmet'])!='NONE') {

			$query="SELECT id, description FROM armor WHERE id='".$_SESSION['character']['act_helmet']."'";
			$result = pg_query($query);
			$temp = pg_fetch_row($result);

			echo '<a href="#" class="tooltip"><img class="pure-img" src="/resources/images/armor/'.$temp[0].'.png" alt="'.$temp[0].'" style="width:64px;height:64px;">'.$temp[1].'</a>';

		}

		//diaforetika none
		?>
						</td>
						<td class="col2">
							<label>Helmet&nbsp;</label></td><td class="col3">
							<select id="helmet" name="helmet" class="pure-input">
								<option value="NONE">NONE</option>

	<?php	//apo kato ola ta armor
			$query="SELECT * FROM characters_armor WHERE username='".$_SESSION['login']['username']."'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);

			foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){

					$query="SELECT name FROM armor WHERE id='".$key."'AND type='Helmet'";
					$result = pg_query($query);
					$temp = pg_fetch_row($result);

						if 	($temp){
						echo '<option value="'.$key,'"';
							if (($_SESSION['character']['act_helmet'])===$key){
								echo ' selected="selected" ';
							}
						echo '">'.$temp[0].'</option>';
						}
				}
			}
?>

							</select>
						</td>
					</tr>

					<tr>
						<td class="col1">
<?php    //Girefko aneshi active armor kai deixno eikona

		if (($_SESSION['character']['act_chest'])!='NONE') {

			$query="SELECT id, description FROM armor WHERE id='".$_SESSION['character']['act_chest']."'";
			$result = pg_query($query);
			$temp = pg_fetch_row($result);

			echo '<a href="#" class="tooltip"><img class="pure-img" src="/resources/images/armor/'.$temp[0].'.png" alt="'.$temp[0].'" style="width:64px;height:64px;">'.$temp[1].'</a>';

		}

		//diaforetika none
		?>
						</td>
						<td class="col2">
							<label>Chest&nbsp;</label></td><td  class="col3">
							<select id="chest" name="chest" class="pure-input">
								<option value="NONE">NONE</option>

	<?php	//apo kato ola ta armor
			$query="SELECT * FROM characters_armor WHERE username='".$_SESSION['login']['username']."'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);

			foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){

					$query="SELECT name FROM armor WHERE id='".$key."' AND type='Chest'";
					$result = pg_query($query);
					$temp = pg_fetch_row($result);

						if 	($temp){
						echo '<option value="'.$key,'"';
							if (($_SESSION['character']['act_chest'])===$key){
								echo ' selected="selected" ';
							}
						echo '">'.$temp[0].'</option>';
						}
				}
			}
?>

							</select>
						</td>
					</tr>

					<tr>
						<td class="col1">
<?php    //Girefko aneshi active armor kai deixno eikona

		if (($_SESSION['character']['act_gloves'])!='NONE') {

			$query="SELECT id, description FROM armor WHERE id='".$_SESSION['character']['act_gloves']."'";
			$result = pg_query($query);
			$temp = pg_fetch_row($result);

			echo '<a href="#" class="tooltip"><img class="pure-img" src="/resources/images/armor/'.$temp[0].'.png" alt="'.$temp[0].'" style="width:64px;height:64px;">'.$temp[1].'</a>';

		}

		//diaforetika none
		?>
						</td>
						<td class="col2">
							<label>Gloves&nbsp;</label></td><td class="col3">
							<select id="gloves" name="gloves" class="pure-input">
								<option value="NONE">NONE</option>

	<?php	//apo kato ola ta armor
			$query="SELECT * FROM characters_armor WHERE username='".$_SESSION['login']['username']."'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);

			foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){

					$query="SELECT name FROM armor WHERE id='".$key."' AND type='Gloves'";
					$result = pg_query($query);
					$temp = pg_fetch_row($result);

						if 	($temp){
						echo '<option value="'.$key,'"';
							if (($_SESSION['character']['act_gloves'])===$key){
								echo ' selected="selected" ';
							}
						echo '">'.$temp[0].'</option>';
						}
				}
			}
?>

							</select>
						</td>
					</tr>

					<tr>
						<td class="col1">
<?php    //Girefko aneshi active armor kai deixno eikona

		if (($_SESSION['character']['act_pants'])!='NONE') {

			$query="SELECT id, description FROM armor WHERE id='".$_SESSION['character']['act_pants']."'";
			$result = pg_query($query);
			$temp = pg_fetch_row($result);

			echo '<a href="#" class="tooltip"><img class="pure-img" src="/resources/images/armor/'.$temp[0].'.png" alt="'.$temp[0].'" style="width:64px;height:64px;">'.$temp[1].'</a>';

		}

		//diaforetika none
		?>
						</td>
						<td class="col2">
							<label>Pants&nbsp;</label></td><td class="col3">
							<select id="pants" name="pants" class="pure-input">
								<option value="NONE">NONE</option>

	<?php	//apo kato ola ta armor
			$query="SELECT * FROM characters_armor WHERE username='".$_SESSION['login']['username']."'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);
			foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){

					$query="SELECT name FROM armor WHERE id='".$key."' AND type='Pants'";
					$result = pg_query($query);
					$temp = pg_fetch_row($result);

						if 	($temp){
						echo '<option value="'.$key,'"';
							if (($_SESSION['character']['act_pants'])===$key){
								echo ' selected="selected" ';
							}
						echo '">'.$temp[0].'</option>';
						}
				}
			}
?>

							</select>
						</td>
					</tr>

					<tr>
						<td class="col1">
<?php    //Girefko aneshi active armor kai deixno eikona

		if (($_SESSION['character']['act_boots'])!='NONE') {

			$query="SELECT id, description FROM armor WHERE id='".$_SESSION['character']['act_boots']."'";
			$result = pg_query($query);
			$temp = pg_fetch_row($result);

			echo '<a href="#" class="tooltip"><img class="pure-img" src="/resources/images/armor/'.$temp[0].'.png" alt="'.$temp[0].'" style="width:64px;height:64px;">'.$temp[1].'</a>';

		}

		//diaforetika none
		?>
						</td>
						<td class="col2">
							<label>Boots&nbsp;</label></td><td class="col3">
							<select id="boots" name="boots" class="pure-input">
								<option value="NONE">NONE</option>

	<?php	//apo kato ola ta armor
			$query="SELECT * FROM characters_armor WHERE username='".$_SESSION['login']['username']."'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);

			foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){

					$query="SELECT name FROM armor WHERE id='".$key."' AND type='Boots'";
					$result = pg_query($query);
					$temp = pg_fetch_row($result);

						if 	($temp){
						echo '<option value="'.$key,'"';
							if (($_SESSION['character']['act_boots'])===$key){
								echo ' selected="selected" ';
							}
						echo '">'.$temp[0].'</option>';
						}
				}
			}
?>

							</select>
						</td>
					</tr>

					<tr>
						<td class="col1">
<?php    //Girefko aneshi active armor kai deixno eikona

		if (($_SESSION['character']['act_ring'])!='NONE') {

			$query="SELECT id, description FROM armor WHERE id='".$_SESSION['character']['act_ring']."'";
			$result = pg_query($query);
			$temp = pg_fetch_row($result);

			echo '<a href="#" class="tooltip"><img class="pure-img" src="/resources/images/armor/'.$temp[0].'.png" alt="'.$temp[0].'" style="width:64px;height:64px;">'.$temp[1].'</a>';

		}

		//diaforetika none
		?>
						</td>
						<td class="col2">
							<label>Ring&nbsp;</label></td><td class="col3">
							<select id="ring" name="ring" class="pure-input">
								<option value="NONE">NONE</option>

	<?php	//apo kato ola ta armor
			$query="SELECT * FROM characters_armor WHERE username='".$_SESSION['login']['username']."'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);

			foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){

					$query="SELECT name FROM armor WHERE id='".$key."' AND type='Ring'";
					$result = pg_query($query);
					$temp = pg_fetch_row($result);

						if 	($temp){
						echo '<option value="'.$key,'"';
							if (($_SESSION['character']['act_ring'])===$key){
								echo ' selected="selected" ';
							}
						echo '">'.$temp[0].'</option>';
						}
				}
			}
?>

							</select>
						</td>
					</tr>

					<tr>
						<td class="col1">
<?php    //Girefko aneshi active armor kai deixno eikona

		if (($_SESSION['character']['act_trinket'])!='NONE') {

			$query="SELECT id, description FROM armor WHERE id='".$_SESSION['character']['act_trinket']."'";
			$result = pg_query($query);
			$temp = pg_fetch_row($result);

			echo '<a href="#" class="tooltip"><img class="pure-img" src="/resources/images/armor/'.$temp[0].'.png" alt="'.$temp[0].'" style="width:64px;height:64px;">'.$temp[1].'</a>';

		}

		//diaforetika none
		?>
						</td>
						<td class="col2">
							<label>Trinket&nbsp;</label></td><td class="col3">
							<select id="trinket" name="trinket" class="pure-input">
								<option value="NONE">NONE</option>

	<?php	//apo kato ola ta armor
			$query="SELECT * FROM characters_armor WHERE username='".$_SESSION['login']['username']."'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);

			foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){

					$query="SELECT name FROM armor WHERE id='".$key."' AND type='Trinket'";
					$result = pg_query($query);
					$temp = pg_fetch_row($result);

						if 	($temp){
						echo '<option value="'.$key,'"';
							if (($_SESSION['character']['act_trinket'])===$key){
								echo ' selected="selected" ';
							}
						echo '">'.$temp[0].'</option>';
						}
				}
			}
?>

							</select>
						</td>
					</tr>


			</table>
		<br/>
 </fieldset>
 <fieldset>
        <legend>Active Weapons</legend>

		<table class="center">
			<tr>
				<td class="col1">
<?php    //Girefko aneshi active armor kai deixno eikona

		if (($_SESSION['character']['act_wep1'])!='NONE') {

			$query="SELECT id, description FROM weapons WHERE id='".$_SESSION['character']['act_wep1']."'";
			$result = pg_query($query);
			$temp = pg_fetch_row($result);

			echo '<a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$temp[0].'.png" alt="'.$temp[0].'" style="width:64px;height:64px;">'.$temp[1].'</a>';

		}

		//diaforetika none
		?>
						</td>
						<td class="col2">
							<label>Main</label></td><td class="col3">
							<select id="weapon1" name="weapon1" class="pure-input">
								<option value="NONE">NONE</option>

	<?php	//apo kato ola ta armor
			$query="SELECT * FROM characters_weapons WHERE username='".$_SESSION['login']['username']."'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);?>
			<option disabled>Melee</option>
	<?php	foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){

					$query="SELECT name FROM weapons WHERE id='".$key."'AND type='Melee'";
					$result = pg_query($query);
					$temp = pg_fetch_row($result);

						if 	($temp){
						echo '<option value="'.$key,'"';
							if (($_SESSION['character']['act_wep1'])===$key){
								echo ' selected="selected" ';
							}
						echo '">'.$temp[0].'</option>';
						}
				}
			}
?>
			<option disabled>Ranged</option>
	<?php
			foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){

					$query="SELECT name FROM weapons WHERE id='".$key."'AND type='Ranged'";
					$result = pg_query($query);
					$temp = pg_fetch_row($result);

						if 	($temp){
						echo '<option value="'.$key,'"';
							if (($_SESSION['character']['act_wep1'])===$key){
								echo ' selected="selected" ';
							}
						echo '">'.$temp[0].'</option>';
						}
				}
			}

?>
			<option disabled>Spells</option>
	<?php
			foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){

					$query="SELECT name FROM weapons WHERE id='".$key."'AND type='Magic'";
					$result = pg_query($query);
					$temp = pg_fetch_row($result);

						if 	($temp){
						echo '<option value="'.$key,'"';
							if (($_SESSION['character']['act_wep1'])===$key){
								echo ' selected="selected" ';
							}
						echo '">'.$temp[0].'</option>';
						}
				}
			}






?>

							</select>
						</td>
					</tr>

			<tr>
				<td class="col1">
<?php    //Girefko aneshi active armor kai deixno eikona

		if (($_SESSION['character']['act_wep2'])!='NONE') {

			$query="SELECT id, description FROM weapons WHERE id='".$_SESSION['character']['act_wep2']."'";
			$result = pg_query($query);
			$temp = pg_fetch_row($result);

			echo '<a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$temp[0].'.png" alt="'.$temp[0].'" style="width:64px;height:64px;">'.$temp[1].'</a>';

		}

		//diaforetika none
		?>
						</td>
						<td class="col2">
							<label>Secondary</label></td><td class="col3">
							<select id="weapon2" name="weapon2" class="pure-input">
								<option value="NONE">NONE</option>

	<?php	//apo kato ola ta armor
			$query="SELECT * FROM characters_weapons WHERE username='".$_SESSION['login']['username']."'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);?>
			<option disabled>Melee</option>
	<?php	foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){

					$query="SELECT name FROM weapons WHERE id='".$key."'AND type='Melee'";
					$result = pg_query($query);
					$temp = pg_fetch_row($result);

						if 	($temp){
						echo '<option value="'.$key,'"';
							if (($_SESSION['character']['act_wep2'])===$key){
								echo ' selected="selected" ';
							}
						echo '">'.$temp[0].'</option>';
						}
				}
			}
?>
			<option disabled>Ranged</option>
	<?php
			foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){

					$query="SELECT name FROM weapons WHERE id='".$key."'AND type='Ranged'";
					$result = pg_query($query);
					$temp = pg_fetch_row($result);

						if 	($temp){
						echo '<option value="'.$key,'"';
							if (($_SESSION['character']['act_wep2'])===$key){
								echo ' selected="selected" ';
							}
						echo '">'.$temp[0].'</option>';
						}
				}
			}

?>
			<option disabled>Spells</option>
	<?php
			foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){

					$query="SELECT name FROM weapons WHERE id='".$key."'AND type='Magic'";
					$result = pg_query($query);
					$temp = pg_fetch_row($result);

						if 	($temp){
						echo '<option value="'.$key,'"';
							if (($_SESSION['character']['act_wep2'])===$key){
								echo ' selected="selected" ';
							}
						echo '">'.$temp[0].'</option>';
						}
				}
			}






?>

							</select>
						</td>
					</tr>
</table>

					<br/>



 </fieldset>




  <fieldset>
        <legend>Active Items</legend>

		<table class="center">
			<tr>
				<td class="col1">
<?php    //Girefko aneshi active armor kai deixno eikona

		if (($_SESSION['character']['act_item1'])!='NONE') {

			$result=item_finder($_SESSION['character']['act_item1'],$use,$datachange);
			echo '<a href="#" class="tooltip"><img class="pure-img" src="/resources/images/item/'.$_SESSION['character']['act_item1'].'.png" alt="'.$_SESSION['character']['act_item1'].'" style="width:64px;height:64px;"><span>'.$result['description'].'</span></a>';

		}

		//diaforetika none
		?>
						</td>
						<td class="col2">
							<label>Item 1</label></td><td class="col3">
							<select id="item1" name="item1" class="pure-input">
								<option value="NONE">NONE</option>

	<?php



			//check if he has any items
			$query="SELECT * FROM characters_items WHERE username='".$_SESSION['login']['username']."'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);


			foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){
					// print the GAMEE
					$result=item_finder($key,$use,$datachange);

						echo '<option value="'.$key,'"';
							if (($_SESSION['character']['act_item1'])===$key){
								echo ' selected="selected" ';
							}
						echo '">'.$result['name'].'</option>';




				}

			}


?>

							</select>
						</td>
					</tr>

		<tr>
				<td class="col1">
<?php    //Girefko aneshi active armor kai deixno eikona

		if (($_SESSION['character']['act_item2'])!='NONE') {

			$result=item_finder($_SESSION['character']['act_item2'],$use,$datachange);
			echo '<a href="#" class="tooltip"><img class="pure-img" src="/resources/images/item/'.$_SESSION['character']['act_item2'].'.png" alt="'.$_SESSION['character']['act_item2'].'" style="width:64px;height:64px;"><span>'.$result['description'].'</span></a>';

		}

		//diaforetika none
		?>
						</td>
						<td class="col2">
							<label>Item 2</label></td><td class="col3">
							<select id="item2" name="item2" class="pure-input">
								<option value="NONE">NONE</option>

	<?php



			//check if he has any items
			$query="SELECT * FROM characters_items WHERE username='".$_SESSION['login']['username']."'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);


			foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){
					// print the GAMEE
					$result=item_finder($key,$use,$datachange);

						echo '<option value="'.$key,'"';
							if (($_SESSION['character']['act_item2'])===$key){
								echo ' selected="selected" ';
							}
						echo '">'.$result['name'].'</option>';




				}

			}


?>

							</select>
						</td>
					</tr>


		<tr>
				<td class="col1">
<?php    //Girefko aneshi active armor kai deixno eikona

		if (($_SESSION['character']['act_item3'])!='NONE') {

			$result=item_finder($_SESSION['character']['act_item3'],$use,$datachange);
			echo '<a href="#" class="tooltip"><img class="pure-img" src="/resources/images/item/'.$_SESSION['character']['act_item3'].'.png" alt="'.$_SESSION['character']['act_item3'].'" style="width:64px;height:64px;"><span>'.$result['description'].'</span></a>';

		}

		//diaforetika none
		?>
						</td>
						<td class="col2">
							<label>Item 3</label></td><td class="col3">
							<select id="item3" name="item3" class="pure-input">
								<option value="NONE">NONE</option>

	<?php



			//check if he has any items
			$query="SELECT * FROM characters_items WHERE username='".$_SESSION['login']['username']."'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);


			foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){
					// print the GAMEE
					$result=item_finder($key,$use,$datachange);

						echo '<option value="'.$key,'"';
							if (($_SESSION['character']['act_item3'])===$key){
								echo ' selected="selected" ';
							}
						echo '">'.$result['name'].'</option>';




				}

			}


?>

							</select>
						</td>
					</tr>
					</table>					<br/>
					 </fieldset>

  <fieldset>
        <legend>Active Ability</legend>

		<table class="center">
			<tr>
				<td class="col1">
<?php    //Girefko aneshi active ability kai deixno eikona

		if (($_SESSION['character']['act_skill1'])!='NONE') {

			$result=ability_finder($_SESSION['character']['act_skill1'],$use,$datachange);
			echo '<a href="#" class="tooltip"><img class="pure-img" src="/resources/images/item/'.$_SESSION['character']['act_skill1'].'.png" alt="'.$_SESSION['character']['act_skill1'].'" style="width:64px;height:64px;"><span>'.$result['description'].'</span></a>';

		}

		//diaforetika none
		?>
						</td>
						<td class="col2">
							<label>Ability</label></td><td class="col3">
							<select id="ability" name="ability" class="pure-input">
								<option value="NONE">NONE</option>

	<?php



			//check if he has any items
			$query="SELECT * FROM characters_abilities WHERE username='".$_SESSION['login']['username']."'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);


			foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){
					// print the GAMEE
					$result=ability_finder($key,$use,$datachange);

						echo '<option value="'.$key,'"';
							if (($_SESSION['character']['act_skill1'])===$key){
								echo ' selected="selected" ';
							}
						echo '">'.$result['name'].'</option>';




				}

			}


?>

							</select>
						</td>
					</tr>

</table>

					<br/>



 </fieldset>





     <button type="submit"  name="setactive" id="setactive" class="pure-button pure-button-primary">Set Active Equipment</button>

 </div>

<div class="oneqq">
<fieldset>
    <legend>Legend</legend>


<div class="mid1">
<?php echo'<img class="pure-img" src="/resources/images/characters/character_'. $_SESSION['character']['char_pic'].'.png" alt="HTML5 Icon" style="width:128px;height:128px;">' ?>
</div>

<div class="mid2">
<p><?php echo  $_SESSION['character']['char_nick'];?></p>

<table class="center">

	<tr>
		<td class="col2">Level:</td>
		<td><?php echo  $_SESSION['character']['level'];?></td>
	</tr>
	<tr>
		<td class="col2">Experience:</td>
		<td><?php echo  $_SESSION['character']['experience'];?></td>
	</tr>
	<tr>
		<td class="col2">HP:</td>
		<td><?php echo  $_SESSION['character']['act_hp'];?> </td>
	</tr>
	<tr>
		<td class="col2">Gold:</td>
		<td><?php echo  $_SESSION['character']['gold'];?></td>
	</tr>
</table>

<table class="center">

	<tr>
		<td>Main Weapon Damage</td>
		<td><?php
		if ($_SESSION['character']['act_wep1_type']==='Melee' || $_SESSION['character']['act_wep1_type']==='NONE') {

			if ($_SESSION['character']['act_wep1_type']==='NONE') {

				$_SESSION['character']['act_wep1_dmgmax']=2;
				$_SESSION['character']['act_wep1_dmgmin']=1;
			}

			//Apply the stats

			$damage_max=$_SESSION['character']['act_wep1_dmgmax'] * $_SESSION['character']['act_strength'] * 0.01 + $_SESSION['character']['act_wep1_dmgmax'];
			$damage_min=$_SESSION['character']['act_wep1_dmgmin'] * $_SESSION['character']['act_strength'] * 0.01 + $_SESSION['character']['act_wep1_dmgmin'];

		}

		//Ranged

		if ($_SESSION['character']['act_wep1_type']==='Ranged') {

			//Apply the stats

			$damage_max=$_SESSION['character']['act_wep1_dmgmax'] * $_SESSION['character']['act_agility'] * 0.01 + $_SESSION['character']['act_wep1_dmgmax'];
			$damage_min=$_SESSION['character']['act_wep1_dmgmin'] * $_SESSION['character']['act_agility'] * 0.01 + $_SESSION['character']['act_wep1_dmgmin'];

		}

		//Magic

		if ($_SESSION['character']['act_wep1_type']==='Ranged') {

			//Apply the stats

			$damage_max=$_SESSION['character']['act_wep1_dmgmax'] * $_SESSION['character']['act_intelligence'] * 0.01 + $_SESSION['character']['act_wep1_dmgmax'];
			$damage_min=$_SESSION['character']['act_wep1_dmgmin'] * $_SESSION['character']['act_intelligence'] * 0.01 + $_SESSION['character']['act_wep1_dmgmin'];

		}

		echo $damage_min.'-'.$damage_max
		?></td>
	</tr>
	<tr>
		<td>Secondary Weapon Damage</td>
		<td><?php
		if ($_SESSION['character']['act_wep2_type']==='Melee' || $_SESSION['character']['act_wep2_type']==='NONE') {

			if ($_SESSION['character']['act_wep2_type']==='NONE') {

				$_SESSION['character']['act_wep2_dmgmax']=2;
				$_SESSION['character']['act_wep2_dmgmin']=1;
			}

			//Apply the stats

			$damage_max=$_SESSION['character']['act_wep2_dmgmax'] * $_SESSION['character']['act_strength'] * 0.01 + $_SESSION['character']['act_wep2_dmgmax'];
			$damage_min=$_SESSION['character']['act_wep2_dmgmin'] * $_SESSION['character']['act_strength'] * 0.01 + $_SESSION['character']['act_wep2_dmgmin'];

		}

		//Ranged

		if ($_SESSION['character']['act_wep2_type']==='Ranged') {

			//Apply the stats

			$damage_max=$_SESSION['character']['act_wep2_dmgmax'] * $_SESSION['character']['act_agility'] * 0.01 + $_SESSION['character']['act_wep2_dmgmax'];
			$damage_min=$_SESSION['character']['act_wep2_dmgmin'] * $_SESSION['character']['act_agility'] * 0.01 + $_SESSION['character']['act_wep2_dmgmin'];

		}

		//Magic

		if ($_SESSION['character']['act_wep2_type']==='Ranged') {

			//Apply the stats

			$damage_max=$_SESSION['character']['act_wep2_dmgmax'] * $_SESSION['character']['act_intelligence'] * 0.01 + $_SESSION['character']['act_wep2_dmgmax'];
			$damage_min=$_SESSION['character']['act_wep2_dmgmin'] * $_SESSION['character']['act_intelligence'] * 0.01 + $_SESSION['character']['act_wep2_dmgmin'];

		}

		echo $damage_min.'-'.$damage_max
		?></td>
	</tr>
	<tr>
	<td>Hit chance:</td>
	<td><?php echo 'TBD'?></td>
	</tr>
	<tr>
	<td>Critical chance:</td>
	<td><?php echo 'TBD'?></td>
	</tr>
</table>

</div>


</fieldset>
</div>


<div class="oneq">
	<fieldset>
	<legend>Attributes</legend>

			<table class="center">

					<tr>
						<td>Primary Stats</td>
						<td></td>
						<?php if ($_SESSION['character']['points']>0 ){?>
						<td></td>
						<?php } ?>
						<td></td>
					</tr>
					<tr>
						<td>Strength:<a href="#" class="tooltip"><img src="/resources/images/other/tooltip.png" alt="info" style="width:16px;height:16px;"><span>Increases melee damage by 1%</span></a></td>
						<td><?php echo $_SESSION['character']['strength']; ?></td>
						<?php if ($_SESSION['character']['points']>0 ){?>
						<td><button type="submit"  name="add_strength" id="add_strength" class="pure-button pure-button-primary">+</button></td>
						<?php } ?>
						<td><?php echo $_SESSION['character']['act_strength']; ?></td>
					</tr>
					<tr>
						<td>Agility:<a href="#" class="tooltip"><img src="/resources/images/other/tooltip.png" alt="info" style="width:16px;height:16px;"><span>Increases ranged damage by 1%</span></a></td>
						<td><?php echo $_SESSION['character']['agility']; ?></td>
						<?php if ($_SESSION['character']['points']>0 ){?>
						<td><button type="submit"  name="add_agility" id="add_agility" class="pure-button pure-button-primary">+</button></td>
						<?php } ?>
						<td><?php echo $_SESSION['character']['act_agility']; ?></td>
					</tr>
					<tr>
						<td>Intelligence:<a href="#" class="tooltip"><img src="/resources/images/other/tooltip.png" alt="info" style="width:16px;height:16px;"><span>Increases magic damage by 1%</span></a></td>
						<td><?php echo $_SESSION['character']['intelligence']; ?></td>
						<?php if ($_SESSION['character']['points']>0 ){?>
						<td><button type="submit"  name="add_intelligence" id="add_intelligence" class="pure-button pure-button-primary">+</button></td>
						<?php } ?>
						<td><?php echo $_SESSION['character']['act_intelligence']; ?></td>
					</tr>
					<tr>
						<td>Vitality:<a href="#" class="tooltip"><img src="/resources/images/other/tooltip.png" alt="info" style="width:16px;height:16px;"><span>Increases hit points by 10</span></a></td>
						<td><?php echo  $_SESSION['character']['vitality'];?></td>
						<?php if ($_SESSION['character']['points']>0 ){?>
						<td><button type="submit"  name="add_vitality" id="add_vitality" class="pure-button pure-button-primary">+</button></td>
						<?php } ?>
						<td><?php echo $_SESSION['character']['act_vitality']; ?></td>
					</tr>

					<tr>
						<td>Armor:<a href="#" class="tooltip"><img src="/resources/images/other/tooltip.png" alt="info" style="width:16px;height:16px;"><span>Reduces all damage taken by 0.5%</span></a></td>
						<td><?php echo  $_SESSION['character']['armor']; ?></td>
						<?php if ($_SESSION['character']['points']>0 ){?>
						<td><button type="submit"  name="add_armor" id="add_armor" class="pure-button pure-button-primary">+</button></td>
						<?php } ?>
						<td><?php echo $_SESSION['character']['act_armor']; ?></td>
					</tr>
				<tr>
						<td> </td>
						<td> </td>
						<?php if ($_SESSION['character']['points']>0 ){?>
						<td></td>
						<?php } ?>
						<td></td>
					</tr>
				<tr>
						<td>Secondary Stats</td>
						<td></td>
						<?php if ($_SESSION['character']['points']>0 ){?>
						<td></td>
						<?php } ?>
						<td></td>
					</tr>


					<tr>
						<td>Hit:<a href="#" class="tooltip"><img src="/resources/images/other/tooltip.png" alt="info" style="width:16px;height:16px;"><span>Increases hit chance by 0.1%</span></a></td>
						<td><?php echo  $_SESSION['character']['hit']; ?></td>
						<?php if ($_SESSION['character']['points']>0 ){?>
						<td><button type="submit"  name="add_hit" id="add_hit" class="pure-button pure-button-primary">+</button></td>
						<?php } ?>
						<td><?php echo $_SESSION['character']['act_hit']; ?></td>
					</tr>
					<tr>
						<td>Critical:<a href="#" class="tooltip"><img src="/resources/images/other/tooltip.png" alt="info" style="width:16px;height:16px;"><span>Increases critical hit chance by 0.1%</span></a></td>
						<td><?php echo  $_SESSION['character']['critical']; ?></td>
						<?php if ($_SESSION['character']['points']>0 ){?>
						<td><button type="submit"  name="add_critical" id="add_critical" class="pure-button pure-button-primary">+</button></td>
						<?php } ?>
						<td><?php echo $_SESSION['character']['act_critical']; ?></td>
					</tr>
					<tr>
						<td>Experience:<a href="#" class="tooltip"><img src="/resources/images/other/tooltip.png" alt="info" style="width:16px;height:16px;"><span>Increases experience gained by 1%</span></a></td>
						<td><?php echo  $_SESSION['character']['expgain']; ?></td>
						<?php if ($_SESSION['character']['points']>0 ){?>
						<td><button type="submit"  name="add_expgain" id="add_expgain" class="pure-button pure-button-primary">+</button></td>
						<?php } ?>
						<td><?php echo $_SESSION['character']['act_expgain']; ?></td>
					</tr>
					<tr>
						<td>Gold:<a href="#" class="tooltip"><img src="/resources/images/other/tooltip.png" alt="info" style="width:16px;height:16px;"><span>Increases gold gained by 2%</span></a></td>
						<td><?php echo  $_SESSION['character']['gold']; ?></td>
						<?php if ($_SESSION['character']['points']>0 ){?>
						<td><button type="submit"  name="add_luck" id="add_luck" class="pure-button pure-button-primary">+</button></td>
						<?php } ?>
						<td><?php echo $_SESSION['character']['act_luck']; ?></td>
					</tr>


			</table>
	</fieldset>
</div>



</form>




        </div>
    </div>
</div>





<script>
(function (window, document) {

    var layout   = document.getElementById('layout'),
        menu     = document.getElementById('menu'),
        menuLink = document.getElementById('menuLink');

    function toggleClass(element, className) {
        var classes = element.className.split(/\s+/),
            length = classes.length,
            i = 0;

        for(; i < length; i++) {
          if (classes[i] === className) {
            classes.splice(i, 1);
            break;
          }
        }
        // The className is not found
        if (length === classes.length) {
            classes.push(className);
        }

        element.className = classes.join(' ');
    }

    menuLink.onclick = function (e) {
        var active = 'active';

        e.preventDefault();
        toggleClass(layout, active);
        toggleClass(menu, active);
        toggleClass(menuLink, active);
    };

}(this, this.document));
</script>



</body>
</html>


<?php

$end = microtime(true);
$creationtime = ($end - $start);
printf("Page created in %.6f seconds.", $creationtime);
?>
