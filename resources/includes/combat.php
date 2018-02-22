<?php
function combat_func($player1,$player2){
//Get the values of player 1 and player 2
$_SESSION['fight']['status']='off';
//Initiliaze item used
$combat['player1_round']['itemused']=1;	
$combat['player2_round']['itemused']=1;	
$combat['attacker']['stop']='no';
$combat['output']['text']='';
//Player 1
$result=pg_query("SELECT * FROM characters_main WHERE char_name='".$player1."'");
$temp=pg_fetch_assoc($result);

foreach ($temp  as $key => $value){

	$combat['player1_temp'][$key]=$value;
	$combat['player1_round'][$key]=$value;
	
}

//Player 2
$result=pg_query("SELECT * FROM characters_main WHERE char_name='".$player2."'");
$temp=pg_fetch_assoc($result);

foreach ($temp  as $key => $value){

	$combat['player2_temp'][$key]=$value;
	$combat['player2_round'][$key]=$value;
	
}

//set_error_handler
$combat['player1_temp']['damagetaken']=0;
$combat['player2_temp']['damagetaken']=0;
$combat['player1_round']['damagetaken']=0;
$combat['player2_round']['damagetaken']=0;
//Who goes first coin toss (probably add stat speed or ability)

$chance = mt_rand(1,2);

if ($chance <2) {
	
	$attacker='player1';
	$defender='player2';
	$combat['output']['text']=$combat['output']['text'].'<p>A coin is tossed to decide who goes first.</p>';
	$combat['output']['text']=$combat['output']['text'].'<p>'.$combat['player1_round']['char_nick'].' attacks first!</p>';
}
else {
	
	$attacker='player2';
	$defender='player1';
	$combat['output']['text']=$combat['output']['text'].'<p>A coin is tossed to decide who goes first.</p>';
	$combat['output']['text']=$combat['output']['text'].'<p>'.$combat['player1_round']['char_nick'].' attacks first!</p>';
	
}



// Each fighting round is being calculated in this loop


while ( ($combat['player2_round']['act_hp']>0) && ($combat['player1_round']['act_hp']>0)) {
	echo $combat['player2_round']['act_hp'];
	echo $combat['player1_round']['act_hp'];
	//Chance to fuckup
	
	//$combat=clumsy($combat,$attacker,$defender)
	
	//Phase 1, use items and abilities
	$combat=item_ability($combat,$attacker,$defender,1);
	
	//Resume if success
	if ($combat['attacker']['stop']==='no'){
		
		//Check if he hits 
		$combat=miss($combat,$attacker,$defender);
	
		//Resume if success
		if ($combat[$attacker.'_temp']['hit']==='success'){
			
			//Calculate damage and defence
			$combat=damage($combat,$attacker,$defender);
	

		// End of Second resume
		}
		
	//First resume end and else reset
	}else{
		$combat['attacker']['stop']='no';
	}
	
	//Phase 2, use items and abilities
	$combat=item_ability($combat,$attacker,$defender,2);
	
	//Turn switch
	$tempchange=$attacker;
	$attacker=$defender;
	$defender=$tempchange;
	
	
	//Restore the round stats
	
	//Player1
	foreach ($combat['player1_round']  as $key => $value){
		
		$combat['player1_temp'][$key]=$value;


	}
	
	//Player2
	foreach ($combat['player2_round']  as $key => $value){
		
		$combat['player2_temp'][$key]=$value;

	}
	
}



if ($combat['player1_round']['act_hp']>0) { 

	//$_SESSION['character']['act_luck']=$_SESSION['character']['act_luck']+$data['luck'];	
	//$_SESSION['character']['act_expgain']=$_SESSION['character']['act_expgain']+$data['expgain'];	
//---------------------------
//Calculate if he gets item
//---------------------------
	$combat['output']['text']=$combat['output']['text'].'<p>Looting......................</p>';

	$chance = mt_rand(1,100);
	//Roll item chance 20%
	
	if ($chance<=100){
		
		$item_gained=1;
		
		$chance = mt_rand(1,100);	
		if ($chance<=30){
		
			$type='weapons';
			$tempsave='a weapon';
		
		
		}else{
		
			$type='armor';	
			$tempsave='an armor';
		
		
		}			

	}else{
		
		$item_gained=0;
		
	}

	if ($item_gained==1){
		
		$query="SELECT (SELECT count(*)  FROM ".$type." WHERE (reqlvl>=(".$combat['player2_round']['level']."-5)) AND (reqlvl<=(".$combat['player2_round']['level']." +2)) AND rarity='Common') as Common,
				(SELECT count(*)  FROM ".$type." WHERE (reqlvl>=(".$combat['player2_round']['level']."-5)) AND (reqlvl<=(".$combat['player2_round']['level']." +2)) AND rarity='Uncommon') as Uncommon,
				(SELECT count(*)  FROM ".$type." WHERE (reqlvl>=(".$combat['player2_round']['level']."-5)) AND (reqlvl<=(".$combat['player2_round']['level']." +2)) AND rarity='Rare') as Rare,
				(SELECT count(*)  FROM ".$type." WHERE (reqlvl>=(".$combat['player2_round']['level']."-5)) AND (reqlvl<=(".$combat['player2_round']['level']." +2)) AND rarity='Epic') as Epic,
				(SELECT count(*)  FROM ".$type." WHERE (reqlvl>=(".$combat['player2_round']['level']."-5)) AND (reqlvl<=(".$combat['player2_round']['level']." +2)) AND rarity='Legendary') as Legendary;";
			
		$result=pg_fetch_assoc(pg_query($query));
	
	}

//roll rarity
	if ($item_gained==1){
		
		$noitem=false;
		//Roll rarity 50 20 5 1 	
		$rarity = mt_rand(1,100);
		
		if ($rarity<=1){
			$rarity='Legendary';
		}
		elseif  ($rarity<=5 && $rarity>1) {
			$rarity='Epic';
		}
		elseif ($rarity>5 && $rarity<=20) {
			$rarity='Rare';
		}
		elseif ($rarity>20 && $rarity<=50) {
			$rarity='Uncommon';
		}
		else{
			$rarity='Common';
		}
	
		
	}

	if ($item_gained==1){	
		if ($rarity==='Legendary') {

			if ($result['legendary'] == 0  && $result['epic'] != 0) {
				$rarity='Epic';
			} elseif ($result['legendary'] == 0  && $result['rare'] != 0) {
				$rarity='Rare';
			} elseif ($result['legendary'] == 0  && $result['uncommon'] != 0) {
				$rarity='Uncommon';
			}elseif ($result['legendary'] == 0  && $result['common'] != 0) {
				$rarity='Common';
			}elseif ($result['legendary'] == 0){
				$noitem=true;
			}
		}elseif ($rarity==='Epic') {

			if ($result['epic'] == 0  && $result['rare'] != 0) {
				$rarity='Rare';
			} elseif ($result['epic'] == 0  && $result['uncommon'] != 0) {
				$rarity='Uncommon';
			}elseif ($result['epic'] == 0  && $result['common'] != 0) {
				$rarity='Common';
			}elseif ($result['epic'] == 0 ){
				$noitem=true;
			}
		}elseif ($rarity==='Rare') {

			if ($result['rare'] == 0  && $result['uncommon'] != 0) {
				$rarity='Uncommon';
			}elseif ($result['rare'] == 0  && $result['common'] != 0) {
				$rarity='Common';
			}elseif ($result['rare'] == 0 ){
				$noitem=true;
			}
		}elseif ($rarity==='Uncommon') {

			if ($result['uncommon'] == 0  && $result['common'] != 0) {
				$rarity='Common';
			}elseif($result['uncommon'] == 0 ){
				$noitem=true;
			}
		}elseif ($rarity==='Common') {

			if ($result['common'] == 0 ) {
				$noitem=true;
			}
		}
	}
	
	//Calculate if it is a weapon or armor
	


	if ($item_gained==1 && $noitem===false){

		//Roll item in database
		//	SELECT myid FROM mytable OFFSET (random()*(select count(*) from mytable)) LIMIT 1;
		$query="SELECT * FROM ".$type." WHERE (reqlvl>=(".$combat['player2_round']['level']."-5)) AND (reqlvl<=(".$combat['player2_round']['level']." +2)) AND rarity='".$rarity."' ORDER BY random() LIMIT 1; ";
		$result = pg_fetch_assoc(pg_query($query));
	
		//Show item
		$combat['output']['text']=$combat['output']['text'].'<p>You found '.$tempsave.'! <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/'.$type.'/'.$result['id'].'.png" alt="'.$result['name'].'" style="width:64px;height:64px;">'.$result['description'].'</a></p>';
		
		//Give item 
		$query="UPDATE characters_".$type." SET ".$result['id']."=".$result['id']."+1 WHERE username='".$_SESSION['login']['username']."';";
		$result=pg_query($query);

	
	}

//---------------------------
//Calculate if he gets 2nd item
//---------------------------	
	
	//Roll 2nd item if he gets first 33%
	if ($item_gained==1){
	
		$chance = mt_rand(1,100);
		
		if ($chance<=33){
			
			$item_gained=2;
			$noitem=false;
			//Roll type
			$chance = mt_rand(1,100);	
				
			if ($chance<=30){
		
				$type='weapons';
				$tempsave='a weapon';
		
			}else{
		
				$type='armor';	
				$tempsave='an armor';
		
			}
			
			//Roll rarity 60 35 5 1 	
			$rarity = mt_rand(1,100);
		
			if ($rarity<=1){
				$rarity='Legendary';
			}
			elseif  ($rarity<=5 && $rarity>1) {
				$rarity='Epic';
			}
			elseif ($rarity>5 && $rarity<=20) {
				$rarity='Rare';
			}
			elseif ($rarity>20 && $rarity<=50) {
				$rarity='Uncommon';
			}
			else{
				$rarity='Common';
			}
	
		}
	}
	
	if ($item_gained==2){
		
		$query="SELECT (SELECT count(*)  FROM ".$type." WHERE (reqlvl>=(".$combat['player2_round']['level']."-5)) AND (reqlvl<=(".$combat['player2_round']['level']." +2)) AND rarity='Common') as Common,
				(SELECT count(*)  FROM ".$type." WHERE (reqlvl>=(".$combat['player2_round']['level']."-5)) AND (reqlvl<=(".$combat['player2_round']['level']." +2)) AND rarity='Uncommon') as Uncommon,
				(SELECT count(*)  FROM ".$type." WHERE (reqlvl>=(".$combat['player2_round']['level']."-5)) AND (reqlvl<=(".$combat['player2_round']['level']." +2)) AND rarity='Rare') as Rare,
				(SELECT count(*)  FROM ".$type." WHERE (reqlvl>=(".$combat['player2_round']['level']."-5)) AND (reqlvl<=(".$combat['player2_round']['level']." +2)) AND rarity='Epic') as Epic,
				(SELECT count(*)  FROM ".$type." WHERE (reqlvl>=(".$combat['player2_round']['level']."-5)) AND (reqlvl<=(".$combat['player2_round']['level']." +2)) AND rarity='Legendary') as Legendary;";
			
		$result=pg_fetch_assoc(pg_query($query));
	
	}	
	
	if ($item_gained==2){
	
		if ($rarity==='Legendary') {

			if ($result['legendary'] == 0  && $result['epic'] != 0) {
				$rarity='Epic';
			} elseif ($result['legendary'] == 0  && $result['rare'] != 0) {
				$rarity='Rare';
			} elseif ($result['legendary'] == 0  && $result['uncommon'] != 0) {
				$rarity='Uncommon';
			}elseif ($result['legendary'] == 0  && $result['common'] != 0) {
				$rarity='Common';
			}elseif ($result['legendary'] == 0){
				$noitem=true;
			}
		}elseif ($rarity==='Epic') {

			if ($result['epic'] == 0  && $result['rare'] != 0) {
				$rarity='Rare';
			} elseif ($result['epic'] == 0  && $result['uncommon'] != 0) {
				$rarity='Uncommon';
			}elseif ($result['epic'] == 0  && $result['common'] != 0) {
				$rarity='Common';
			}elseif ($result['epic'] == 0 ){
				$noitem=true;
			}
		}elseif ($rarity==='Rare') {

			if ($result['rare'] == 0  && $result['uncommon'] != 0) {
				$rarity='Uncommon';
			}elseif ($result['rare'] == 0  && $result['common'] != 0) {
				$rarity='Common';
			}elseif ($result['rare'] == 0 ){
				$noitem=true;
			}
		}elseif ($rarity==='Uncommon') {

			if ($result['uncommon'] == 0  && $result['common'] != 0) {
				$rarity='Common';
			}elseif($result['uncommon'] == 0 ){
				$noitem=true;
			}
		}elseif ($rarity==='Common') {

			if ($result['common'] == 0 ) {
				$noitem=true;
			}
		}
		
	}	

	if ($item_gained==2 && $noitem===false){

		//Roll item in database
		//	SELECT myid FROM mytable OFFSET (random()*(select count(*) from mytable)) LIMIT 1;
		$query="SELECT * FROM ".$type." WHERE (reqlvl>=(".$combat['player2_round']['level']."-5)) AND (reqlvl<=(".$combat['player2_round']['level']." +2)) AND rarity='".$rarity."' ORDER BY random() LIMIT 1; ";
		$result = pg_fetch_assoc(pg_query($query));
	
		//Show item
		$combat['output']['text']=$combat['output']['text'].'<p>You found '.$tempsave.'! <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/'.$type.'/'.$result['id'].'.png" alt="'.$result['id'].'" style="width:64px;height:64px;">'.$result['description'].'</a></p>';
	    
		//Give item 
		$query="UPDATE characters_".$type." SET ".$result['id']."=".$result['id']."+1 WHERE username='".$_SESSION['login']['username']."';";
		$result=pg_query($query);

	
	}

//---------------------------
//Calculate if he gets ability
//---------------------------	
	
	//Roll ability 0.1%
	$chance = mt_rand(1,1000);	

	//Give ability
	if ($chance==1){
		$item_gained=3;
		$query="SELECT column_name
		FROM information_schema.columns
		WHERE table_name   = 'characters_abilities' AND  column_name<>'username' ORDER BY random() LIMIT 1;";
 
		$result=pg_fetch_row(pg_query($query));
		
		$result=ability_finder($result[0],0,$result);
	
		echo 'Gained ability '.$result['description'];
	
		$query="UPDATE characters_items 
		SET ".$result[0]."=".$result[0]."+1 
		WHERE username=".$_SESSION['login']['username']."";

		$result=pg_query($query);

	}
	
//---------------------------
//Calculate if he gets item
//---------------------------	
	
	//Roll item 33%
	$chance = mt_rand(1,1000);	
	
	
	//Give item based on level??
	if ($chance<=333){
		$item_gained=3;
		//Set the input for fetching item
		$combat['player1_round']['loot_item']='loot';	
		
		//Fetch the item
		$combat=item_finder('item_1',0,$combat);
		
		//Get the description
		$result='';
		$result=item_finder($combat['player1_round']['loot_item'],0,$result);
	
		//Update output
		$combat['output']['text']=$combat['output']['text'].'<p>You found an item! '.$result['description'].'</p>';
	
		//Award the item
		$query="UPDATE characters_items 
		SET ".$combat['player1_round']['loot_item']."=".$combat['player1_round']['loot_item']."+1 
		WHERE username='".$_SESSION['login']['username']."'";

		$result=pg_query($query);
		
	}


	if ($item_gained==0){
		$combat['output']['text']=$combat['output']['text'].'<p>No loot was found.</p>';
	}
//////////////////////
// Experience calculation
//////////////////////

	//If he has a negative level advantage
	if ($combat['player1_round']['level']>$combat['player2_round']['level']){
	 
		if ($combat['player1_round']['level']==($combat['player2_round']['level']+1)){
			$value2=0;
			$value1=0.1;
		}
		elseif($combat['player1_round']['level']==($combat['player2_round']['level']+2)){
			$value2=-0.10;
			$value1=0.15;
		}
		elseif($combat['player1_round']['level']==($combat['player2_round']['level']+3)){
			$value2=-0.15; 
			$value1=0.2;
		}
		elseif($combat['player1_round']['level']==($combat['player2_round']['level']+4)){
			$value2=-0.20; 
			$value1=0.25;
		}
		else{
			$value2=-1; 
			$value1=1;
		}
	}

	//If he has a positive level advantage
	if ($combat['player1_round']['level']<=$combat['player2_round']['level']){
	 
		if ($combat['player1_round']['level']==$combat['player2_round']['level']){
			$value1=0.1;
			$value2=0.1;
		}
		elseif($combat['player1_round']['level']==($combat['player2_round']['level']-1)){
			$value2=0.1;
			$value1=0;
		}
		elseif($combat['player1_round']['level']==($combat['player2_round']['level']-2)){
			$value2=0.15; 
			$value1=0;
		}
		elseif($combat['player1_round']['level']==($combat['player2_round']['level']-3)){
			$value2=0.2; 
			$value1=0;
		}
		elseif($combat['player1_round']['level']==($combat['player2_round']['level']-4)){
			$value2=0.25; 
			$value1=0;
		}
		else{
			$value2=0.25; 
			$value1=-0.1;
		}
	}

	//Calculate experience 
	$level_min=floor($combat['player1_round']['level']-$combat['player1_round']['level']*$value1);
	$level_max=floor($combat['player1_round']['level']+$combat['player1_round']['level']*$value2);

	$gain=mt_rand( $level_min, $level_max);
	
	if ($gain==0){
		$gain=1;
	}
	$combat['player1_round']['experience']=$combat['player1_round']['experience']+$gain;

	//Update database 
	$query="UPDATE characters_main  SET experience=".$combat['player1_round']['experience']." WHERE char_name='".$_SESSION['character']['char_name']."' ";
	$result=pg_query($query);

	//Display message
	
	
	if ($result){
	
		$combat['output']['text']=$combat['output']['text'].'<p>Experience gained: '.$gain.'</p>' ;

	}
	//Display message
	//Display messageddfasdasdasdasdasd LEVEL + POINTS
	//Display message

	$est_lvl=floor(pow($combat['player1_round']['experience'], (1/1.75)));
    
	if ($combat['player1_round']['level']<$est_lvl){
		
		//give him appropriate points
		$query="UPDATE characters_main
		SET points=points+".($est_lvl-$combat['player1_round']['level'])."
		WHERE char_name='".$_SESSION['character']['char_name']."'";
		$result=pg_query($query);
		
		$combat['output']['text']=$combat['output']['text'].'<p> Gained '.($est_lvl-$combat['player1_round']['level']).' level and points.';
		//increase the level appropriatly
		$query="UPDATE characters_main
		SET level=".$est_lvl."
		WHERE char_name='".$_SESSION['character']['char_name']."'";
		$result=pg_query($query);
	}
	
	
	$token=getToken(8);
	$another=pg_num_rows(pg_query("SELECT * FROM replaytable WHERE id='".$token."' "));
	while ($another>0){
			$token=getToken(8);
			$another=pg_num_rows(pg_query("SELECT * FROM replaytable WHERE id='".$token."' "));
	}
	
	$query="INSERT INTO replaytable(player1,player1img,player2,player2img,winner,id,log) VALUES ('".$combat['player1_round']['char_nick']."','".$combat['player1_round']['char_pic']."','".$combat['player2_round']['char_nick']."','".$combat['player2_round']['char_pic']."','".$combat['player1_round']['char_nick']."','".$token."','".$combat['output']['text']."')";
	pg_query($query);	
		
} else{
		
	// Experience calculation

	//If he has a negative level advantage
	if ($combat['player1_round']['level']<$combat['player2_round']['level']){
	 
		if ($combat['player1_round']['level']==($combat['player2_round']['level']-1)){
			$value2=0;
			$value1=0.1;
		}
		elseif($combat['player1_round']['level']==($combat['player2_round']['level']-2)){
			$value2=-0.10;
			$value1=0.15;
		}
		elseif($combat['player1_round']['level']==($combat['player2_round']['level']-3)){
			$value2=-0.15; 
			$value1=0.2;
		}
		elseif($combat['player1_round']['level']==($combat['player2_round']['level']-4)){
			$value2=-0.20; 
			$value1=0.25;
		}
		else{
			$value2=-1; 
			$value1=1;
		}
	}

	//If he has a positive level advantage
	if ($combat['player1_round']['level']>=$combat['player2_round']['level']){
	 
		if ($combat['player1_round']['level']==$combat['player2_round']['level']){
			$value1=0.1;
			$value2=0.1;
		}
		elseif($combat['player1_round']['level']==(1+$combat['player2_round']['level'])){
			$value2=0.1;
			$value1=0;
		}
		elseif($combat['player1_round']['level']==(2+$combat['player2_round']['level'])){
			$value2=0.15; 
			$value1=0;
		}
		elseif($combat['player1_round']['level']==(3+$combat['player2_round']['level'])){
			$value2=0.2; 
			$value1=0;
		}
		elseif($combat['player1_round']['level']==(4+$combat['player2_round']['level'])){
			$value2=0.25; 
			$value1=0;
		}
		else{
			$value2=0.25; 
			$value1=-0.1;
		}
	}
 
	$level_min=floor($combat['player1_round']['level']-$combat['player1_round']['level']*$value1);
	$level_max=floor($combat['player1_round']['level']+$combat['player1_round']['level']*$value2);
	$gain=mt_rand( $level_min, $level_max);
	$combat['player1_round']['experience']=$combat['player1_round']['experience']-$gain;
    if ($combat['player1_round']['experience']<0){
		$combat['player1_round']['experience']=0;
	}
	//Update database 
	$query="UPDATE characters_main  SET experience=".$combat['player1_round']['experience']." WHERE char_name='".$_SESSION['character']['char_name']."' ";
	$result=pg_query($query);

	//display message
	$combat['output']['text']=$combat['output']['text'].'<p>Lost '.$gain.' experience</p>';
	$token=getToken(8);
	$another=pg_num_rows(pg_query("SELECT * FROM replaytable WHERE id='".$token."' "));
	while ($another>0){
			$token=getToken(8);
			$another=pg_num_rows(pg_query("SELECT * FROM replaytable WHERE id='".$token."' "));
	}
	$query="INSERT INTO replaytable(player1,player1img,player2,player2img,winner,id,log) VALUES ('".$combat['player1_round']['char_nick']."','".$combat['player1_round']['char_pic']."','".$combat['player2_round']['char_nick']."','".$combat['player2_round']['char_pic']."','".$combat['player2_round']['char_nick']."','".$token."','".$combat['output']['text']."')";
	pg_query($query);
}
echo '<p><span id="caption"></span></p>';
echo "<script>type('".$combat['output']['text']."','caption');</script>"; 
}

