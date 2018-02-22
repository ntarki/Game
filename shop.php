<?php
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


$datachange='';
$use=0;
$armor_c=7;
$weapons_c=7;
$item_c=5;
$ability_c=5;
$i=1;
$u=0;

while (($u<1) && ($i<$weapons_c)) {

	if (isset($_POST['weapon_'.$i.'_buy'])) {
        $u=$u+1;

		//get value of option
		if (isset($_POST['weapon_'.$i.'_sel_b'])) {
			if (($_POST['weapon_'.$i.'_sel_b']==='1') ||
    			($_POST['weapon_'.$i.'_sel_b']==='2') ||
				($_POST['weapon_'.$i.'_sel_b']==='3') ||
				($_POST['weapon_'.$i.'_sel_b']==='4') ||
				($_POST['weapon_'.$i.'_sel_b']==='5') ||
				($_POST['weapon_'.$i.'_sel_b']==='10') ||
				($_POST['weapon_'.$i.'_sel_b']==='25') ||
				($_POST['weapon_'.$i.'_sel_b']==='50') ||
				($_POST['weapon_'.$i.'_sel_b']==='100')){

					$quantity=	$_POST['weapon_'.$i.'_sel_b'];

			}
			else {
				$quantity=1;
			}
		}
		else{
		$quantity=1;
		}
		// calculate cost
		$query="SELECT value FROM weapons WHERE id='weapon_".$i."'";
		$result = pg_query($query);
		$temp=pg_fetch_row($result);
		$cost=$temp[0]*$quantity;

		//check if he has enough money
		$query= "
UPDATE characters_currency
SET gold=gold-".$cost."
WHERE username ='".$_SESSION['login']['username']."' AND (0<=(gold-".$cost."))";


		$result = pg_query($query);
		$temp = pg_affected_rows($result);

		//update or display error

		if ($temp==1){

?>
<p>You bought sucessfully NUMBER x NAME.</p>
<?php
		// invetory


$query= "
UPDATE characters_weapons
SET weapon_".$i."= weapon_".$i."+1
WHERE (username='".$_SESSION['login']['username']."')";

		$result = pg_query($query);

		}
		else {

?>
<p>Not enought gold to buy NUMBER x NAME.</p>
<?php

		}



	}





	if (isset($_POST['weapon_'.$i.'_sell'])) {
        $u=$u+1;
		if (isset($_POST['weapon_'.$i.'_sel_s'])) {
			if (($_POST['weapon_'.$i.'_sel_s']==='1') ||
    			($_POST['weapon_'.$i.'_sel_s']==='2') ||
				($_POST['weapon_'.$i.'_sel_s']==='3') ||
				($_POST['weapon_'.$i.'_sel_s']==='4') ||
				($_POST['weapon_'.$i.'_sel_s']==='5') ||
				($_POST['weapon_'.$i.'_sel_s']==='10') ||
				($_POST['weapon_'.$i.'_sel_s']==='25') ||
				($_POST['weapon_'.$i.'_sel_s']==='50') ||
				($_POST['weapon_'.$i.'_sel_s']==='100')){

					$quantity=	$_POST['weapon_'.$i.'_sel_b'];

			}
			else {
				$quantity=1;
			}
		}
		else{
		$quantity=1;
		}



		// calculate cost
		$query="SELECT value FROM weapons WHERE id='weapon_".$i."'";
		$result = pg_query($query);
		$temp=pg_fetch_row($result);
		$cost=floor($temp[0]/4)*$quantity;


			//check if he has enough weapons
		$query= "
UPDATE characters_weapons
SET weapon_".$i."= weapon_".$i."-".$quantity."
WHERE username='".$_SESSION['login']['username']."' AND (0<=(weapon_".$i."-".$quantity."))";


		$result = pg_query($query);
		$temp = pg_affected_rows($result);

		if ($temp==1){

?>
<p>You sold sucessfully NUMBER x NAME.</p>
<?php
		// invetory


$query= "
UPDATE characters_currency
SET gold=gold+".$cost."
WHERE username='".$_SESSION['login']['username']."'";

		$result = pg_query($query);
			}

		else {

?>
<p>Not enought weapons to sell NUMBER x NAME.</p>
<?php

		}
		}
		$i=$i+1;


	}

$i=1;
$u=0;

