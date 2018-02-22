<?php


function get_desc($data) {
	
	echo $data['name'];
	echo $data['type'];
	echo $data['hand'];
	if (isset($data['min_dmg']) && ($data['min_dmg']!=0)) {
		echo $data['min_dmg'];
	}
	if (isset($data['max_dmg']) && ($data['max_dmg']!=0)) {	
	echo $data['max_dmg'];
	}

	if (isset($data['max_dmg']) && ($data['max_dmg']!=0)) {	
	echo $data['strength'];
	}
	if (isset($data['max_dmg']) && ($data['max_dmg']!=0)) {	
	echo $data['agility'];
	}
	if (isset($data['max_dmg']) && ($data['max_dmg']!=0)) {	
	echo $data['strength'];
	}
	if (isset($data['max_dmg']) && ($data['max_dmg']!=0)) {	
	echo $data['intelligence'];
	}
	if (isset($data['max_dmg']) && ($data['max_dmg']!=0)) {	
	echo $data['life'];
	}
	if (isset($data['max_dmg']) && ($data['max_dmg']!=0)) {	
	echo $data['dodge'];	
	}
	if (isset($data['max_dmg']) && ($data['max_dmg']!=0)) {	
	echo $data['armor'];
	}
	if (isset($data['max_dmg']) && ($data['max_dmg']!=0)) {	
	echo $data['hit'];	
	}
	if (isset($data['max_dmg']) && ($data['max_dmg']!=0)) {	
	echo $data['critical'];
	}
	if (isset($data['max_dmg']) && ($data['max_dmg']!=0)) {	
	echo $data['mastery'];	
	}
	if (isset($data['max_dmg']) && ($data['max_dmg']!=0)) {	
	echo $data['luck'];
	}
	if (isset($data['max_dmg']) && ($data['max_dmg']!=0)) {	
	echo $data['expgain'];	
	}
}

?>


<?php

$user_logged_in = is_logged();


if (isset($_POST['weapon_all_expand'])) {
	$_SESSION['shop']['weaponall'] =1;
	$_SESSION['shop']['melee'] =1;
	$_SESSION['shop']['ranged'] =1;
	$_SESSION['shop']['spells'] =1;
}

if (isset($_POST['weapon_all_contract'])) {
	$_SESSION['shop']['weaponall'] =0;
	$_SESSION['shop']['melee'] =0;
	$_SESSION['shop']['ranged'] =0;
	$_SESSION['shop']['spells'] =0;
}

#--------------------------------------

if (isset($_POST['weapon_melee_expand'])) {
	$_SESSION['shop']['melee'] =1;
}

if (isset($_POST['weapon_melee_contract'])) {
	$_SESSION['shop']['melee'] =0;
}
#--------------------------------------

if (isset($_POST['weapon_ranged_expand'])) {
	$_SESSION['shop']['ranged'] =1;
}

if (isset($_POST['weapon_ranged_contract'])) {
	$_SESSION['shop']['ranged'] =0;
}

#--------------------------------------
if (isset($_POST['weapon_spells_expand'])) {
	$_SESSION['shop']['spells'] =1;
}

if (isset($_POST['weapon_spells_contract'])) {
	$_SESSION['shop']['spells'] =0;
}

#--------------------------------------

if (isset($_POST['armor_all_contract'])) {
	$_SESSION['shop']['helmet'] =1;
	$_SESSION['shop']['chest'] =1;
	$_SESSION['shop']['gloves'] =1;
	$_SESSION['shop']['pants'] =1;
	$_SESSION['shop']['boots'] =1;
	$_SESSION['shop']['ring'] =1;
	$_SESSION['shop']['trinket'] =1;	
	$_SESSION['shop']['armorall'] =1;		
}

if (isset($_POST['weapon_spells_contract'])) {
	$_SESSION['shop']['helmet'] =0;
	$_SESSION['shop']['chest'] =0;
	$_SESSION['shop']['gloves'] =0;
	$_SESSION['shop']['pants'] =0;
	$_SESSION['shop']['boots'] =0;
	$_SESSION['shop']['ring'] =0;
	$_SESSION['shop']['trinket'] =0;
	$_SESSION['shop']['armorall'] =0;		
}
#--------------------------------------
if (isset($_POST['armor_helmet_expand'])) {
	$_SESSION['shop']['helmet'] =1;
}

if (isset($_POST['armor_helmet_contract'])) {
	$_SESSION['shop']['helmet'] =0;
}

#--------------------------------------
if (isset($_POST['armor_chest_expand'])) {
	$_SESSION['shop']['chest'] =1;
}

