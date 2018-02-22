<?php

//What the fucks

$user_val='entry';
$for_status='entry';
$email_val='entry';
$token_val='entry';
$password_val1='entry';
// Including the database function
include './resources/includes/databasefunc.inc';



//Need to check if the user is already logged in
$login_form = is_logged();

//Redirect them to index.php  if they are logged in
if ($login_form ){

	echo '<h1>You are logged in</h1>';
	echo '<p>You will be redirected to the main page in 5 seconds or you can click <a href="index.php">here.</a></p>';
	header( "refresh:5;url=index.php" );

}



if ( isset($_POST['for_username'])){
	$for_status='entry_username';
}

if ( isset($_POST['for_pass'])){
	$for_status='entry_password';
}


if ( isset($_POST['for_username_next'])){

	if (filter_var(($_POST['email']), FILTER_VALIDATE_EMAIL)) {

		$emailDB=sanitize_input($_POST['email']);
		$row = pg_num_rows(optic_query($db,"SELECT username FROM usertable WHERE email='".$emailDB."';"));

		/* If valid check if it exists in database */
		if ($row==1){
			$row = pg_fetch_row(optic_query($db,"SELECT username FROM usertable WHERE email='".$emailDB."';"));
			$usernameDB=$row[0];
			/* Send mail */
			$message = "Your username is: $usernameDB
			\r\nThis request was from this address:".$_SERVER['REMOTE_ADDR'].
			"\r\n\nIf you did not request this, please ignore this email.";
			$to= $emailDB;
			$subject = 'Forgotten Username';
			$headers = 'From: noreply@ntarki.com' . "\r\n" .
			'Reply-To: noreply@ntarki.com' . "\r\n";
			mail($to, $subject, $message);

		/*redirect */

			$for_status='done';
			echo '<h1>Username send</h1>';
			echo '<p>Your username has been send to your email.</p>';
			echo '<p>Check the spam folder in case it is there.</p>';
			echo '<p>You will be redirected to the login page in 30 seconds or you can click <a href="login.php">here.</a></p>';

			header( "refresh:30;url=login.php" );

		}
		else {
			$email_val ='error';
			$for_status='entry_username';
		}
	}
}


if ( isset($_POST['for_pass_next'])){

	if (filter_var(($_POST['email_pas']), FILTER_VALIDATE_EMAIL)) {

		$emailDB=sanitize_input($_POST['email_pas']);
		$row = pg_num_rows(optic_query($db,"SELECT username FROM usertable WHERE email='".$emailDB."';"));

		/* If valid check if it exists in database */
		if ($row==1){
			$email_val ='valid';

		}
		else {
			$email_val ='error';
			$for_status='entry_password';
		}
	}

	if (preg_match("/^[a-zA-Z0-9_]{1,26}$/",  sanitize_input($_POST['username']), $username)==1) {

		$usernameDB=sanitize_input($_POST['username']);
		$row = pg_num_rows(optic_query($db,"SELECT username FROM usertable WHERE username='".$usernameDB."';"));

		/* If valid check if it exists in database */
		if ($row==1){
			$user_val ='valid';

		}
		else {
			$user_val ='error';
			$for_status='entry_password';
		}


	}


    if (($user_val==='valid') && ($email_val ==='valid')){

		$forgot_token=getToken(128);

		optic_query($db,"INSERT INTO forgotreq(username,token,used,ip) VALUES ('".$usernameDB."','".$forgot_token."', 0 ,'".htmlentities($_SERVER['REMOTE_ADDR'])."');");


		$message = "Someone (hopefully you) requested a password reset for www.ntarki.com account.
		\r\n\nTo reset your password please enter the following 128 digit token:
		\r\n$forgot_token
		\r\n\nThis token will be active for 10 minutes.
		\r\n\nThis request was from this address:".$_SERVER['REMOTE_ADDR']."
		\r\nIf you did not request this ignore this email.";
		$to= $emailDB;
		$subject = 'Forgotten Password';
		$headers = 'From: noreply@ntarki.com' . "\r\n" .
		'Reply-To: noreply@ntarki.com' . "\r\n";
		mail($to, $subject, $message);


		$for_status='entry_password_ver';

	}
	else {


		$for_status='entry_password';

	}


}