while (($u<1) && ($i<$armor_c)) {

	if (isset($_POST['armor_'.$i.'_buy'])) {
        $u=$u+1;

		//get value of option
		if (isset($_POST['armor_'.$i.'_sel_b'])) {
			if (($_POST['armor_'.$i.'_sel_b']==='1') ||
    			($_POST['armor_'.$i.'_sel_b']==='2') ||
				($_POST['armor_'.$i.'_sel_b']==='3') ||
				($_POST['armor_'.$i.'_sel_b']==='4') ||
				($_POST['armor_'.$i.'_sel_b']==='5') ||
				($_POST['armor_'.$i.'_sel_b']==='10') ||
				($_POST['armor_'.$i.'_sel_b']==='25') ||
				($_POST['armor_'.$i.'_sel_b']==='50') ||
				($_POST['armor_'.$i.'_sel_b']==='100')){

					$quantity=	$_POST['armor_'.$i.'_sel_b'];

			}
			else {
				$quantity=1;
			}
		}
		else{
		$quantity=1;
		}
		// calculate cost
		$query="SELECT value FROM armor WHERE id='armor_".$i."'";
		$result = pg_query($query);
		$temp=pg_fetch_row($result);
		$cost=$temp[0]*$quantity;

		//check if he has enough money
		$query= "
UPDATE characters_currency
SET gold=gold-".$cost."
WHERE username='".$_SESSION['login']['username']."' AND (0<=(gold-".$cost."))";


		$result = pg_query($query);
		$temp = pg_affected_rows($result);

		//update or display error

		if ($temp==1){

?>
<p>You bought sucessfully NUMBER x NAME.</p>
<?php
		// invetory


$query= "
UPDATE characters_armor
SET armor_".$i."= armor_".$i."+1
WHERE (username='".$_SESSION['login']['username']."')";

		$result = pg_query($query);

		}
		else {

?>
<p>Not enought gold to buy NUMBER x NAME.</p>
<?php

		}



	}





	if (isset($_POST['armor_'.$i.'_sell'])) {
        $u=$u+1;
		if (isset($_POST['armor_'.$i.'_sel_s'])) {
			if (($_POST['armor_'.$i.'_sel_s']==='1') ||
    			($_POST['armor_'.$i.'_sel_s']==='2') ||
				($_POST['armor_'.$i.'_sel_s']==='3') ||
				($_POST['armor_'.$i.'_sel_s']==='4') ||
				($_POST['armor_'.$i.'_sel_s']==='5') ||
				($_POST['armor_'.$i.'_sel_s']==='10') ||
				($_POST['armor_'.$i.'_sel_s']==='25') ||
				($_POST['armor_'.$i.'_sel_s']==='50') ||
				($_POST['armor_'.$i.'_sel_s']==='100')){

					$quantity=	$_POST['armor_'.$i.'_sel_b'];

			}
			else {
				$quantity=1;
			}
		}
		else{
		$quantity=1;
		}



		// calculate cost
		$query="SELECT value FROM armor WHERE id='armor_".$i."'";
		$result = pg_query($query);
		$temp=pg_fetch_row($result);
		$cost=floor($temp[0]/4)*$quantity;


			//check if he has enough armors
		$query= "
UPDATE characters_armor
SET armor_".$i."= armor_".$i."-".$quantity."
WHERE username='".$_SESSION['login']['username']."' AND (0<=(armor_".$i."-".$quantity."))";


		$result = pg_query($query);
		$temp = pg_affected_rows($result);

		if ($temp==1){

?>
<p>You sold sucessfully NUMBER x NAME.</p>
<?php
		// invetory


$query= "
UPDATE characters_currency
SET gold=gold+".$cost."
WHERE username='".$_SESSION['login']['username']."'";

		$result = pg_query($query);
			}

		else {

?>
<p>Not enough armors to sell NUMBER x NAME.</p>
<?php

		}
		}
		$i=$i+1;


	}

	$i=1;
$u=0;