if (isset($_POST['armor_chest_contract'])) {
	$_SESSION['shop']['chest'] =0;
}

#--------------------------------------
if (isset($_POST['armor_gloves_expand'])) {
	$_SESSION['shop']['gloves'] =1;
}

if (isset($_POST['armor_gloves_contract'])) {
	$_SESSION['shop']['gloves'] =0;
}

#--------------------------------------
if (isset($_POST['armor_pants_expand'])) {
	$_SESSION['shop']['pants'] =1;
}

if (isset($_POST['armor_pants_contract'])) {
	$_SESSION['shop']['pants'] =0;
}

#--------------------------------------
if (isset($_POST['armor_boots_expand'])) {
	$_SESSION['shop']['boots'] =1;
}

if (isset($_POST['armor_boots_contract'])) {
	$_SESSION['shop']['boots'] =0;
}

#--------------------------------------
if (isset($_POST['armor_ring_expand'])) {
	$_SESSION['shop']['ring'] =1;
}

if (isset($_POST['armor_ring_contract'])) {
	$_SESSION['shop']['ring'] =0;
}

#--------------------------------------
if (isset($_POST['armor_ring_expand'])) {
	$_SESSION['shop']['ring'] =1;
}

if (isset($_POST['armor_ring_contract'])) {
	$_SESSION['shop']['ring'] =0;
}

#--------------------------------------
if (isset($_POST['armor_trinket_expand'])) {
	$_SESSION['shop']['trinket'] =1;
}

if (isset($_POST['armor_trinket_contract'])) {
	$_SESSION['shop']['trinket'] =0;
}





Buy function ... BTW NEED TO LOCK 
#------








#--------------------------------------
?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="resources/css/pure.css" />



<form class="pure-form pure-form-aligned" name ="shop" method="post" >
    <legend>Shop</legend>

	
	<table class="pure-table">



    <thead>
        <tr>
		 <th></th>
            <th colspan="2">Weapons</th>
			<th></th>
<?php		if ($_SESSION['shop']['weaponall'] == 0){
				echo '<th><button type="submit"  name="weapon_all_expand" id="weapon_all_expand" class="pure-button pure-button-primary"> + Expand</button></th>';
			}
			elseif (($_SESSION['shop']['melee'] == 1) && ($_SESSION['shop']['ranged'] == 1) && ($_SESSION['shop']['spells'] == 1)) {
				echo '<th><button type="submit"  name="weapon_all_contract" id="weapon_all_contract" class="pure-button pure-button-primary"> - Contract</button></th>';
			}
			else {
				echo '<th><button type="submit"  name="weapon_all_contract" id="weapon_all_contract" class="pure-button pure-button-primary"> - Contract</button></th>';
			}
?>
        </tr>
    </thead>



		<tr class="pure-table-odd">
            <th>Melee</th>


