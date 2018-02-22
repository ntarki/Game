<?php

// Initializing values in order to avoid errors
$username_val = 'entry';
$email_val = 'entry';
$cb_val = 'entry';
$password_val1 = 'entry';
$form_error=false;
$reg_status=false;

// Including the database function
include './resources/includes/databasefunc.inc';



//Need to check if the user is already logged in 
$login_form = is_logged();

//Redirect them to index.php  if they are logged in
if ($login_form){
	
	echo '<h1>You are logged in</h1>';
	echo '<p>You will be redirected to the main page in 5 seconds or you can click <a href="index.php">here.</a></p>';		
	header( "refresh:5;url=index.php" );
	
}






// When the submit is pressed  
if ( isset($_POST['submit'])) {

	/* Check username validity */
	if (preg_match("/^[a-zA-Z0-9_]{1,26}$/",  sanitize_input($_POST['username']), $username)==1) {

		$usernameDB=sanitize_input($_POST['username']);
		$row = pg_num_rows(optic_query($db,"SELECT username FROM usertable WHERE username='".$usernameDB."';"));

		/* If valid check if it exists in database */	
		if ($row==0){
			$username_val = 'valid';
		}		
		if ($row>0){
			$username_val = 'error_exists';
		}			
	}
	else {
		$username_val = 'error_typo';
	}

	
	/* Check password length */
	if ((strlen($_POST['password'])>=6) && (strlen($_POST['password'])<=72 )) {
		$password_val1 = 'valid';
	}
	else {
		$password_val1 = 'error';
	}
	
	
	/* Check email validity */
	if (filter_var(($_POST['email']), FILTER_VALIDATE_EMAIL)) {

		$emailDB=sanitize_input($_POST['email']);
		$row = pg_num_rows(optic_query($db,"SELECT email FROM usertable WHERE email='".$emailDB."';"));

		/* If valid check if it exists in database */
		if ($row==0){
			$email_val = 'valid';
		}		
		if ($row>0){
			$email_val = 'error_exists';
		}	
	}
	else {
		$email_val='error_typo';
	}



	
	
	/* Check checkbox for terms */	
	if (  isset($_POST['cb']) && $_POST['cb']==='on') {	
		$cb_val = 'valid';
	}
	else {	
		$cb_val = 'error';
	}

	
	
	/* Check if there is an error in the form */		
	if (($username_val === 'error_exists') || ($username_val === 'error_typo') || ($password_val1 === 'error') || ($email_val === 'error_exists') || ($email_val === 'error_typo') || ($cb_val === 'error' ) ) {
		$form_error=true;
	}
	
	/* Register the user */
    elseif (($username_val === 'valid') && ($password_val1 === 'valid') && ($email_val === 'valid') && ($cb_val === 'valid')  ) {
	
		/* Hash password */	
		$passDB=password_hash($_POST['password'],PASSWORD_BCRYPT,["cost" => 12]);

		/* Create activation key */	
		$activationDB=uniqid($more_entropy=true);
	
		/* Send mail */
		$message = "Welcome to ntarki.com
		\r\n\nPlease keep this e-mail for your records. 
		\r\nYour account information is as follows:
		\r\n\nUsername: $usernameDB
		\r\n\nPlease visit the following link in order to activate your account:
		\r\nVisit www.ntarki.com/activation.php?username=$usernameDB&activationID=$activationDB&submit=
		\r\nOr go to www.ntarki.com/activation.php to activate your account using the activation key: $activationDB
		\r\n\nYour password has been securely stored in our database and cannot be retrieved. 
		\r\nIn the event that it is forgotten, you will be able to reset it using the email address associated with your account.
		\r\n\nThank you for registering.";
		$to= $emailDB;
		$subject = 'Activation for ntarki.com';
		$headers = 'From: noreply@ntarki.com' . "\r\n" .
		'Reply-To: noreply@ntarki.com' . "\r\n";
		mail($to, $subject, $message); 
	
		/* Store to database  status + IP */	
		optic_query($db,"INSERT INTO usertable(username,email,password_enc,activation_key,ip_registered) VALUES ('".$usernameDB."','".$emailDB."','".$passDB."','".$activationDB."','".htmlentities($_SERVER['REMOTE_ADDR'])."');");
			

		
		
		
		// Redirect to confirmation		
		$reg_status=true;
		

		echo '<h1>Registration Success</h1>';
		echo '<p>The activation code has been send to your email.</p>';
		echo '<p>Check the spam folder in case it is there.</p>';
		echo '<p>You will be redirected to the activation page in 30 seconds or you can click <a href="activation.php">here.</a></p>';		

		header( "refresh:30;url=activation.php" );

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





<?php if (!$reg_status) { ?>
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
				<li class="pure-menu-item "><a href="login.php" class="pure-menu-link">Login</a></li>
				<li class="pure-menu-item pure-menu-selected"><a href="#" class="pure-menu-link">Register</a></li>
				
				<li class="pure-menu-item pure-menu-disabled menu-item-divided">News</li>
				<li class="pure-menu-item"><a href="#" class="pure-menu-link">Blog</a></li>
				<li class="pure-menu-item"><a href="#" class="pure-menu-link">Patch Notes</a></li>
				
				<li class="pure-menu-item pure-menu-disabled menu-item-divided">Other</li>
				<li class="pure-menu-item"><a href="#" class="pure-menu-link">Contact Us</a></li>
				<li class="pure-menu-item"><a href="#" class="pure-menu-link">About</a></li>

            </ul>
        </div>
    </div>

    <div id="main">
        <div class="header">
            <h1>Registration Form</h1>
            <h2>Create an account to enter the brawl!</h2>
        </div>

        <div class="content">
		
		
		



<form class="pure-form pure-form-aligned" name ="registration" method="post" >
<br/>
<fieldset>	

	<?php if ($form_error ){?>
	<span class="warning">Please fix the indicated field(s).</span><br/><br/>
	<?php }?>
		
<div class="pure-control-group">
	
	<label for="username">	
	<?php if ($username_val === 'error_typo' || $username_val === 'error_exists'){ #Making the text red in case of error ?>
	<span class="warning">Username:</span>
	<?php } else {?> Username:<?php } ?> 
	</label>		
	
	<input  type="text"  name="username" id="username" pattern="[a-zA-Z0-9_]{1,26}" required="required" title="Username length between 1-26 characters. Valid characters are English letters,numbers and the _" placeholder="Enter your username"
	<?php  if ($form_error){ echo 'value="'.$_POST['username'].'"';	} #If there is an error restore input ?>/>

	<?php if ($username_val === 'error_exists'){ #Extra error if the username is taken ?>
    <span class="warning">&nbsp;&nbsp;Username already exists</span>
	<?php } ?> 

	</div>   

<div class="pure-control-group">  

 	<label for="email">	
	<?php if ($email_val === 'error' || $email_val === 'error_exists'){ #Making the text red in case of error?>
	<span class="warning">Email Address:</span>
	<?php } else {?>Email Address:<?php } ?>
	</label>	

	<input type="email" name="email" id="email" required="required" placeholder="name@domain.com" title="Enter your email address" 
	<?php if ($form_error){ echo 'value="'.$_POST['email'].'"';	} #If there is an error restore input ?>/>		

	<?php if ($email_val === 'error_exists'){ #Extra error if the username is taken ?>
    <span class="warning">&nbsp;&nbsp;Email already registered</span>
	<?php } ?> 

	</div>

<div class="pure-control-group">  
	
	<label for="password">		
	<?php if ($password_val1 === 'error'){ #Making the text red in case of error?>
	<span class="warning">Password:</span>
	<?php } else {?>Password:<?php } ?>	</label>	

 	<input type="password" name="password" id="password" title="Password minimum length 6 characters up to 72. Try using a strong password."  onchange="form.password_ver.pattern = this.value;"  pattern=".{6,}" required="required" 
	<?php if ($form_error){ echo 'value="'.$_POST['password'].'"';	} #If there is an error restore input ?> />	

</div>  


<div class="pure-control-group">  
 	
	<label for="password1">
	<?php if ($password_val1 === 'error'){  #Making the text red in case of error?>
	<span class="warning"> Confirm Password:</span>
	<?php } else {?>Confirm Password:<?php } ?>
	</label>
	
	<input title="Confirm the password." type="password" name="password_ver" id="password_ver" 
	
	<?php if ($form_error){ echo 'value="'.$_POST['password'].'"';}#If there is an error restore input	?> />		

</div>


<div class="pure-controls">

    <label for="cb" class="pure-checkbox">
    <input id="cb" name="cb" type="checkbox" required="required"
	<?php if (isset($_POST['cb']) && ($_POST['cb']==='on')){ echo "checked='checked'";}#If there is an error restore input ?>/>   
	<?php if ($cb_val === 'error'){ # Make the text red ?>  
	<span class="warning">I accept the terms and conditions.</span>
	<?php } else {?>I accept the terms and conditions.<?php } ?>
	</label>
	
    <br/>
	
    <button type="submit"  name="submit" id="submit" class="pure-button pure-button-primary">Register</button>

</div>
</fieldset>	
</form>








  
        </div>
    </div>
</div>


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