while (($u<1) && ($i<$item_c)) {

	if (isset($_POST['item_'.$i.'_buy'])) {
        $u=$u+1;

		//get value of option
		if (isset($_POST['item_'.$i.'_sel_b'])) {
			if (($_POST['item_'.$i.'_sel_b']==='1') ||
    			($_POST['item_'.$i.'_sel_b']==='2') ||
				($_POST['item_'.$i.'_sel_b']==='3') ||
				($_POST['item_'.$i.'_sel_b']==='4') ||
				($_POST['item_'.$i.'_sel_b']==='5') ||
				($_POST['item_'.$i.'_sel_b']==='10') ||
				($_POST['item_'.$i.'_sel_b']==='25') ||
				($_POST['item_'.$i.'_sel_b']==='50') ||
				($_POST['item_'.$i.'_sel_b']==='100')){

					$quantity=	$_POST['item_'.$i.'_sel_b'];

			}
			else {
				$quantity=1;
			}
		}
		else{
		$quantity=1;
		}
		// calculate cost

		$result = item_finder('item_'.$i,$use,$datachange);

		$cost=$result['value']*$quantity;

		//check if he has enough money
		$query= "
UPDATE characters_currency
SET gold=gold-".$cost."
WHERE username='".$_SESSION['login']['username']."' AND (0<=(gold-".$cost."))";


		$result = pg_query($query);
		$temp = pg_affected_rows($result);

		//update or display error

		if ($temp==1){

?>
<p>You bought sucessfully NUMBER x NAME.</p>
<?php
		// invetory


$query= "
UPDATE characters_items
SET item_".$i."= item_".$i."+1
WHERE (username='".$_SESSION['login']['username']."')";

		$result = pg_query($query);

		}
		else {

?>
<p>Not enought gold to buy NUMBER x NAME.</p>
<?php

		}



	}





	if (isset($_POST['item_'.$i.'_sell'])) {
        $u=$u+1;
		if (isset($_POST['item_'.$i.'_sel_s'])) {
			if (($_POST['item_'.$i.'_sel_s']==='1') ||
    			($_POST['item_'.$i.'_sel_s']==='2') ||
				($_POST['item_'.$i.'_sel_s']==='3') ||
				($_POST['item_'.$i.'_sel_s']==='4') ||
				($_POST['item_'.$i.'_sel_s']==='5') ||
				($_POST['item_'.$i.'_sel_s']==='10') ||
				($_POST['item_'.$i.'_sel_s']==='25') ||
				($_POST['item_'.$i.'_sel_s']==='50') ||
				($_POST['item_'.$i.'_sel_s']==='100')){

					$quantity=	$_POST['item_'.$i.'_sel_b'];

			}
			else {
				$quantity=1;
			}
		}
		else{
		$quantity=1;
		}



		// calculate cost
		$result = item_finder('item_'.$i,$use,$datachange);

		$cost=floor($result['value']/4)*$quantity;


			//check if he has enough armors
		$query= "
UPDATE characters_items
SET item_".$i."= item_".$i."-".$quantity."
WHERE username='".$_SESSION['login']['username']."' AND (0<=(item_".$i."-".$quantity."))";


		$result = pg_query($query);
		$temp = pg_affected_rows($result);

		if ($temp==1){

?>
<p>You sold sucessfully NUMBER x NAME.</p>
<?php
		// invetory


$query= "
UPDATE characters_currency
SET gold=gold+".$cost."
WHERE username='".$_SESSION['login']['username']."'";

		$result = pg_query($query);
			}

		else {

?>
<p>Not enough items to sell NUMBER x NAME.</p>
<?php

		}
		}
		$i=$i+1;


	}

	$i=1;