<?php  		 //->expand MELEE
			if ($_SESSION['shop']['melee'] == 1){ 
?>
			<th></th>
			<th></th>		
			<th><button type="submit"  name="weapon_melee_expand" id="weapon_melee_expand" class="pure-button pure-button-primary"> + Expand </button></th>
			</tr>
<?php 
			}

			else {
?>	
			<th></th>
			<th></th>
			<th><button type="submit"  name="weapon_melee_contract" id="weapon_melee_contract" class="pure-button pure-button-primary"> - Contract </button></th>
			</tr>
		<tr class="pure-table-odd">	
		<th>Description:</th>
		<th>Quantity in inventory:</th>
		<th>Selling Price for each:</th>
		<th>Buying Price for each:</th>
		<th>Buy</th>		
		<th>Sell</th>		
		</tr>		
			
<?php 
			}
		
		
		if ($_SESSION['shop']['melee'] == 1) {

			//check if he has any items
			$query="SELECT * FROM characters_weapons WHERE username='admin' AND type='Melee'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);
			$i=0;
			foreach ($data  as $key => $value){
					
				if (($key!=='username') && ($value !=0)){
					// print the GAMEE		
					$query="SELECT id, description, name, value FROM characters_weapons WHERE username='admin' AND type='Melee'";
					$result = pg_query($query);
					$data = pg_fetch_row($result);
					//user data
					$query="SELECT ".$key." FROM characters_weapons WHERE username='".$_SESSION['username']."'";
					$result = pg_query($query);		
					$user_data = pg_fetch_row($result);					
					$i=$i+1;
					
						echo '<tr><th><a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$data[0].'.png" alt="'.$data[0].'" style="width:128px;height:128px;">'.$data[1].'</a></th>';
						//how many he has
						echo '<th>'.$user_data[0].'</th>';
						//how many he wants to buy/sell
						echo '<th>'.$temp[3].'</th>';						
						echo '<th>'.floor($temp[3]/4).'</th>';				
						//Buy sell buttons						
						echo '<th><select>
						<option id="'.$data[0].'_1_sell" value="1">1</option>
						<option id="'.$data[0].'_2_sell" value="2">2</option>
						<option id="'.$data[0].'_3_sell" value="3">3</option>
						<option id="'.$data[0].'_4_sell" value="4">4</option>
						<option id="'.$data[0].'_5_sell" value="5">5</option>
						<option id="'.$data[0].'_10_sell" value="10">10</option>
						<option id="'.$data[0].'_25_sell" value="25">25/option>
						<option id="'.$data[0].'_50_sell" value="50">50</option>
						<option id="'.$data[0].'_100_sell" value="100">100</option>
						</select>
						&nbsp;&nbsp;<button type="submit"  id="'.$data[0].'_sell" name="'.$data[0].'_sell"  class="pure-button pure-button-primary">Sell</button></th>';
						//Buy sell buttons
						echo '<th><select>
						<option id="'.$data[0].'_1_buy" value="1">1</option>
						<option id="'.$data[0].'_2_buy" value="2">2</option>
						<option id="'.$data[0].'_3_buy" value="3">3</option>
						<option id="'.$data[0].'_4_buy" value="4">4</option>
						<option id="'.$data[0].'_5_buy" value="5">5</option>
						<option id="'.$data[0].'_10_buy" value="10">10</option>
						<option id="'.$data[0].'_25_buy" value="25">25/option>
						<option id="'.$data[0].'_50_buy" value="50">50</option>
						<option id="'.$data[0].'_100_buy" value="100">100</option>
						</select>
						&nbsp;&nbsp;<button type="submit"  id="'.$data[0].'_buy" name="'.$data[0].'_buy"  class="pure-button pure-button-primary">Buy</button></th></tr>';
					
				}
				
			}

		}



#reduct button on bottom and top
		if ($_SESSION['shop']['melee'] == 1)  {
?>		
		<tr class="pure-table-odd">
			<th><a href="#top"><button type="button"  name="backtotop" id="backtotop" class="pure-button pure-button-primary"> Back to Top </button></a></th>
			<th></th>
			<th></th>	
			<th><button type="submit"  name="weapon_melee_contract" id="weapon_melee_contract" class="pure-button pure-button-primary"> - Contract </button></th>
        </tr>
<?php
		} 
		
#--------------------------------------------------------------------
	
?>
			
		<tr class="pure-table-odd">
            <th>Ranged</th>


<?php  		 //->expand ranged
			if ($_SESSION['shop']['ranged'] == 1){ 
?>
			<th></th>
			<th></th>		
			<th><button type="submit"  name="weapon_ranged_expand" id="weapon_ranged_expand" class="pure-button pure-button-primary"> + Expand </button></th>
			</tr>
<?php 
			}

			else {
?>	
			<th></th>
			<th></th>
			<th><button type="submit"  name="weapon_ranged_contract" id="weapon_ranged_contract" class="pure-button pure-button-primary"> - Contract </button></th>
			</tr>
<?php 
			}
		
		
		if ($_SESSION['shop']['ranged'] == 1) {

			//check if he has any items
			$query="SELECT * FROM characters_weapons WHERE username='admin' AND type='Ranged'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);
			$i=0;
			foreach ($data  as $key => $value){
					
				if (($key!=='username') && ($value !=0)){
					// print the GAMEE		
					$query="SELECT id, description, name, value FROM characters_weapons WHERE username='admin' AND type='Ranged'";
					$result = pg_query($query);
					$data = pg_fetch_row($result);
					$i=$i+1;
					
						echo '<tr><th><a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$data[0].'.png" alt="'.$data[0].'" style="width:128px;height:128px;">'.$data[1].'</a></th>';
						echo '<th>'.$data[2].'</th>';
						echo '<th>'.$data[3].'</th>';
						echo '<th>'.$value.'</th></tr>';
					
				}
				
			}

		}



#reduct button on bottom and top
		if (($_SESSION['shop']['ranged'] == 1) && ($i!=0 )) {
?>		
		<tr class="pure-table-odd">
			<th><a href="#top"><button type="button"  name="backtotop" id="backtotop" class="pure-button pure-button-primary"> Back to Top </button></a></th>
			<th></th>
			<th></th>	
			<th><button type="submit"  name="weapon_ranged_contract" id="weapon_ranged_contract" class="pure-button pure-button-primary"> - Contract </button></th>
        </tr>
<?php
		} 