if ( isset($_POST['pass_final'])){

	/*Check if the token exists*/

	if (preg_match("/^[a-zA-Z0-9]{128,128}$/",  sanitize_input($_POST['tokenID']), $tokenID)==1) {

		$tokenDB=sanitize_input($_POST['tokenID']);
		$row = pg_num_rows(optic_query($db,"SELECT username FROM forgotreq WHERE token='".$tokenDB."' AND (current_timestamp < (time_created + interval '10 minutes')) AND used=0;"));

		/* If valid check if it exists in database */
		if ($row==1){
			$token_val ='valid';

		}
		else {
			$token_val ='error';
			$for_status='entry_password_ver';
		}
	}
	else {
		$token_val ='error';
		$for_status='entry_password_ver';
	}



	/* Check password length */
	if ((strlen($_POST['password'])>=6) && (strlen($_POST['password'])<=72 )) {
		$password_val1 = 'valid';
	}
	else {
		$for_status='entry_password_ver';
		$password_val1 = 'error';
	}


	if (($password_val1==='valid') && ($token_val ==='valid')){

		/* Hash password */
		$passDB=password_hash($_POST['password'],PASSWORD_BCRYPT,["cost" => 12]);
		$row = pg_fetch_row(optic_query($db,"SELECT username FROM forgotreq WHERE token='".$tokenDB."' AND (current_timestamp < (time_created + interval '10 minutes')) AND used=0;"));
		$usernameDB=$row[0];
		/* Store to database  status + IP */
		optic_query($db,"UPDATE usertable SET (password_enc)=('".$passDB."') WHERE username='".$usernameDB."';");
		optic_query($db,"UPDATE forgotreq SET (used)=(1) WHERE token='".$tokenDB."';");
		// Redirect to confirmation
		$for_status='done';


		echo '<h1>Password Changed Successfully</h1>';
		echo '<p>You will be redirected to the login page in 30 seconds or you can click <a href="login.php">here.</a></p>';

		header( "refresh:30;url=login.php" );


	}

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

<?php if ($for_status==='entry' || $for_status==='entry_username' || $for_status==='entry_password' || $for_status==='entry_password_ver' ) { ?>
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

				<li class="pure-menu-item pure-menu-disabled menu-item-divided">Account</li>
				<li class="pure-menu-item pure-menu-selected"><a href="login.php" class="pure-menu-link">Login</a></li>
				<li class="pure-menu-item"><a href="registration.php" class="pure-menu-link">Register</a></li>

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
        </div>

        <div class="content">

<br/>
<?php if ($for_status==='entry') { ?>
<form class="pure-form pure-form-aligned" name ="forgot" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
<fieldset>

<div class="pure-control-group">

    <button type="submit"  name="for_username" id="for_username" class="pure-button pure-button-primary">Forgot Username</button>
    <br/><br/>
	<button type="submit"  name="for_pass" id="for_pass" class="pure-button pure-button-primary">Forgot Password</button>

</div>
</fieldset>
</form>

<?php }?>




<?php if ($for_status==='entry_username') { ?>
<form class="pure-form pure-form-aligned" name ="forgot" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
<fieldset>
	<?php if ($email_val === 'error' ){ #Making the text red in case of error?>
	<p><span class="warning">Enter the email used in registration</span></p>
	<?php } ?>

	<div class="pure-control-group">
	<label for="email">Email Address:</label>

	<input type="text" name="email" id="email" required="required" placeholder="name@domain.com"/>

</div>

<div class="pure-controls">

    <button type="submit"  name="for_username_next" id="for_username_next" class="pure-button pure-button-primary">Send Username</button>

</div>
</fieldset>
</form>
<?php }?>







<?php if ($for_status==='entry_password') { ?>

<form class="pure-form pure-form-aligned" name ="forgot" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
<fieldset>
<div class="pure-control-group">
	<?php if ($email_val === 'error' || $user_val === 'error'  ){ #Making the text red in case of error?>
	<p><span class="warning">Email and username do not match or do not exist in our database.</span></p>
	<?php } ?>

	<label for="email_pas">	Email Address:</label>

	<input type="email" name="email_pas" id="email_pas" required="required" placeholder="name@domain.com"
	<?php if ($email_val === 'error' || $user_val === 'error'  ){ echo 'value="'.$_POST['email_pas'].'"';	} #If there is an error restore input ?>/>

</div>

<div class="pure-control-group">
	<label for="username"> Username:</label>

	<input  type="text"  name="username" id="username" pattern="[a-zA-Z0-9_]{1,26}" required="required" placeholder="Enter your username" <?php  if ($email_val === 'error' || $user_val === 'error'  ){ echo 'value="'.$_POST['username'].'"';	} #If there is an error restore input ?>/>

</div>

<div class="pure-controls">
    <button type="submit"  name="for_pass_next" id="for_pass_next" class="pure-button pure-button-primary">Send Password <br/>Reset Token</button>
</div>
</fieldset>
</form>
<?php }?>








<?php if ($for_status==='entry_password_ver') { ?>

<form class="pure-form pure-form-aligned" name ="forgot" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
<fieldset>
	<p><span class="warning">Do NOT close this page.</span></p>
	<p></p>
	<p><span class="warning">An email has been sent with a recovery token. It lasts 10 minutes.</span></p>
	<p><span class="warning">Use this token here to reset your password.</span></p>

	<?php if ($token_val==='error' || $password_val1==='error' ) { ?>
	<p><span class="warning">Incorrect token or the password does not match the minimum requirements.</span></p>
	<?php } ?>



<div class="pure-control-group">

	<label for="tokenID">Token:</label>

 	<input type="text" name="tokenID" id="tokenID" title="Enter the token that was provided."  required="required" />

</div>

<div class="pure-control-group">

	<label for="password">Password</label>

 	<input type="password" name="password" id="password" title="Password minimum length 6 characters up to 72. Try using a strong password."  onchange="form.password_ver.pattern = this.value;"  pattern=".{6,}" required="required" />

</div>


<div class="pure-control-group">

	<label for="password1"> Confirm Password:</label>

	<input title="Confirm the password." type="password" name="password_ver" id="password_ver"  />

</div>

<div class="pure-controls">

    <button type="submit"  name="pass_final" id="pass_final" class="pure-button pure-button-primary">Reset Password</button>




</div>
</fieldset>
</form>


        </div>
    </div>
</div>


<?php }?>

<?php }?>

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