$u=0;
while (($u<1) && ($i<$ability_c)) {

	if (isset($_POST['ability_'.$i.'_buy'])) {
        $u=$u+1;

		//get value of option
		if (isset($_POST['ability_'.$i.'_sel_b'])) {
			if (($_POST['ability_'.$i.'_sel_b']==='1') ||
    			($_POST['ability_'.$i.'_sel_b']==='2') ||
				($_POST['ability_'.$i.'_sel_b']==='3') ||
				($_POST['ability_'.$i.'_sel_b']==='4') ||
				($_POST['ability_'.$i.'_sel_b']==='5') ||
				($_POST['ability_'.$i.'_sel_b']==='10') ||
				($_POST['ability_'.$i.'_sel_b']==='25') ||
				($_POST['ability_'.$i.'_sel_b']==='50') ||
				($_POST['ability_'.$i.'_sel_b']==='100')){

					$quantity=	$_POST['ability_'.$i.'_sel_b'];

			}
			else {
				$quantity=1;
			}
		}
		else{
		$quantity=1;
		}
		// calculate cost

		$result = ability_finder('ability_'.$i,$use,$datachange);

		$cost=$result['value']*$quantity;

		//check if he has enough money
		$query= "
UPDATE characters_currency
SET gold=gold-".$cost."
WHERE username='".$_SESSION['login']['username']."' AND (0<=(gold-".$cost."))";


		$result = pg_query($query);
		$temp = pg_affected_rows($result);

		//update or display error

		if ($temp==1){

?>
<p>You bought sucessfully NUMBER x NAME.</p>
<?php
		// invetory


$query= "
UPDATE characters_abilities
SET ability_".$i."= ability_".$i."+1
WHERE (username='".$_SESSION['login']['username']."')";

		$result = pg_query($query);

		}
		else {

?>
<p>Not enought gold to buy NUMBER x NAME.</p>
<?php

		}



	}





	if (isset($_POST['ability_'.$i.'_sell'])) {
        $u=$u+1;
		if (isset($_POST['ability_'.$i.'_sel_s'])) {
			if (($_POST['ability_'.$i.'_sel_s']==='1') ||
    			($_POST['ability_'.$i.'_sel_s']==='2') ||
				($_POST['ability_'.$i.'_sel_s']==='3') ||
				($_POST['ability_'.$i.'_sel_s']==='4') ||
				($_POST['ability_'.$i.'_sel_s']==='5') ||
				($_POST['ability_'.$i.'_sel_s']==='10') ||
				($_POST['ability_'.$i.'_sel_s']==='25') ||
				($_POST['ability_'.$i.'_sel_s']==='50') ||
				($_POST['ability_'.$i.'_sel_s']==='100')){

					$quantity=	$_POST['ability_'.$i.'_sel_b'];

			}
			else {
				$quantity=1;
			}
		}
		else{
		$quantity=1;
		}



		// calculate cost
		$result = ability_finder('ability_'.$i,$use,$datachange);

		$cost=floor($result['value']/4)*$quantity;


			//check if he has enough armors
		$query= "
UPDATE characters_abilities
SET ability_".$i."= ability_".$i."-".$quantity."
WHERE username='".$_SESSION['login']['username']."' AND (0<=(ability_".$i."-".$quantity."))";


		$result = pg_query($query);
		$temp = pg_affected_rows($result);

		if ($temp==1){

?>
<p>You sold sucessfully NUMBER x NAME.</p>
<?php
		// invetory


$query= "
UPDATE characters_currency
SET gold=gold+".$cost."
WHERE username='".$_SESSION['login']['username']."'";

		$result = pg_query($query);
			}

		else {

?>
<p>Not enough abilitys to sell NUMBER x NAME.</p>
<?php

		}
		}
		$i=$i+1;


	}
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
    max-width: 800px;
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

form {
 line-height: 70%;
}