#--------------------------------------------------------------------		
?>			
			
	
			
		<tr class="pure-table-odd">
            <th>Magic</th>


<?php  		 //->expand spells
			if ($_SESSION['shop']['spells'] == 1){ 
?>
			<th></th>
			<th></th>		
			<th><button type="submit"  name="weapon_spells_expand" id="weapon_spells_expand" class="pure-button pure-button-primary"> + Expand </button></th>
			</tr>
<?php 
			}

			else {
?>	
			<th></th>
			<th></th>
			<th><button type="submit"  name="weapon_spells_contract" id="weapon_spells_contract" class="pure-button pure-button-primary"> - Contract </button></th>
			</tr>
<?php 
			}
		
		
		if ($_SESSION['shop']['spells'] == 1) {

			//check if he has any items
			$query="SELECT * FROM characters_weapons WHERE username='admin' AND type='Magic'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);
			$i=0;
			foreach ($data  as $key => $value){
					
				if (($key!=='username') && ($value !=0)){
					// print the GAMEE		
					$query="SELECT id, description, name, value FROM characters_weapons WHERE username='admin' AND type='Magic'";
					$result = pg_query($query);
					$data = pg_fetch_row($result);
					$i=$i+1;
					
						echo '<tr><th><a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$data[0].'.png" alt="'.$data[0].'" style="width:128px;height:128px;">'.$data[1].'</a></th>';
						echo '<th>'.$data[2].'</th>';
						echo '<th>'.$data[3].'</th>';
						echo '<th>'.$value.'</th></tr>';
					
				}
				
			}

		}



#reduct button on bottom and top
		if (($_SESSION['shop']['spells'] == 1) && ($i!=0 )) {
?>		
		<tr class="pure-table-odd">
			<th><a href="#top"><button type="button"  name="backtotop" id="backtotop" class="pure-button pure-button-primary"> Back to Top </button></a></th>
			<th></th>
			<th></th>	
			<th><button type="submit"  name="weapon_spells_contract" id="weapon_spells_contract" class="pure-button pure-button-primary"> - Contract </button></th>
        </tr>
<?php
		} 
		
#--------------------------------------------------------------------				
#--------------------------------------------------------------------	
#----------------------ARMOR----------------------------------	
#--------------------------------------------------------------------	
#--------------------------------------------------------------------			
?>			
					
			
<thead>
        <tr>
		 <th></th>
            <th colspan="2">Armor</th>
			<th></th>
<?php		if ($_SESSION['shop']['armorall'] == 0){
				echo '<th><button type="submit"  name="armor_all_expand" id="armor_all_expand" class="pure-button pure-button-primary"> + Expand</button></th>';
			}
			elseif (($_SESSION['shop']['helmet'] == 1) && ($_SESSION['shop']['chest'] == 1) && ($_SESSION['shop']['gloves'] == 1) && ($_SESSION['shop']['pants'] == 1) && ($_SESSION['shop']['boots'] == 1) && ($_SESSION['shop']['ring'] == 1) && ($_SESSION['shop']['trinket'] == 1)) {
				echo '<th><button type="submit"  name="armor_all_contract" id="armor_all_contract" class="pure-button pure-button-primary"> - Contract</button></th>';
			}
			else {
				echo '<th><button type="submit"  name="armor_all_contract" id="armor_all_contract" class="pure-button pure-button-primary"> - Contract</button></th>';
			}
?>
        </tr>
    </thead>



		<tr class="pure-table-odd">
            <th>Helmet</th>


<?php  		 //->expand helmet
			if ($_SESSION['shop']['helmet'] == 1){ 
?>
			<th></th>
			<th></th>		
			<th><button type="submit"  name="armor_helmet_expand" id="armor_helmet_expand" class="pure-button pure-button-primary"> + Expand </button></th>
			</tr>
<?php 
			}

			else {
?>	
			<th></th>
			<th></th>
			<th><button type="submit"  name="armor_helmet_contract" id="armor_helmet_contract" class="pure-button pure-button-primary"> - Contract </button></th>
			</tr>
<?php 
			}
		
		
		if ($_SESSION['shop']['helmet'] == 1) {

			//check if he has any items
			$query="SELECT * FROM characters_armor WHERE username='admin' AND type='Helmet'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);
			$i=0;
			foreach ($data  as $key => $value){
					
				if (($key!=='username') && ($value !=0)){
					// print the GAMEE		
					$query="SELECT id, description, name, value FROM characters_armor WHERE username='admin' AND type='Helmet'";
					$result = pg_query($query);
					$data = pg_fetch_row($result);
					$i=$i+1;
					
						echo '<tr><th><a href="#" class="tooltip"><img class="pure-img" src="/resources/images/armor/'.$data[0].'.png" alt="'.$data[0].'" style="width:128px;height:128px;">'.$data[1].'</a></th>';
						echo '<th>'.$data[2].'</th>';
						echo '<th>'.$data[3].'</th>';
						echo '<th>'.$value.'</th></tr>';
					
				}
				
			}

		}



