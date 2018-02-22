<?php
//-----------------------------------------------

// Including the database function
include './resources/includes/databasefunc.inc';
// Including the database function
include './resources/includes/temp_session_save.inc';
// Including the database function
include './resources/includes/combat.php';
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

$random_players='on';

//-----------------------------------------------
//-----------------------------------------------

//Check if a button is set
$i=0;

while ($i<=8){

	//If it was pressed prepare to call fight function
	if (isset($_POST['fight'.$i])){

		$_SESSION['fight']['status']='on';
		$enemy=$_SESSION['multi']['random'.$i];
		$enemy_img=$_SESSION['multi']['randomimg'.$i];
		$enemy_nick=$_SESSION['multi']['randomnick'.$i];
	}

	//Increase counter
	$i=$i+1;
}



//check search input
if (isset($_POST['seachplayer'])){

	$random_players='off';
	if (preg_match("/^[a-zA-Z0-9_]{1,26}$/",  sanitize_input($_POST['search_name']))==1) {

		$search=sanitize_input($_POST['search_name']);

	}else{

		$search='';

	}

}

if (isset($_POST['randomreset'])){
	$random_players='on';
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


.buttonimg {
	padding-top:65px;
	width: 128px;
	height: 128px;
	background-color: #1f8dd6;
    background-repeat: no-repeat;
	color: black;
	font-size: 150%;
	font-weight: bold;
	vertical-align:text-bottom;
	     border: 2px solid #1f8dd6;
    border-radius: 10px;

}
.buttonimg:active {
	width: 128px;
	background-color: #333;
	height: 128px;
    background-repeat: no-repeat;
	color: black;
	font-size: 150%;
	font-weight: bold;
outline: none;
	 	     border: 2px solid;
    border-radius: 10px;
	vertical-align:text-bottom;

}
.buttonimg:focus {
	width: 128px;
	background-color: #333;
	height: 128px;
    background-repeat: no-repeat;
	color: black;
	font-size: 150%;
	font-weight: bold;
outline: none;
	 	     border: 2px solid;
    border-radius: 10px;
	vertical-align:text-bottom;

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
	display: inline-block;
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


.pure-img{


	display: inline-block;
height:32px;
width:32px;
}

.title3{
	width:20%;
	position: relative;
	display: inline-block;
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

<script>
var what = 'How to ignore the text inside the following tag<a href="#" class="tooltip"><img class="pure-img" src="/resources/images/weapons/weapon_2.png" alt="Knife" style="width:64px;height:64px;"><span><span class="white">Knife</span><br><span class="white">Melee</span><br><br><span class="whitelarge">1-3</span><br><span class="grey">Damage per Hit</span><br><br><span class="pristat"><span class="prinum">+1 Strength </span></span><br><br><br></span></a>!',
    i = 0,
    isTag,
    text,
    line,
    test;
function type(str,caption) {

    text = str.slice(0, ++i);

    if (text === str){
         document.getElementById(caption).innerHTML = text;

   text='';
   i=0;
   test=false;
   isTag = false;
   return;

    }

    document.getElementById(caption).innerHTML = text;

    var char = text.slice(-1);
    if( char === '<' ) {
           isTag = true;
    }
    if( char === '>' ){

       char = text.slice(-3);

        if (char === 'p">' ){
            isTag = true;
            test = true;

        }else if (!test){
            isTag = false;
        }
    }

    char = text.slice(-4);
    if (char === '</a>' ){
         test = false;
    }

    if (char === '</p>' ){
         line= true;
		 istag=false;
    }


	if (isTag) {
		return type(str,caption);
	}

    if (line) {
		line=false;
		return setTimeout(function(){type(str,caption)}, 3000);
	}else{
		setTimeout(function(){type(str,caption)}, 30);
	}



}

</script>



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
<?php
// Show if there isn't combat
if ($_SESSION['fight']['status']==='off'){
?>
    <div id="main">
        <div class="header">
            <h1>Multi player</h1>
            <h2>Search for a player</h2>
<form class="pure-form" name ="select1" method="post">
			<input  type="text"  name="search_name" id="search_name" pattern="[a-zA-Z0-9_]{1,26}"  title="Character name length between 1-26 characters. Valid characters are English letters,numbers and the _" placeholder="Enter character name" />
			<button type="submit"  name="seachplayer" id="seachplayer" class="pure-button pure-button-primary"> Search </button>
			</form>
        </div>

        <div class="content">
<?php
}else{ // Image 1 Image 2 Image 3
?>
    <div id="main">
        <div class="header">
            <div class="title3">
			<?php echo '<img class="pure-img" src="/resources/images/characters/character_'. $_SESSION['character']['char_pic'].'.png" alt="HTML5 Icon" style="width:128px;height:128px;">' ?>
			</br>
			<?php echo $_SESSION['character']['char_nick']?>
			</div>
			<div class="title3">
			<?php echo '<img class="pure-img" src="/resources/images/other/vs.png" alt="HTML5 Icon" style="width:128px;height:128px;">' ?>
			</br>

			</div>
			<div class="title3">
			<?php
			echo '<img class="pure-img" src="/resources/images/characters/character_'.$enemy_img.'.png" alt="HTML5 Icon" style="width:128px;height:128px;">' ?>
			</br>
			<?php echo $enemy_nick?>
			</div>
        </div>

        <div class="content">
<?php
}
?>

<form class="pure-form" name ="select2" method="post">
<?php

//if search was selected

//show random players other wise
if ($_SESSION['fight']['status']==='off'){

	if ($random_players==='on'){

		//Select 9 random players
		$query="SELECT * FROM characters_main WHERE (level>=(".$_SESSION['character']['level']." -4)) AND (level<=(".$_SESSION['character']['level']." +4)) AND (char_name!='".$_SESSION['character']['char_name']."')  ORDER BY random() LIMIT 9; ";
		$result=pg_fetch_all(pg_query($query));
		$i=0;

		//loop
		while ($i<=8){

			//3 per row
			if (isset($result[$i]['char_nick'])){

				//Generate the button and name it
				echo '<button type="submit"  name="fight'.$i.'" id="fight'.$i.'" class="buttonimg"> '.$result[$i]['char_nick'].' </button>';
				echo '&nbsp&nbsp&nbsp';
				//Save into session so I call the fight
				$_SESSION['multi']['random'.$i]=$result[$i]['char_name'];
				$_SESSION['multi']['randomimg'.$i]=$result[$i]['char_pic'];
				$_SESSION['multi']['randomnick'.$i]=$result[$i]['char_nick'];

				//Every 3 loops change row
				$line_breaker=($i+1) % 3;

				if ($line_breaker==0) {

					echo '<br/><br/>';

				}

			}

			$i=$i+1;
		}

	}else{

		//Select the requested
		$query="SELECT * FROM characters_main WHERE char_nick='".$search."' ";
		$result=pg_num_rows(pg_query($query));

		//Check if he exists
		if ($result>0){
				$query="SELECT * FROM characters_main WHERE char_nick='".$search."' ";
				$result=pg_fetch_assoc(pg_query($query));
				//Save into session so I call the fight
				$_SESSION['multi']['random0']=$result['char_name'];

				$_SESSION['multi']['randomimg0']=$result['char_pic'];
				$_SESSION['multi']['randomnick0']=$result['char_nick'];

				echo '<button type="submit"  name="fight0" id="fight0" class="buttonimg"> '.$result['char_nick'].' </button>';

		}else {

			echo 'No such player found!';
		}
		echo '</br></br><button type="submit"  name="randomreset" id="randomreset" class="pure-button pure-button-primary"> Random Characters </button>';
	}

}else{

		combat_func($_SESSION['character']['char_name'],$enemy);


}

?>
</form>
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
<style>
<?php
$i=0;

while ($i<=8){

	//Generate button number
	if (isset($_SESSION['multi']['randomimg'.$i])){
		//Show the appropriate enemy image
		echo '#fight'.$i.'{';
		echo 'background-image:url(/resources/images/characters/character_'.$_SESSION['multi']['randomimg'.$i].'.png)';
		echo '}';
	}
	//Increase counter
	$i=$i+1;
}
?>
</style>
</html>