.content{
	text-align:center;
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
        <li class="pure-menu-item "><a href="create.php" class ="pure-menu-link">Create</a></li>


				<li class="pure-menu-item pure-menu-disabled menu-item-divided">Account</li>
				<li class="pure-menu-item "><a href="inventory.php" class="pure-menu-link">Inventory</a></li>
        <li class="pure-menu-item pure-menu-selected"><a href="shop.php" class="pure-menu-link">Shop</a></li>
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

	<form  method="post">
<table>
<thead>
<tr>
<td><button type="submit"  name="weapons_main" id="weapons_main" class="pure-button pure-button-primary">Weapons</button></td>
<td><button type="submit"  name="armor_main" id="armor_main" class="pure-button pure-button-primary">Armor</button></td>
<td><button type="submit"  name="items_main" id="items_main" class="pure-button pure-button-primary">Items</button></td>
<td><button type="submit"  name="abilities_main" id="abilities_main" class="pure-button pure-button-primary">Abilities</button></td>
</tr>
</thead>

<?php if (isset($_POST['weapons_main']) || isset($_POST['weapons_main_all']) || isset($_POST['weapons_main_melee']) || isset($_POST['weapons_main_ranged']) || isset($_POST['weapons_main_magic']) ){?>
<tr>
<td><button type="submit"  name="weapons_main_all" id="weapons_main_all" class="pure-button pure-button-primary">All</button></td>
<td><button type="submit"  name="weapons_main_melee" id="weapons_main_melee" class="pure-button pure-button-primary">Melee</button></td>
<td><button type="submit"  name="weapons_main_ranged" id="weapons_main_ranged" class="pure-button pure-button-primary">Ranged</button></td>
<td><button type="submit"  name="weapons_main_magic" id="weapons_main_magic" class="pure-button pure-button-primary">Magic</button></td>
</tr>
<?php }?>


<?php if (isset($_POST['armor_main']) ||  isset($_POST['armor_main_all']) || isset($_POST['armor_main_helmet']) || isset($_POST['armor_main_chest'])|| isset($_POST['armor_main_gloves']) || isset($_POST['armor_main_pants']) || isset($_POST['armor_main_boots']) || isset($_POST['armor_main_ring']) || isset($_POST['armor_main_trinket']) ){?>
<tr>
<td><button type="submit"  name="armor_main_all" id="armor_main_all" class="pure-button pure-button-primary">All</button></td>
<td><button type="submit"  name="armor_main_helmet" id="armor_main_helmet" class="pure-button pure-button-primary">Helmet</button></td>
<td><button type="submit"  name="armor_main_chest" id="armor_main_chest" class="pure-button pure-button-primary">Chest</button></td>
<td><button type="submit"  name="armor_main_gloves" id="armor_main_gloves" class="pure-button pure-button-primary">Gloves</button></td>
</tr>
<tr>
<td><button type="submit"  name="armor_main_pants" id="armor_main_pants" class="pure-button pure-button-primary">Pants</button></td>
<td><button type="submit"  name="armor_main_boots" id="armor_main_boots" class="pure-button pure-button-primary">Boots</button></td>
<td><button type="submit"  name="armor_main_ring" id="armor_main_ring" class="pure-button pure-button-primary">Ring</button></td>
<td><button type="submit"  name="armor_main_trinket" id="armor_main_trinket" class="pure-button pure-button-primary">Trinket</button></td>
</tr>
<?php }?>

</table>
</form>
</div>

<div class="content">
<div class="inventory_main">
<?php
$weapons= array('melee','ranged','magic');

//Weapons
if (isset($_POST['weapons_main_all'])){

	foreach($weapons as $id => $itemstyle){

		$_POST['weapons_main_'.$itemstyle]='on';

	}

}


//Weapons MELEE
foreach($weapons as $id => $itemstyle){
	if (isset($_POST['weapons_main_'.$itemstyle])){

		//check if he has any items
		$query="SELECT * FROM characters_weapons WHERE username='admin' ";
		$result = pg_query($query);
		$data = pg_fetch_assoc($result);
		$i=0;

		foreach ($data  as $key => $value){

			if (($key!=='username') && ($value !=='0')){

				// print the GAMEE
				$query="SELECT id, description, name, value,type FROM weapons WHERE id='".$key."' AND type='".ucfirst($itemstyle)."'";
				$result = pg_query($query);
				$temp = pg_fetch_row($result);

				if  ($temp){
					echo '<tr><td><a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/'.$temp[0].'.png" alt="'.$temp[0].'" style="width:64px;height:64px;">'.$temp[1].'</a></td>';
					echo '<td>'.$temp[2].'</td>';
					echo '<td>'.$temp[3].'</td>';
					echo '<td>'.floor($temp[3]/4).'</td>';
					$i=$i+1;
					//Buy sell buttons
					echo '<td><select id="'.$temp[0].'_sel_s" name="'.$temp[0].'_sel_s"><option id="'.$temp[0].'_1_sell" value="1">1</option><option id="'.$temp[0].'_2_sell" value="2">2</option><option id="'.$temp[0].'_3_sell" value="3">3</option><option id="'.$temp[0].'_4_sell" value="4">4</option><option id="'.$temp[0].'_5_sell" value="5">5</option><option id="'.$temp[0].'_10_sell" value="10">10</option><option id="'.$temp[0].'_25_sell" value="25">25</option><option id="'.$temp[0].'_50_sell" value="50">50</option><option id="'.$temp[0].'_100_sell" value="100">100</option></select>&nbsp;&nbsp;<button type="submit"  id="'.$temp[0].'_sell" name="'.$temp[0].'_sell"  class="pure-button pure-button-primary">Sell</button></td>';
					//Buy sell buttons
					echo '<td><select id="'.$temp[0].'_sel_b" name="'.$temp[0].'_sel_b" ><option id="'.$temp[0].'_1_buy" value="1">1</option><option id="'.$temp[0].'_2_buy" value="2">2</option><option id="'.$temp[0].'_3_buy" value="3">3</option><option id="'.$temp[0].'_4_buy" value="4">4</option><option id="'.$temp[0].'_5_buy" value="5">5</option><option id="'.$temp[0].'_10_buy" value="10">10</option><option id="'.$temp[0].'_25_buy" value="25">25</option><option id="'.$temp[0].'_50_buy" value="50">50</option><option id="'.$temp[0].'_100_buy" value="100">100</option></select>&nbsp;&nbsp;<button type="submit"  id="'.$temp[0].'_buy" name="'.$temp[0].'_buy"  class="pure-button pure-button-primary">Buy</button></td></tr>';
				}
			}
		}
	}
}

?>
<?php
$equipment= array('helmet','chest','gloves','pants','boots','ring','trinket');

//Armor All
if (isset($_POST['armor_main_all'])) {

	foreach($equipment as $id => $itemstyle){

		$_POST['armor_main_'.$itemstyle]='on';

	}
}

//Armor HELMET


foreach($equipment as $id => $itemstyle){

	if (isset($_POST['armor_main_'.$itemstyle])) {

		//check if he has any items
		$query="SELECT * FROM characters_armor WHERE username='admin' ";
		$result = pg_query($query);
		$data = pg_fetch_assoc($result);
		$i=0;

		foreach ($data  as $key => $value){

			if (($key!=='username') && ($value !=='0')){
				// print the GAMEE
				$query="SELECT id, description, name, value,type FROM armor WHERE id='".$key."' AND type='".ucfirst($itemstyle)."'";
				$result = pg_query($query);
				$temp = pg_fetch_row($result);

				if  ($temp){
					echo '<tr><td><a href="#" class="tooltip"><img class="pure-img" src="/resources/images/armor/'.$temp[0].'.png" alt="'.$temp[0].'" style="width:64px;height:64px;">'.$temp[1].'</a></td>';
					echo '<td>'.$temp[2].'</td>';
					echo '<td>'.$temp[3].'</td>';
					echo '<td>'.floor($temp[3]/4).'</td>';
						//Buy sell buttons
						//Buy sell buttons
					echo '<td><select id="'.$temp[0].'_sel_s" name="'.$temp[0].'_sel_s"><option id="'.$temp[0].'_1_sell" value="1">1</option><option id="'.$temp[0].'_2_sell" value="2">2</option><option id="'.$temp[0].'_3_sell" value="3">3</option><option id="'.$temp[0].'_4_sell" value="4">4</option><option id="'.$temp[0].'_5_sell" value="5">5</option><option id="'.$temp[0].'_10_sell" value="10">10</option><option id="'.$temp[0].'_25_sell" value="25">25</option><option id="'.$temp[0].'_50_sell" value="50">50</option><option id="'.$temp[0].'_100_sell" value="100">100</option></select>&nbsp;&nbsp;<button type="submit"  id="'.$temp[0].'_sell" name="'.$temp[0].'_sell"  class="pure-button pure-button-primary">Sell</button></td>';
					//Buy sell buttons
					echo '<td><select id="'.$temp[0].'_sel_b" name="'.$temp[0].'_sel_b"><option id="'.$temp[0].'_1_buy" value="1">1</option><option id="'.$temp[0].'_2_buy" value="2">2</option><option id="'.$temp[0].'_3_buy" value="3">3</option><option id="'.$temp[0].'_4_buy" value="4">4</option><option id="'.$temp[0].'_5_buy" value="5">5</option><option id="'.$temp[0].'_10_buy" value="10">10</option><option id="'.$temp[0].'_25_buy" value="25">25</option><option id="'.$temp[0].'_50_buy" value="50">50</option><option id="'.$temp[0].'_100_buy" value="100">100</option></select>&nbsp;&nbsp;<button type="submit"  id="'.$temp[0].'_buy" name="'.$temp[0].'_buy"  class="pure-button pure-button-primary">Buy</button></td></tr>';
					$i=$i+1;

				}
			}
		}
	}
}
?>

<?php

if (isset($_POST['abilities_main'])) {

			//check if he has any abilities
			$query="SELECT * FROM characters_abilities WHERE username='admin' ";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);
			$i=0;

			foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){
					// print the GAMEE
					$result=ability_finder($key,$use,$datachange);

					$i=$i+1;

						echo '<tr><td><a href="#" class="tooltip"><img class="pure-img" src="/resources/images/ability/'.$key.'.png" alt="'.$key.'" style="width:64px;height:64px;"><span>'.$result['description'].'</span></a></td>';
						echo '<td>'.$result['name'].'</td>';
						echo '<td>'.$result['value'].'</td>';
						echo '<td>'.floor($result['value']/4).'</td>';
							//Buy sell buttons
						echo '<td><select id="'.$key.'_sel_s" name="'.$key.'_sel_s"><option id="'.$key.'_1_sell" value="1">1</option><option id="'.$key.'_2_sell" value="2">2</option><option id="'.$key.'_3_sell" value="3">3</option><option id="'.$key.'_4_sell" value="4">4</option><option id="'.$key.'_5_sell" value="5">5</option><option id="'.$key.'_10_sell" value="10">10</option><option id="'.$key.'_25_sell" value="25">25</option><option id="'.$key.'_50_sell" value="50">50</option><option id="'.$key.'_100_sell" value="100">100</option></select>&nbsp;&nbsp;<button type="submit"  id="'.$key.'_sell" name="'.$key.'_sell"  class="pure-button pure-button-primary">Sell</button></td>';
						//Buy sell buttons
						echo '<td><select id="'.$key.'_sel_b" name="'.$key.'_sel_b"><option id="'.$key.'_1_buy" value="1">1</option><option id="'.$key.'_2_buy" value="2">2</option><option id="'.$key.'_3_buy" value="3">3</option><option id="'.$key.'_4_buy" value="4">4</option><option id="'.$key.'_5_buy" value="5">5</option><option id="'.$key.'_10_buy" value="10">10</option><option id="'.$key.'_25_buy" value="25">25</option><option id="'.$key.'_50_buy" value="50">50</option><option id="'.$key.'_100_buy" value="100">100</option></select>&nbsp;&nbsp;<button type="submit"  id="'.$key.'_buy" name="'.$key.'_buy"  class="pure-button pure-button-primary">Buy</button></td></tr>';


				}

			}

}