#reduct button on bottom and top
		if (($_SESSION['shop']['helmet'] == 1) && ($i!=0 )) {
?>		
		<tr class="pure-table-odd">
			<th><a href="#top"><button type="button"  name="backtotop" id="backtotop" class="pure-button pure-button-primary"> Back to Top </button></a></th>
			<th></th>
			<th></th>	
			<th><button type="submit"  name="armor_helmet_contract" id="armor_helmet_contract" class="pure-button pure-button-primary"> - Contract </button></th>
        </tr>
<?php
		} 

		
#--------------------------------------------------------------------			
			
?>

		<tr class="pure-table-odd">
            <th>Chest armor</th>


<?php  		 //->expand chest
			if ($_SESSION['shop']['chest'] == 1){ 
?>
			<th></th>
			<th></th>		
			<th><button type="submit"  name="armor_chest_expand" id="armor_chest_expand" class="pure-button pure-button-primary"> + Expand </button></th>
			</tr>
<?php 
			}

			else {
?>	
			<th></th>
			<th></th>
			<th><button type="submit"  name="armor_chest_contract" id="armor_chest_contract" class="pure-button pure-button-primary"> - Contract </button></th>
			</tr>
<?php 
			}
		
		
		if ($_SESSION['shop']['chest'] == 1) {

			//check if he has any items
			$query="SELECT * FROM characters_armor WHERE username='admin' AND type='Chest'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);
			$i=0;
			foreach ($data  as $key => $value){
					
				if (($key!=='username') && ($value !=0)){
					// print the GAMEE		
					$query="SELECT id, description, name, value FROM characters_armor WHERE username='admin' AND type='Chest'";
					$result = pg_query($query);
					$data = pg_fetch_row($result);
					$i=$i+1;
					
						echo '<tr><th><a href="#" class="tooltip"><img class="pure-img" src="/resources/images/armor/'.$data[0].'.png" alt="'.$data[0].'" style="width:128px;height:128px;">'.$data[1].'</a></th>';
						echo '<th>'.$data[2].'</th>';
						echo '<th>'.$data[3].'</th>';
						echo '<th>'.$value.'</th></tr>';
					
				}
				
			}

		}



#reduct button on bottom and top
		if (($_SESSION['shop']['chest'] == 1) && ($i!=0 )) {
?>		
		<tr class="pure-table-odd">
			<th><a href="#top"><button type="button"  name="backtotop" id="backtotop" class="pure-button pure-button-primary"> Back to Top </button></a></th>
			<th></th>
			<th></th>	
			<th><button type="submit"  name="armor_chest_contract" id="armor_chest_contract" class="pure-button pure-button-primary"> - Contract </button></th>
        </tr>
<?php
		} 

		
#--------------------------------------------------------------------			
			
			
?>

		<tr class="pure-table-odd">
            <th>Gloves</th>


<?php  		 //->expand gloves
			if ($_SESSION['shop']['gloves'] == 1){ 
?>
			<th></th>
			<th></th>		
			<th><button type="submit"  name="armor_gloves_expand" id="armor_gloves_expand" class="pure-button pure-button-primary"> + Expand </button></th>
			</tr>
<?php 
			}

			else {
?>	
			<th></th>
			<th></th>
			<th><button type="submit"  name="armor_gloves_contract" id="armor_gloves_contract" class="pure-button pure-button-primary"> - Contract </button></th>
			</tr>
<?php 
			}
		
		
		if ($_SESSION['shop']['gloves'] == 1) {

			//check if he has any items
			$query="SELECT * FROM characters_armor WHERE username='admin' AND type='Gloves'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);
			$i=0;
			foreach ($data  as $key => $value){
					
				if (($key!=='username') && ($value !=0)){
					// print the GAMEE		
					$query="SELECT id, description, name, value FROM characters_armor WHERE username='admin' AND type='Gloves'";
					$result = pg_query($query);
					$data = pg_fetch_row($result);
					$i=$i+1;
					
						echo '<tr><th><a href="#" class="tooltip"><img class="pure-img" src="/resources/images/armor/'.$data[0].'.png" alt="'.$data[0].'" style="width:128px;height:128px;">'.$data[1].'</a></th>';
						echo '<th>'.$data[2].'</th>';
						echo '<th>'.$data[3].'</th>';
						echo '<th>'.$value.'</th></tr>';
					
				}
				
			}

		}