function clumsy($combat,$attacker,$defender) {
	
	$chance = mt_rand(1,100);
	
	if ($chance <=2 ) {
		
			
		echo 'weapon swapped!';
		//remove weapon?

		$tempchange=$combat[$attacker]['round']['act_wep1'];
		$combat[$attacker]['round']['act_wep1']=$combat[$attacker]['round']['act_wep2'];	
		$combat[$attacker]['round']['act_wep2']=$tempchange;	

	}
	
	return $combat;
	
}


function item_ability($combat,$attacker,$defender,$phase) {
	

	//Attacker
	$combat['round']['phase']=$phase;
	
	//Set attacker and defender
	$combat['round']['active']=$attacker;
	$combat['round']['attacker']=$attacker;
	$combat['round']['defender']=$defender;
	
	$combat=ability_finder($combat[$attacker.'_temp']['act_skill1'],1,$combat);
	
	//Switch items
	if ($combat[$attacker.'_round']['itemused']<4) {
		
		$combat=item_finder($combat[$attacker.'_temp']['act_item'.$combat[$attacker.'_round']['itemused']],1,$combat);

		
	}
	$combat['round']['active']=$defender;
	
	//Defender
	
	$combat=ability_finder($combat[$defender.'_temp']['act_skill1'],1,$combat);

	//Switch items
	if ($combat[$defender.'_round']['itemused']<4) {
		
		$combat=item_finder($combat[$defender.'_temp']['act_item'.$combat[$defender.'_round']['itemused']],1,$combat);


	}
	
	return $combat;
	
	
}

