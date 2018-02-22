<?php

function ability_finder ($ability_name,$use,$combat){

switch ($ability_name) {
    case 'ability_1':

		$name='Pet: Wolf';
		$description='You gain a pet wolf, which deals damage';
		$value='1000';

		if ($use) {

			if ($combat['round']['phase']==2 && ($combat['round']['attacker']===$combat['round']['active']) ){
				//Calculate if wolf hits
				$hitchance = 0.1*$combat[$combat['round']['active'].'_temp']['act_hit'];

				//Roll 1 to 100
				$chance = mt_rand(0,100);

				//Calculate the attackers hit chance
				if (($hitchance+$chance)>=8 ){


					//Calculate wolf damage

					$dmgmin=$combat[$combat['round']['active'].'_temp']['act_wep1_dmgmin']+$combat[$combat['round']['active'].'_temp']['act_wep1_dmgmin'] * $combat[$combat['round']['active'].'_temp']['act_agility'];
					$dmgmax=$combat[$combat['round']['active'].'_temp']['act_wep1_dmgmax']+ $combat[$combat['round']['active'].'_temp']['act_wep1_dmgmax'] * $combat[$combat['round']['active'].'_temp']['act_agility'];
					$damage = mt_rand(floor($dmgmin*0.1),floor($dmgmax*0.1));

					$chance = mt_rand(0,1000);

					if($combat[$combat['round']['active'].'_temp']['act_critical'] >= $chance){
						$damage=$damage*2;
					}

					//Armor reduction


					//Deal the wolf damage
					if($combat['round']['active']==='player1'){
						$combat['player2_round']['act_hp']=$combat['player2_round']['act_hp']-$damage;
					}else{
						$combat['player1_round']['act_hp']=$combat['player1_round']['act_hp']-$damage;
					}

					//Display the effect of use
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$combat['round']['active'].'_round']['char_nick'].' wolf deals '.$damage.'damage!</p>';

				}else{

					//Display the miss
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$combat['round']['active'].'_round']['char_nick'].' wolf attacks but misses!</p>';
				}
			}
		}

        break;

    case 'ability_2':
		$name='Regenaration';
		$description='Regenerate 5% of your health each round';
		$value='2000';

		if ($use) {
			//Use only on phase 2
			if ($combat['round']['phase']==2){


					$heal=floor(($combat[$combat['round']['active'].'_round']['act_vitality'])*10)*0.05;

					//Heal the player
					if($combat['round']['active']==='player1'){

						if ((($combat[$combat['round']['active'].'_round']['act_vitality'])*10)<= ($combat['player1_round']['act_hp']+$heal)){
							$combat['player1_round']['act_hp']=$combat['player1_round']['act_hp']+$heal;
						}
						else{
							$combat['player1_round']['act_hp']=(($combat[$combat['round']['active'].'_round']['act_vitality'])*10);
						}

					}else{
						if((($combat[$combat['round']['active'].'_round']['act_vitality'])*10)<= ($combat['player2_round']['act_hp']+$heal)){

							$combat['player2_round']['act_hp']=$combat['player2_round']['act_hp']+$heal;

						}else{
							$combat['player2_round']['act_hp']=(($combat[$combat['round']['active'].'_round']['act_vitality'])*10);
						}

					}

				//Echo message
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$combat['round']['active'].'_round']['char_nick'].' recoves '.$heal.' from regenaration ability!</p>';
			}

		}

        break;

     case 'ability_3':
		$name='Steal Weapon';
		$description='Steals the enemy weapon.';
		$value='3000';

		if ($use) {
			//If switch was made
			if(!isset($combat[$combat['round']['active'].'_round']['weaponswitch'])){

				if ($combat['round']['active']==='player1'){
					$player_act='player1';
					$player_inc='player2';
				}else{
					$player_act='player2';
					$player_inc='player1';
				}

				//Calculate his % hp. Divide act_hp by the total hp
				$percenthp=($combat[$combat['round']['active'].'_round']['act_hp'])/(($combat[$combat['round']['active'].'_round']['act_vitality'])*10);

				//other weapon stats;
				$query="SELECT * FROM weapons WHERE id='".$combat[$player_act.'_temp']['act_wep1']."'";
				$result=pg_fetch_assoc(pg_query($query));

				//Player drops his weapon
				$combat[$player_act.'_temp']['act_strength']=$combat[$player_act.'_temp']['act_strength']-$result['strength'];
				$combat[$player_act.'_temp']['act_agility']=$combat[$player_act.'_temp']['act_agility']-$result['agility'];
				$combat[$player_act.'_temp']['act_intelligence']=$combat[$player_act.'_temp']['act_intelligence']-$result['intelligence'];
				$combat[$player_act.'_temp']['act_armor']=$combat[$player_act.'_temp']['act_armor']-$result['armor'];
				$combat[$player_act.'_temp']['act_vitality']=$combat[$player_act.'_temp']['act_vitality']-$result['vitality'];
				$combat[$player_act.'_temp']['act_hit']=$combat[$player_act.'_temp']['act_hit']-$result['hit'];
				$combat[$player_act.'_temp']['act_critical']=$combat[$player_act.'_temp']['act_critical']-$result['critical'];
				$combat[$player_act.'_temp']['act_dodge']=$combat[$player_act.'_temp']['act_dodge']-$result['dodge'];
				$combat[$player_act.'_temp']['act_mastery']=$combat[$player_act.'_temp']['act_mastery']-$result['mastery'];
				$combat[$player_act.'_temp']['act_luck']=$combat[$player_act.'_temp']['act_luck']-$result['luck'];
				$combat[$player_act.'_temp']['act_expgain']=$combat[$player_act.'_temp']['act_expgain']-$result['expgain'];
				$combat[$player_act.'_round']['act_strength']=$combat[$player_act.'_round']['act_strength']-$result['strength'];
				$combat[$player_act.'_round']['act_agility']=$combat[$player_act.'_round']['act_agility']-$result['agility'];
				$combat[$player_act.'_round']['act_intelligence']=$combat[$player_act.'_round']['act_intelligence']-$result['intelligence'];
				$combat[$player_act.'_round']['act_armor']=$combat[$player_act.'_round']['act_armor']-$result['armor'];
				$combat[$player_act.'_round']['act_vitality']=$combat[$player_act.'_round']['act_vitality']-$result['vitality'];
				$combat[$player_act.'_round']['act_hit']=$combat[$player_act.'_round']['act_hit']-$result['hit'];
				$combat[$player_act.'_round']['act_critical']=$combat[$player_act.'_round']['act_critical']-$result['critical'];
				$combat[$player_act.'_round']['act_dodge']=$combat[$player_act.'_round']['act_dodge']-$result['dodge'];
				$combat[$player_act.'_round']['act_mastery']=$combat[$player_act.'_round']['act_mastery']-$result['mastery'];
				$combat[$player_act.'_round']['act_luck']=$combat[$player_act.'_round']['act_luck']-$result['luck'];
				$combat[$player_act.'_round']['act_expgain']=$combat[$player_act.'_round']['act_expgain']-$result['expgain'];


				//Set his weapon
				$combat[$player_act.'_temp']['act_wep1']=$combat[$player_inc.'_temp']['act_wep1'];
				$combat[$player_act.'_round']['act_wep1']=$combat[$player_inc.'_round']['act_wep1'];
				//Set his type
				$combat[$player_act.'_temp']['act_wep1_type']=$combat[$player_inc.'_temp']['act_wep1_type'];
				$combat[$player_act.'_round']['act_wep1_type']=$combat[$player_inc.'_round']['act_wep1_type'];
				//Set damages
				$combat[$player_act.'_temp']['act_wep1_dmgmin']=$combat[$player_inc.'_temp']['act_wep1_dmgmin'];
				$combat[$player_act.'_temp']['act_wep1_dmgmax']=$combat[$player_inc.'_temp']['act_wep1_dmgmax'];
				$combat[$player_act.'_round']['act_wep1_dmgmin']=$combat[$player_inc.'_round']['act_wep1_dmgmin'];
				$combat[$player_act.'_round']['act_wep1_dmgmax']=$combat[$player_inc.'_round']['act_wep1_dmgmax'];


				//Enemy
				//Set his weapon
				$combat[$player_inc.'_temp']['act_wep1']=$combat[$player_inc.'_temp']['act_wep2'];
				$combat[$player_inc.'_round']['act_wep1']=$combat[$player_inc.'_round']['act_wep2'];
				$combat[$player_inc.'_temp']['act_wep2']='NONE';
				$combat[$player_inc.'_round']['act_wep2']='NONE';
				//Set his type
				$combat[$player_inc.'_temp']['act_wep1_type']=$combat[$player_inc.'_temp']['act_wep2_type'];
				$combat[$player_inc.'_round']['act_wep1_type']=$combat[$player_inc.'_round']['act_wep2_type'];
				$combat[$player_inc.'_temp']['act_wep2_type']='NONE';
				$combat[$player_inc.'_round']['act_wep2_type']='NONE';
				//Set damages
				$combat[$player_inc.'_temp']['act_wep1_dmgmin']=$combat[$player_inc.'_temp']['act_wep2_dmgmin'];
				$combat[$player_inc.'_temp']['act_wep1_dmgmax']=$combat[$player_inc.'_temp']['act_wep2_dmgmax'];
				$combat[$player_inc.'_round']['act_wep1_dmgmin']=$combat[$player_inc.'_round']['act_wep2_dmgmin'];
				$combat[$player_inc.'_round']['act_wep1_dmgmax']=$combat[$player_inc.'_round']['act_wep2_dmgmax'];
				$combat[$player_inc.'_temp']['act_wep2_dmgmin']=1;
				$combat[$player_inc.'_temp']['act_wep2_dmgmax']=2;
				$combat[$player_inc.'_round']['act_wep2_dmgmin']=1;
				$combat[$player_inc.'_round']['act_wep2_dmgmax']=2;


				//other weapon stats;
				$query="SELECT * FROM weapons WHERE id='".$combat[$player_act.'_temp']['act_wep1']."'";
				$result=pg_fetch_assoc(pg_query($query));


				//Add to player 1
				$combat[$player_act.'_temp']['act_strength']=$combat[$player_act.'_temp']['act_strength']+$result['strength'];
				$combat[$player_act.'_temp']['act_agility']=$combat[$player_act.'_temp']['act_agility']+$result['agility'];
				$combat[$player_act.'_temp']['act_intelligence']=$combat[$player_act.'_temp']['act_intelligence']+$result['intelligence'];
				$combat[$player_act.'_temp']['act_armor']=$combat[$player_act.'_temp']['act_armor']+$result['armor'];
				$combat[$player_act.'_temp']['act_vitality']=$combat[$player_act.'_temp']['act_vitality']+$result['vitality'];
				$combat[$player_act.'_temp']['act_hit']=$combat[$player_act.'_temp']['act_hit']+$result['hit'];
				$combat[$player_act.'_temp']['act_critical']=$combat[$player_act.'_temp']['act_critical']+$result['critical'];
				$combat[$player_act.'_temp']['act_dodge']=$combat[$player_act.'_temp']['act_dodge']+$result['dodge'];
				$combat[$player_act.'_temp']['act_mastery']=$combat[$player_act.'_temp']['act_mastery']+$result['mastery'];
				$combat[$player_act.'_temp']['act_luck']=$combat[$player_act.'_temp']['act_luck']+$result['luck'];
				$combat[$player_act.'_temp']['act_expgain']=$combat[$player_act.'_temp']['act_expgain']+$result['expgain'];
				$combat[$player_act.'_round']['act_strength']=$combat[$player_act.'_round']['act_strength']+$result['strength'];
				$combat[$player_act.'_round']['act_agility']=$combat[$player_act.'_round']['act_agility']+$result['agility'];
				$combat[$player_act.'_round']['act_intelligence']=$combat[$player_act.'_round']['act_intelligence']+$result['intelligence'];
				$combat[$player_act.'_round']['act_armor']=$combat[$player_act.'_round']['act_armor']+$result['armor'];
				$combat[$player_act.'_round']['act_vitality']=$combat[$player_act.'_round']['act_vitality']+$result['vitality'];
				$combat[$player_act.'_round']['act_hit']=$combat[$player_act.'_round']['act_hit']+$result['hit'];
				$combat[$player_act.'_round']['act_critical']=$combat[$player_act.'_round']['act_critical']+$result['critical'];
				$combat[$player_act.'_round']['act_dodge']=$combat[$player_act.'_round']['act_dodge']+$result['dodge'];
				$combat[$player_act.'_round']['act_mastery']=$combat[$player_act.'_round']['act_mastery']+$result['mastery'];
				$combat[$player_act.'_round']['act_luck']=$combat[$player_act.'_round']['act_luck']+$result['luck'];
				$combat[$player_act.'_round']['act_expgain']=$combat[$player_act.'_round']['act_expgain']+$result['expgain'];

				//Remove from player 2
				$combat[$player_inc.'_temp']['act_strength']=$combat[$player_inc.'_temp']['act_strength']-$result['strength'];
				$combat[$player_inc.'_temp']['act_agility']=$combat[$player_inc.'_temp']['act_agility']-$result['agility'];
				$combat[$player_inc.'_temp']['act_intelligence']=$combat[$player_inc.'_temp']['act_intelligence']-$result['intelligence'];
				$combat[$player_inc.'_temp']['act_armor']=$combat[$player_inc.'_temp']['act_armor']-$result['armor'];
				$combat[$player_inc.'_temp']['act_vitality']=$combat[$player_inc.'_temp']['act_vitality']-$result['vitality'];
				$combat[$player_inc.'_temp']['act_hit']=$combat[$player_inc.'_temp']['act_hit']-$result['hit'];
				$combat[$player_inc.'_temp']['act_critical']=$combat[$player_inc.'_temp']['act_critical']-$result['critical'];
				$combat[$player_inc.'_temp']['act_dodge']=$combat[$player_inc.'_temp']['act_dodge']-$result['dodge'];
				$combat[$player_inc.'_temp']['act_mastery']=$combat[$player_inc.'_temp']['act_mastery']-$result['mastery'];
				$combat[$player_inc.'_temp']['act_luck']=$combat[$player_inc.'_temp']['act_luck']-$result['luck'];
				$combat[$player_inc.'_temp']['act_expgain']=$combat[$player_inc.'_temp']['act_expgain']-$result['expgain'];
				$combat[$player_inc.'_round']['act_strength']=$combat[$player_inc.'_round']['act_strength']-$result['strength'];
				$combat[$player_inc.'_round']['act_agility']=$combat[$player_inc.'_round']['act_agility']-$result['agility'];
				$combat[$player_inc.'_round']['act_intelligence']=$combat[$player_inc.'_round']['act_intelligence']-$result['intelligence'];
				$combat[$player_inc.'_round']['act_armor']=$combat[$player_inc.'_round']['act_armor']-$result['armor'];
				$combat[$player_inc.'_round']['act_vitality']=$combat[$player_inc.'_round']['act_vitality']-$result['vitality'];
				$combat[$player_inc.'_round']['act_hit']=$combat[$player_inc.'_round']['act_hit']-$result['hit'];
				$combat[$player_inc.'_round']['act_critical']=$combat[$player_inc.'_round']['act_critical']-$result['critical'];
				$combat[$player_inc.'_round']['act_dodge']=$combat[$player_inc.'_round']['act_dodge']-$result['dodge'];
				$combat[$player_inc.'_round']['act_mastery']=$combat[$player_inc.'_round']['act_mastery']-$result['mastery'];
				$combat[$player_inc.'_round']['act_luck']=$combat[$player_inc.'_round']['act_luck']-$result['luck'];
				$combat[$player_inc.'_round']['act_expgain']=$combat[$player_inc.'_round']['act_expgain']-$result['expgain'];



				//Calculate hp
				$combat[$combat['round']['active'].'_round']['act_hp']=floor((($combat[$combat['round']['active'].'_round']['act_vitality'])*10)*$percenthp);

				//Note the switch was made
				$combat[$combat['round']['active'].'_round']['weaponswitch']='on';

				//Display a message
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$combat['round']['active'].'_round']['char_nick'].' steals the enemy weapon!</p>';
			}

		}

        break;

     case 'ability_4':
		$name='Enrage';
		$description='When you are below 25% health increases the damage you deal by 100%';
		$value='4000';

		if ($use) {

			//Calculate his % hp. Divide act_hp by the total hp
			$percenthp=($combat[$combat['round']['active'].'_round']['act_hp'])/(($combat[$combat['round']['active'].'_round']['act_vitality'])*10);

			//Only use when hp is below 25% and before attacks only for attacker???;
			if ($percenthp<=0.25 && $combat['round']['phase']==1 && ($combat['round']['attacker']===$combat['round']['active']) ){

				//Enrage effects

				$combat[$combat['round']['active'].'_temp']['act_wep1_dmgmin']=$combat[$combat['round']['active'].'_temp']['act_wep1_dmgmin'] * 2;
				$combat[$combat['round']['active'].'_temp']['act_strength']=$combat[$combat['round']['active'].'_temp']['act_strength'] * 2;
				$combat[$combat['round']['active'].'_temp']['act_agility']=$combat[$combat['round']['active'].'_temp']['act_agility'] * 2;
				$combat[$combat['round']['active'].'_temp']['act_intelligence']=$combat[$combat['round']['active'].'_temp']['act_intelligence'] * 2;
				$combat[$combat['round']['active'].'_temp']['act_wep1_dmgmax']=$combat[$combat['round']['active'].'_temp']['act_wep1_dmgmax'] *2 ;

				//Message to display
				if($combat[$combat['round']['active'].'_round']['enraged']==='active'){

					//Display the effect of use
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$combat['round']['active'].'_round']['char_nick'].' is still Enraged!</p>';

				}else{

					//Display the effect of use
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$combat['round']['active'].'_round']['char_nick'].' is now Enraged!</p>';

				}

				//Note that the enrage effect happened
				$combat[$combat['round']['active'].'_round']['enraged']='active';

			}else{

				if($combat[$combat['round']['active'].'_round']['enraged']==='active'){

					//Display the effect of use
					$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$combat['round']['active'].'_round']['char_nick'].' is no longer Enraged!</p>';

				}else{

				//Reset the enrage effect if he enraged
				$combat[$combat['round']['active'].'_round']['enraged']='';


			}

		}
  }
        break;

	 case 'ability_5':
		$name='Thorns';
		$description='Reflects 10% of the damage you take';
		$value='5000';

		if ($use) {

			//Check if he took damage
			if ($combat[$combat['round']['active'].'_temp']['damagetaken']>0){

				//Calculate damage
				$damage=floor($combat[$combat['round']['active'].'_temp']['damagetaken']*0.1);

				//Armor reduction

					//Update enemy life
					if($combat['round']['active']==='player1'){
						$combat['player2_round']['act_hp']=$combat['player2_round']['act_hp']-$damage;
					}else{
						$combat['player1_round']['act_hp']=$combat['player1_round']['act_hp']-$damage;
					}

				//Display the effect of use
				$combat['output']['text']=$combat['output']['text'].'<p>'.$combat[$combat['round']['active'].'_round']['char_nick'].' reflects '.$damage.' from his thorns!</p>';


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


return $combat;

}

?>