#reduct button on bottom and top
		if (($_SESSION['shop']['gloves'] == 1) && ($i!=0 )) {
?>		
		<tr class="pure-table-odd">
			<th><a href="#top"><button type="button"  name="backtotop" id="backtotop" class="pure-button pure-button-primary"> Back to Top </button></a></th>
			<th></th>
			<th></th>	
			<th><button type="submit"  name="armor_gloves_contract" id="armor_gloves_contract" class="pure-button pure-button-primary"> - Contract </button></th>
        </tr>
<?php
		} 
		
#--------------------------------------------------------------------									
				
			
?>

		<tr class="pure-table-odd">
            <th>Pants</th>


<?php  		 //->expand pants
			if ($_SESSION['shop']['pants'] == 1){ 
?>
			<th></th>
			<th></th>		
			<th><button type="submit"  name="armor_pants_expand" id="armor_pants_expand" class="pure-button pure-button-primary"> + Expand </button></th>
			</tr>
<?php 
			}

			else {
?>	
			<th></th>
			<th></th>
			<th><button type="submit"  name="armor_pants_contract" id="armor_pants_contract" class="pure-button pure-button-primary"> - Contract </button></th>
			</tr>
<?php 
			}
		
		
		if ($_SESSION['shop']['pants'] == 1) {

			//check if he has any items
			$query="SELECT * FROM characters_armor WHERE username='admin' AND type='Pants'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);
			$i=0;
			foreach ($data  as $key => $value){
					
				if (($key!=='username') && ($value !=0)){
					// print the GAMEE		
					$query="SELECT id, description, name, value FROM characters_armor WHERE username='admin' AND type='Pants'";
					$result = pg_query($query);
					$data = pg_fetch_row($result);
					$i=$i+1;
					
						echo '<tr><th><a href="#" class="tooltip"><img class="pure-img" src="/resources/images/armor/'.$data[0].'.png" alt="'.$data[0].'" style="width:128px;height:128px;">'.$data[1].'</a></th>';
						echo '<th>'.$data[2].'</th>';
						echo '<th>'.$data[3].'</th>';
						echo '<th>'.$value.'</th></tr>';
					
				}
				
			}

		}



#reduct button on bottom and top
		if (($_SESSION['shop']['pants'] == 1) && ($i!=0 )) {
?>		
		<tr class="pure-table-odd">
			<th><a href="#top"><button type="button"  name="backtotop" id="backtotop" class="pure-button pure-button-primary"> Back to Top </button></a></th>
			<th></th>
			<th></th>	
			<th><button type="submit"  name="armor_pants_contract" id="armor_pants_contract" class="pure-button pure-button-primary"> - Contract </button></th>
        </tr>
<?php
		} 
		
#--------------------------------------------------------------------			
				
			
?>

		<tr class="pure-table-odd">
            <th>Boots</th>


<?php  		 //->expand pants
			if ($_SESSION['shop']['boots'] == 1){ 
?>
			<th></th>
			<th></th>		
			<th><button type="submit"  name="armor_boots_expand" id="armor_boots_expand" class="pure-button pure-button-primary"> + Expand </button></th>
			</tr>
<?php 
			}

			else {
?>	
			<th></th>
			<th></th>
			<th><button type="submit"  name="armor_boots_contract" id="armor_boots_contract" class="pure-button pure-button-primary"> - Contract </button></th>
			</tr>
<?php 
			}
		
		
		if ($_SESSION['shop']['boots'] == 1) {

			//check if he has any items
			$query="SELECT * FROM characters_armor WHERE username='admin' AND type='Boots'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);
			$i=0;
			foreach ($data  as $key => $value){
					
				if (($key!=='username') && ($value !=0)){
					// print the GAMEE		
					$query="SELECT id, description, name, value FROM characters_armor WHERE username='admin' AND type='Boots'";
					$result = pg_query($query);
					$data = pg_fetch_row($result);
					$i=$i+1;
					
						echo '<tr><th><a href="#" class="tooltip"><img class="pure-img" src="/resources/images/armor/'.$data[0].'.png" alt="'.$data[0].'" style="width:128px;height:128px;">'.$data[1].'</a></th>';
						echo '<th>'.$data[2].'</th>';
						echo '<th>'.$data[3].'</th>';
						echo '<th>'.$value.'</th></tr>';
					
				}
				
			}

		}