if (isset($_POST['items_main'])) {

			//check if he has any items
			$query="SELECT * FROM characters_items WHERE username='admin' ";
			$result = pg_query($query);
			$data = pg_fetch_assoc($result);
			$i=0;

			foreach ($data  as $key => $value){

				if (($key!=='username') && ($value !=='0')){
					// print the GAMEE
					$result=item_finder($key,$use,$datachange);

					$i=$i+1;

						echo '<tr><td><a href="#" class="tooltip"><img class="pure-img" src="/resources/images/item/'.$key.'.png" alt="'.$key.'" style="width:64px;height:64px;"><span>'.$result['description'].'</span></a></td>';
						echo '<td>'.$result['name'].'</td>';
						echo '<td>'.$result['value'].'</td>';
						echo '<td>'.floor($result['value']/4).'</td>';
							//Buy sell buttons
						echo '<td><select id="'.$key.'_sel_s" name="'.$key.'_sel_s"><option id="'.$key.'_1_sell" value="1">1</option><option id="'.$key.'_2_sell" value="2">2</option><option id="'.$key.'_3_sell" value="3">3</option><option id="'.$key.'_4_sell" value="4">4</option><option id="'.$key.'_5_sell" value="5">5</option><option id="'.$key.'_10_sell" value="10">10</option><option id="'.$key.'_25_sell" value="25">25</option><option id="'.$key.'_50_sell" value="50">50</option><option id="'.$key.'_100_sell" value="100">100</option></select>&nbsp;&nbsp;<button type="submit"  id="'.$key.'_sell" name="'.$key.'_sell"  class="pure-button pure-button-primary">Sell</button></td>';
						//Buy sell buttons
						echo '<td><select id="'.$key.'_sel_b" name="'.$key.'_sel_b"><option id="'.$key.'_1_buy" value="1">1</option><option id="'.$key.'_2_buy" value="2">2</option><option id="'.$key.'_3_buy" value="3">3</option><option id="'.$key.'_4_buy" value="4">4</option><option id="'.$key.'_5_buy" value="5">5</option><option id="'.$key.'_10_buy" value="10">10</option><option id="'.$key.'_25_buy" value="25">25</option><option id="'.$key.'_50_buy" value="50">50</option><option id="'.$key.'_100_buy" value="100">100</option></select>&nbsp;&nbsp;<button type="submit"  id="'.$key.'_buy" name="'.$key.'_buy"  class="pure-button pure-button-primary">Buy</button></td></tr>';


				}

			}

}


?>
</div>
</div>

<style>
.inventory_main {
	width:70%;
	text-align:center;
}
.body{
	text-align:center;

}
</style>
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
