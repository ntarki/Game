<?php

function item_finder ($item_name,$use,$combat){

switch ($item_name) {
    case 'item_1':

	 	//Item properties
		$name='Elixir of Agility';
		$description='Increases agility by 100% for one round';
		$value='10';
		if ($use) {

			//Only use on phase 1 and only if the player is attacking
			if ($combat['round']['phase']==1 &&  ($combat['round']['attacker']===$combat['round']['active'])){


				//Give 100% increased agility
				$combat[$combat['round']['active'].'_temp']['act_agility']=$combat[$combat['round']['active'].'_temp']['act_agility']*2;

				//Display the effect of use
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$combat['round']['active'].'_round']['char_nick'].' uses Elixir of Agility increasing his agility by 100%!</p>';

				//If the attacking player is player 1
				if ($combat['round']['active']==='player1'){

					//Check if he has more items
					$query="SELECT item_1 FROM characters_items WHERE username=''".$_SESSION['login']['username']."'";
					$result=pg_fetch_row(pg_query($query));

					//If he has more than one item remove one from inventory
					//or set his act_item_number to 'NONE'
					if ($result[0]>0){

						//Remove one item from inventory
						$query="UPDATE characters_items SET item_1=item_1-1 WHERE username=''".$_SESSION['login']['username']."' ;";
						pg_query($query);

					}else{

						//Set his act_item_number to 'NONE'
						$query="UPDATE characters_main SET act_item".$combat[$combat['round']['active'].'_round']['itemused']."=act_item".$combat[$combat['round']['active'].'_round']['itemused']."-1 WHERE char_nick=''".$combat[$combat['round']['active'].'_round']['char_nick']."' ;";
						pg_query($query);

					}


				}

				//The item_number has been used
				$combat[$combat['round']['active'].'_round']['itemused']=$combat[$combat['round']['active'].'_round']['itemused']+1;
			}

		}

        break;

    case 'item_2':

		//Item properties
		$name='Elixir of Strength';
		$description='Increases strength by 100% for one round';
		$value='10';

		if ($use) {

			//Only use on phase 1 and only if the player is attacking
			if ($combat['round']['phase']==1 &&  ($combat['round']['attacker']===$combat['round']['active'])){


				//Give 100% increased strength
				$combat[$combat['round']['active'].'_temp']['act_strength']=$combat[$combat['round']['active'].'_temp']['act_strength']*2;

				//Display the effect of use
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$combat['round']['active'].'_round']['char_nick'].' uses Elixir of Strength increasing his strength by 100%!</p>';

				//If the attacking player is player 1
				if ($combat['round']['active']==='player1'){

					//Check if he has more items
					$query="SELECT item_2 FROM characters_items WHERE username=''".$_SESSION['login']['username']."'";
					$result=pg_fetch_row(pg_query($query));

					//If he has more than one item remove one from inventory
					//or set his act_item_number to 'NONE'
					if ($result[0]>0){

						//Remove one item from inventory
						$query="UPDATE characters_items SET item_2=item_2-1 WHERE username=''".$_SESSION['login']['username']."' ;";
						pg_query($query);

					}else{

						//Set his act_item_number to 'NONE'
						$query="UPDATE characters_main SET act_item".$combat[$combat['round']['active'].'_round']['itemused']."=act_item".$combat[$combat['round']['active'].'_round']['itemused']."-1 WHERE char_nick=''".$combat[$combat['round']['active'].'_round']['char_nick']."' ;";
						pg_query($query);

					}


				}

				//The item_number has been used
				$combat[$combat['round']['active'].'_round']['itemused']=$combat[$combat['round']['active'].'_round']['itemused']+1;
			}

		}

        break;

     case 'item_3':

		//Item properties
		$value='10';
		$description='Elixir of Intelligence';
		$name='potion of shit3';

		if ($use) {

			//Only use on phase 1 and only if the player is attacking
			if ($combat['round']['phase']==1 &&  ($combat['round']['attacker']===$combat['round']['active'])){


				//Give 100% increased intelligence
				$combat[$combat['round']['active'].'_temp']['act_intelligence']=$combat[$combat['round']['active'].'_temp']['act_intelligence']*2;

				//Display the effect of use
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$combat['round']['active'].'_round']['char_nick'].' uses Elixir of Intelligence increasing his intelligence by 100%!</p>';

				//If the attacking player is player 1
				if ($combat['round']['active']==='player1'){

					//Check if he has more items
					$query="SELECT item_3 FROM characters_items WHERE username=''".$_SESSION['login']['username']."'";
					$result=pg_fetch_row(pg_query($query));

					//If he has more than one item remove one from inventory
					//or set his act_skill_number to 'NONE'
					if ($result[0]>0){

						//Remove one item from inventory
						$query="UPDATE characters_items SET item_3=item_3-1 WHERE username=''".$_SESSION['login']['username']."' ;";
						pg_query($query);

					}else{

						//Set his act_item_number to 'NONE'
						$query="UPDATE characters_main SET act_item".$combat[$combat['round']['active'].'_round']['itemused']."=act_item".$combat[$combat['round']['active'].'_round']['itemused']."-1 WHERE char_nick=''".$combat[$combat['round']['active'].'_round']['char_nick']."' ;";
						pg_query($query);

					}


				}

				//The item_number has been used
				$combat[$combat['round']['active'].'_round']['itemused']=$combat[$combat['round']['active'].'_round']['itemused']+1;
			}

		}

        break;

     case 'item_4':
		$value='10';
		$name='Potion of Health';
		$description='Restores 10% of your total healthpoints';

		if ($use) {

			//Calculate his % hp. Divide act_hp by the total hp
			$percenthp=($combat[$combat['round']['active'].'_round']['act_hp'])/(($combat[$combat['round']['active'].'_round']['act_vitality'])*10);

			//Only use when hp is below 90%;
			if ($percenthp<=0.9){


				//Give 10% health back
				$combat[$combat['round']['active'].'_round']['act_hp']=$combat[$combat['round']['active'].'_round']['act_hp']+floor((($combat[$combat['round']['active'].'_round']['act_vitality'])*10)/10);

				//Display the effect of use
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$combat['round']['active'].'_round']['char_nick'].' uses Potion of Health increasing his hp by 10%!</p>';

				//If the attacking player is player 1
				if ($combat['round']['active']==='player1'){

					//Check if he has more items
					$query="SELECT item_4 FROM characters_items WHERE username=''".$_SESSION['login']['username']."'";
					$result=pg_fetch_row(pg_query($query));

					//If he has more than one item remove one from inventory
					//or set his act_item_number to 'NONE'
					if ($result[0]>0){

						//Remove one item from inventory
						$query="UPDATE characters_items SET item_4=item_4-1 WHERE username=''".$_SESSION['login']['username']."' ;";
						pg_query($query);

					}else{

						//Set his act_item_number to 'NONE'
						$query="UPDATE characters_main SET act_item".$combat[$combat['round']['active'].'_round']['itemused']."=act_item".$combat[$combat['round']['active'].'_round']['itemused']."-1 WHERE char_nick=''".$combat[$combat['round']['active'].'_round']['char_nick']."' ;";
						pg_query($query);

					}


				}

				//The item_number has been used
				$combat[$combat['round']['active'].'_round']['itemused']=$combat[$combat['round']['active'].'_round']['itemused']+1;
			}
		}

        break;

	 case 'item_5':
	 $name='Major Potion of Health';
		$description='Restores 20% of your total healthpoints';
		$value='20';

		if ($use) {

			//Calculate his % hp. Divide act_hp by the total hp
			$percenthp=($combat[$combat['round']['active'].'_round']['act_hp'])/(($combat[$combat['round']['active'].'_round']['act_vitality'])*10);

			//Only use when hp is below 80%;
			if ($percenthp<=0.8){


				//Give 20% health back
				$combat[$combat['round']['active'].'_round']['act_hp']=$combat[$combat['round']['active'].'_round']['act_hp']+floor((($combat[$combat['round']['active'].'_round']['act_vitality'])*10)*0.2);

				//Display the effect of use
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$combat['round']['active'].'_round']['char_nick'].' uses Major Potion of Health increasing his hp by 20%!</p>';

				//If the attacking player is player 1
				if ($combat['round']['active']==='player1'){

					//Check if he has more items
					$query="SELECT item_5 FROM characters_items WHERE username=''".$_SESSION['login']['username']."'";
					$result=pg_fetch_row(pg_query($query));

					//If he has more than one item remove one from inventory
					//or set his act_item_number to 'NONE'
					if ($result[0]>0){

						//Remove one item from inventory
						$query="UPDATE characters_items SET item_5=item_5-1 WHERE username=''".$_SESSION['login']['username']."' ;";
						pg_query($query);

					}else{

						//Set his act_item_number to 'NONE'
						$query="UPDATE characters_main SET act_item".$combat[$combat['round']['active'].'_round']['itemused']."=act_item".$combat[$combat['round']['active'].'_round']['itemused']."-1 WHERE char_nick=''".$combat[$combat['round']['active'].'_round']['char_nick']."' ;";
						pg_query($query);

					}


				}

				//The item_number has been used
				$combat[$combat['round']['active'].'_round']['itemused']=$combat[$combat['round']['active'].'_round']['itemused']+1;
			}
		}

        break;
}

if ( isset($combat['player1_round']['act_hp'])) {

}else{

	$combat['description']=$description;
	$combat['value']=$value;
	$combat['name']=$name;

}

//In case for loot items

if ( isset($combat['player1_round']['loot_item'])) {

	//Roll rarity
	if ($combat['player1_round']['loot_item']==='legendary'){


		$combat['player1_round']['loot_item']=item_5;

	}elseif($combat['player1_round']['loot_item']==='epic'){


		$combat['player1_round']['loot_item']=item_3;

	}elseif($combat['player1_round']['loot_item']==='rare'){


		$combat['player1_round']['loot_item']=item_2;


	}elseif($combat['player1_round']['loot_item']==='uncommon'){

		$chance=mt_rand(1,2);

		if ($chance==1){

			$combat['player1_round']['loot_item']=item_4;

		}elseif ($chance==2){

			$combat['player1_round']['loot_item']=item_1;
		}

	}

}


return $combat;

}

?>