#reduct button on bottom and top
		if (($_SESSION['shop']['boots'] == 1) && ($i!=0 )) {
?>		
		<tr class="pure-table-odd">
			<th><a href="#top"><button type="button"  name="backtotop" id="backtotop" class="pure-button pure-button-primary"> Back to Top </button></a></th>
			<th></th>
			<th></th>	
			<th><button type="submit"  name="armor_boots_contract" id="armor_boots_contract" class="pure-button pure-button-primary"> - Contract </button></th>
        </tr>
<?php
		} 

		
#--------------------------------------------------------------------						
				
?>

		<tr class="pure-table-odd">
            <th>Rings</th>


<?php  		 //->expand ring
			if ($_SESSION['shop']['ring'] == 1){ 
?>
			<th></th>
			<th></th>		
			<th><button type="submit"  name="armor_ring_expand" id="armor_ring_expand" class="pure-button pure-button-primary"> + Expand </button></th>
			</tr>
<?php 
			}

			else {
?>	
			<th></th>
			<th></th>
			<th><button type="submit"  name="armor_ring_contract" id="armor_ring_contract" class="pure-button pure-button-primary"> - Contract </button></th>
			</tr>
<?php 
			}
		
		
		if ($_SESSION['shop']['ring'] == 1) {

			//check if he has any items
			$query="SELECT * FROM characters_armor WHERE username='admin' AND type='Ring'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);
			$i=0;
			foreach ($data  as $key => $value){
					
				if (($key!=='username') && ($value !=0)){
					// print the GAMEE		
					$query="SELECT id, description, name, value FROM characters_armor WHERE username='admin' AND type='Ring'";
					$result = pg_query($query);
					$data = pg_fetch_row($result);
					$i=$i+1;
					
						echo '<tr><th><a href="#" class="tooltip"><img class="pure-img" src="/resources/images/armor/'.$data[0].'.png" alt="'.$data[0].'" style="width:128px;height:128px;">'.$data[1].'</a></th>';
						echo '<th>'.$data[2].'</th>';
						echo '<th>'.$data[3].'</th>';
						echo '<th>'.$value.'</th></tr>';
					
				}
				
			}

		}



#reduct button on bottom and top
		if (($_SESSION['shop']['ring'] == 1) && ($i!=0 )) {
?>		
		<tr class="pure-table-odd">
			<th><a href="#top"><button type="button"  name="backtotop" id="backtotop" class="pure-button pure-button-primary"> Back to Top </button></a></th>
			<th></th>
			<th></th>	
			<th><button type="submit"  name="armor_ring_contract" id="armor_ring_contract" class="pure-button pure-button-primary"> - Contract </button></th>
        </tr>
<?php
		} 

		
#--------------------------------------------------------------------				
				
?>

		<tr class="pure-table-odd">
            <th>Trinkets</th>


<?php  		 //->expand trinket
			if ($_SESSION['shop']['trinket'] == 1){ 
?>
			<th></th>
			<th></th>		
			<th><button type="submit"  name="armor_trinket_expand" id="armor_trinket_expand" class="pure-button pure-button-primary"> + Expand </button></th>
			</tr>
<?php 
			}

			else {
?>	
			<th></th>
			<th></th>
			<th><button type="submit"  name="armor_trinket_contract" id="armor_trinket_contract" class="pure-button pure-button-primary"> - Contract </button></th>
			</tr>
<?php 
			}
		
		
		if ($_SESSION['shop']['trinket'] == 1) {

			//check if he has any items
			$query="SELECT * FROM characters_armor WHERE username='admin' AND type='Trinket'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);
			$i=0;
			foreach ($data  as $key => $value){
					
				if (($key!=='username') && ($value !=0)){
					// print the GAMEE		
					$query="SELECT id, description, name, value FROM characters_armor WHERE username='admin' AND type='Trinket'";
					$result = pg_query($query);
					$data = pg_fetch_row($result);
					$i=$i+1;
					
						echo '<tr><th><a href="#" class="tooltip"><img class="pure-img" src="/resources/images/armor/'.$data[0].'.png" alt="'.$data[0].'" style="width:128px;height:128px;">'.$data[1].'</a></th>';
						echo '<th>'.$data[2].'</th>';
						echo '<th>'.$data[3].'</th>';
						echo '<th>'.$value.'</th></tr>';
					
				}
				
			}

		}