function damage($combat,$attacker,$defender) {
    	
		//Melee
		if ($combat[$attacker.'_temp']['act_wep1_type']==='Melee' || $combat[$attacker.'_temp']['act_wep1_type']==='NONE') {
			
			if ($combat[$attacker.'_temp']['act_wep1_type']==='NONE') {
				
				$combat[$attacker.'_temp']['act_wep1_dmgmax']=2;
				$combat[$attacker.'_temp']['act_wep1_dmgmin']=1;
			}
			
			//Apply the stats
			
			$damage_max=$combat[$attacker.'_temp']['act_wep1_dmgmax'] * $combat[$attacker.'_temp']['act_strength'] * 0.01 + $combat[$attacker.'_temp']['act_wep1_dmgmax'];
			$damage_min=$combat[$attacker.'_temp']['act_wep1_dmgmin'] * $combat[$attacker.'_temp']['act_strength'] * 0.01 + $combat[$attacker.'_temp']['act_wep1_dmgmin'];

			
		}		
		
		//Ranged
		
		if ($combat[$attacker.'_temp']['act_wep1_type']==='Ranged') {
		
			//Apply the stats
			
			$damage_max=$combat[$attacker.'_temp']['act_wep1_dmgmax'] * $combat[$attacker.'_temp']['act_agility'] * 0.01 + $combat[$attacker.'_temp']['act_wep1_dmgmax'];
			$damage_min=$combat[$attacker.'_temp']['act_wep1_dmgmin'] * $combat[$attacker.'_temp']['act_agility'] * 0.01 + $combat[$attacker.'_temp']['act_wep1_dmgmin'];

		
		}	
			
		//Magic
		
		if ($combat[$attacker.'_temp']['act_wep1_type']==='Ranged') {

			//Apply the stats
			
			$damage_max=$combat[$attacker.'_temp']['act_wep1_dmgmax'] * $combat[$attacker.'_temp']['act_intelligence'] * 0.01 + $combat[$attacker.'_temp']['act_wep1_dmgmax'];
			$damage_min=$combat[$attacker.'_temp']['act_wep1_dmgmin'] * $combat[$attacker.'_temp']['act_intelligence'] * 0.01 + $combat[$attacker.'_temp']['act_wep1_dmgmin'];

		
		}				
		
			
		//Critical hit 
		$chance = mt_rand(0,1000);			
		
		if ($combat[$attacker.'_temp']['act_critical'] >= $chance ) {
			
			$damage_min=$damage_min*2;
			$damage_max=$damage_max*2;
			$tempsave='critical';
		}else{
			$tempsave='';
		}
			
		

		//Damage calculation
		
		$damage = mt_rand($damage_min,$damage_max);
		

		
		//Armor reduction
		
		$damage = $damage*(100/($combat[$defender.'_temp']['act_armor']+100));
		$damage = floor($damage);
			

		if ($damage>0){

			$combat[$defender.'_temp']['damagetaken']=$damage;

		}
		
		//Life reduction
			
		$combat[$defender.'_round']['act_hp']=$combat[$defender.'_round']['act_hp']-$damage;
		

		//Words to print find 
		
		
		//Melee
		if ($combat[$attacker.'_temp']['act_wep1_type']==='Melee' ) {		
			
			$query="SELECT description FROM weapons WHERE id='".$combat[$attacker.'_temp']['act_wep1']."'";
			$result = pg_fetch_row(pg_query($query));	
	
	
			$chance = mt_rand(1,3);
			
			if ($tempsave==='critical'){
				if ($chance==1){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
				if ($chance==2){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
				if ($chance==3){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
			}else{
				if ($chance==1){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
				if ($chance==2){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
				if ($chance==3){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}				
			}
		
		
	
	
		
		}
		
		//Punch		
		if ($combat[$attacker.'_temp']['act_wep1_type']==='NONE'){
			
			$query="SELECT description FROM weapons WHERE id='".$combat[$attacker.'_temp']['act_wep1']."'";
			$result = pg_fetch_row(pg_query($query));	
			
			$chance = mt_rand(1,3);
			
			if ($tempsave==='critical'){
				if ($chance==1){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
				if ($chance==2){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
				if ($chance==3){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
			}else{
				if ($chance==1){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
				if ($chance==2){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
				if ($chance==3){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}				
			}
			
		}		
		
		//Ranged				
		if ($combat[$attacker.'_temp']['act_wep1_type']==='Ranged'){
			
			$query="SELECT description FROM weapons WHERE id='".$combat[$attacker.'_temp']['act_wep1']."'";
			$result = pg_fetch_row(pg_query($query));	
			
			$chance = mt_rand(1,3);
			
			if ($tempsave==='critical'){
				if ($chance==1){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
				if ($chance==2){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
				if ($chance==3){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
			}else{
				if ($chance==1){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
				if ($chance==2){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
				if ($chance==3){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}				
			}
		}	
		
		//Magic			
		if ($combat[$attacker.'_temp']['act_wep1_type']==='Magic'){
			
			$query="SELECT description FROM weapons WHERE id='".$combat[$attacker.'_temp']['act_wep1']."'";
			$result = pg_fetch_row(pg_query($query));	
			
			$chance = mt_rand(1,3);
			
			if ($tempsave==='critical'){
				if ($chance==1){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
				if ($chance==2){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
				if ($chance==3){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
			}else{
				if ($chance==1){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
				if ($chance==2){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}
				if ($chance==3){
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_round']['char_nick'].
						' deals '.$damage.' damage to '.$combat[$defender.'_round']['char_nick'].
						' with his  <a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$combat[$attacker.'_temp']['act_wep1'].
						'.png" alt="'.$combat[$attacker.'_temp']['act_wep1'].'" style="width:64px;height:64px;">'.$result[0].'</a></p>';
				}				
			}
		}	
		
	return $combat;
	
	
}

function miss($combat,$attacker,$defender) {
	
	//Calculate the attackers hit chance 
	$hitchance = 0.1*$combat[$attacker.'_temp']['act_hit'];
	
	//Roll 1 to 100
	$chance = mt_rand(0,100);
	
	//Calculate the attackers hit chance 
	if (($hitchance+$chance)>=8 ){
		
		$chance = mt_rand(0,100);
		$dodgechance = 0.1*$combat[$defender.'_temp']['act_dodge'];
		
		//Calculate if the defender dodged
		if (($chance-$dodgechance)>=0 ){
			
			//Message for attack landing
			$combat[$attacker.'_temp']['hit']='success';
			//Melee
			//Ranged
			//Magic
		}
		else{
			
			//Message for attack dodging
			echo 'attack dodged';
			$combat[$attacker.'_temp']['hit']='failed';
			//Melee
			//Ranged
			//Magic
			
		}
		
	}
	
	else
		
	{
		//Message for attack missing
		
		$combat[$attacker.'_temp']['hit']='failed';
		
		$chance = mt_rand(1,3);
		
		if ($combat[$attacker.'_temp']['act_wep1_type']==='NONE') {
			if ($chance==1){
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_temp']['char_nick'].' tries to attack but slips and falls down.</p>';
			}
			if ($chance==2){
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_temp']['char_nick'].' swings his fists wildly but misses.</p>';
			}
			if ($chance==3){
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_temp']['char_nick'].' swings his fists wildly but misses.</p>';
			}
		}
		//Melee
		if ($combat[$attacker.'_temp']['act_wep1_type']==='Melee') {
			if ($chance==1){
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_temp']['char_nick'].' tries to attack but slips and falls down.</p>';
			}
			if ($chance==2){
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_temp']['char_nick'].' swings his weapon wildly but misses.</p>';
			}
			if ($chance==3){
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_temp']['char_nick'].' swings his weapon wildly but misses.</p>';
			}
		}
		
		//Ranged
		if ($combat[$attacker.'_temp']['act_wep1_type']==='Ranged') {
			if ($chance==1){		
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_temp']['char_nick'].' fires far into the distance.</p>';	
			}
			if ($chance==2){
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_temp']['char_nick'].' shoots but misses.</p>';			
			}
			if ($chance==3){
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_temp']['char_nick'].' shoots but misses.</p>';	
			}
		}	
		//Magic		
		if ($combat[$attacker.'_temp']['act_wep1_type']==='Magic') {		
			if ($chance==1){	
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_temp']['char_nick'].' attempts to cast a spell but forgot the spell.</p>';	
			}
			if ($chance==2){	
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_temp']['char_nick'].' shoots a spell but but misses.</p>';	
			}
			if ($chance==3){				
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$attacker.'_temp']['char_nick'].' attempts to cast a spell but forgot the spell.</p>';	
			}
		}				
	}
		
	return $combat;
	
	
}