#reduct button on bottom and top
		if (($_SESSION['shop']['trinket'] == 1) && ($i!=0 )) {
?>		
		<tr class="pure-table-odd">
			<th><a href="#top"><button type="button"  name="backtotop" id="backtotop" class="pure-button pure-button-primary"> Back to Top </button></a></th>
			<th></th>
			<th></th>	
			<th><button type="submit"  name="armor_trinket_contract" id="armor_trinket_contract" class="pure-button pure-button-primary"> - Contract </button></th>
        </tr>
<?php
		} 

		
#--------------------------------------------------------------------				
#--------------------------------------------------------------------	
#----------------------Items----------------------------------	
#--------------------------------------------------------------------	
#--------------------------------------------------------------------							
?>			
<thead>
        <tr>
		 <th></th>
            <th colspan="2">Items</th>
			<th></th>
<?php		if ($_SESSION['shop']['items'] == 0){
				echo '<th><button type="submit"  name="items_all_expand" id="items_all_expand" class="pure-button pure-button-primary"> + Expand</button></th>';
			}
			else {
				echo '<th><button type="submit"  name="items_all_contract" id="items_all_contract" class="pure-button pure-button-primary"> - Contract</button></th>';
			}
?>
        </tr>
    </thead>



<?php  				
		
		if ($_SESSION['shop']['items'] == 1) {

			//check if he has any items
			$query="SELECT * FROM characters_items WHERE username='admin'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);
			$i=0;
			
			foreach ($data  as $key => $value){
					
				if (($key!=='username') && ($value !=0)){
					// print the GAMEE		
					$result=item_finder($item_name,$use,$datachange);
					
					$i=$i+1;
					
						echo '<tr><th><a href="#" class="tooltip"><img class="pure-img" src="/resources/images/items/'.$data[0].'.png" alt="'.$data[0].'" style="width:128px;height:128px;">'.$result[0].'</a></th>';
						echo '<th>'.$result[1].'</th>';
						echo '<th>'.$result[2].'</th>';
						echo '<th>'.$value.'</th></tr>';
					
				}
				
			}

		}



#reduct button on bottom and top
		if (($_SESSION['shop']['items'] == 1) && ($i!=0 )) {
?>		
		<tr class="pure-table-odd">
			<th><a href="#top"><button type="button"  name="backtotop" id="backtotop" class="pure-button pure-button-primary"> Back to Top </button></a></th>
			<th></th>
			<th></th>	
			<th><button type="submit"  name="all_items_contract" id="all_items_contract" class="pure-button pure-button-primary"> - Contract </button></th>
        </tr>
<?php
		}

		
#--------------------------------------------------------------------				
#--------------------------------------------------------------------	
#----------------------Ability----------------------------------	
#--------------------------------------------------------------------	
#--------------------------------------------------------------------	
?>			
<thead>
        <tr>
		 <th></th>
            <th colspan="2">Abilities</th>
			<th></th>
<?php		if ($_SESSION['shop']['abilities'] == 0){
				echo '<th><button type="submit"  name="abilities_all_expand" id="abilities_all_expand" class="pure-button pure-button-primary"> + Expand</button></th>';
			}
			else {
				echo '<th><button type="submit"  name="abilities_all_contract" id="abilities_all_contract" class="pure-button pure-button-primary"> - Contract</button></th>';
			}
?>
        </tr>
    </thead>



<?php  				
		
		if ($_SESSION['shop']['abilities'] == 1) {

			//check if he has any abilities
			$query="SELECT * FROM haracters_ability WHERE username='admin'";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);
			$i=0;
			
			foreach ($data  as $key => $value){
					
				if (($key!=='username') && ($value !=0)){
					// print the GAMEE		
					$result=item_finder($item_name,$use,$datachange);
					
					$i=$i+1;
					
						echo '<tr><th><a href="#" class="tooltip"><img class="pure-img" src="/resources/images/ability/'.$data[0].'.png" alt="'.$data[0].'" style="width:128px;height:128px;">'.$result[0].'</a></th>';
						echo '<th>'.$result[1].'</th>';
						echo '<th>'.$result[2].'</th>';
						echo '<th>'.$value.'</th></tr>';
					
				}
				
			}

		}



#reduct button on bottom and top
		if (($_SESSION['shop']['abilities'] == 1) && ($i!=0 )) {
?>		
		<tr class="pure-table-odd">
			<th><a href="#top"><button type="button"  name="backtotop" id="backtotop" class="pure-button pure-button-primary"> Back to Top </button></a></th>
			<th></th>
			<th></th>	
			<th><button type="submit"  name="abilities_all_contract" id="abilities_all_contract" class="pure-button pure-button-primary"> - Contract </button></th>
        </tr>
<?php
		} 


		
#--------------------------------------------------------------------							
			


//title main 4 expand 
#reduct button on bottom and top



</form>
?>


