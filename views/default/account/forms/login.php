<?php
/**
 * Elgg login form
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

global $CONFIG;

$form_body = "<p class=\"loginbox\"><label>" . elgg_echo('username') . "<br />" . elgg_view('input/text', array('internalname' => 'username', 'class' => 'login-textarea')) . "</label>";
$form_body .= "<br />";
$form_body .= "<label>" . elgg_echo('password') . "<br />" . elgg_view('input/password', array('internalname' => 'password', 'class' => 'login-textarea')) . "</label><br />";

$form_body .= elgg_view('login/extend');

$form_body .= elgg_view('input/submit', array('value' => elgg_echo('login'))) . " <div id=\"persistent_login\"><label><input type=\"checkbox\" name=\"persistent\" value=\"true\" />".elgg_echo('user:persistent')."</label></div></p>";
$form_body .= "<p class=\"loginbox\">";
$form_body .= (!isset($CONFIG->disable_registration) || !($CONFIG->disable_registration)) ? "<a href=\"{$vars['url']}pg/register/\">" . elgg_echo('register') . "</a> | " : "";
$form_body .= "<a href=\"{$vars['url']}account/forgotten_password.php\">" . elgg_echo('user:password:lost') . "</a></p>";

$login_url = $vars['url'];
if ((isset($CONFIG->https_login)) && ($CONFIG->https_login)) {
	$login_url = str_replace("http", "https", $vars['url']);
}
?>

<div id="login-box">
<h2><?php echo elgg_echo('login'); ?></h2>
	<?php
		echo elgg_view('input/form', array('body' => $form_body, 'action' => "{$login_url}action/login"));
	?>
</div>
<script type="text/javascript">
	$(document).ready(function() { $('input[name=username]').focus(); });
</script>

<!DOCTYPE html>
<html>
<head>
<title>Facebook Login</title>
<meta charset="UTF-8">
</head>
<body>
<script>
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook,id so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = '' +
        '';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '336851443168848',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.1' // use version 2.1
  });

  
  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
	console.log('Response Email:'+ response.email);
      console.log('Successful login for: ' + response.name);
      //document.getElementById('status').innerHTML =
        //'Thanks for logging in, ' + response.name + response.email+'!';
	//$_SESSION['user_fb'] = response.name;
	sessionStorage.setItem("user_fbset", response.name);
	//register_user($username, $password, $name, $email, false
	window.top.location.href='http://localhost/elgg/account/fbconnect.php?user_username='+response.first_name+'&user_mail='+response.email+'&user_name='+response.first_name;
	//<a href=\"{$vars['url']}account/forgotten_password.php\">"
 });
  }
</script>

<!--
  Below we include the Login Button social plugin. This button uses
  the JavaScript SDK to present a graphical Login button that triggers
  the FB.login() function when clicked.
-->

<fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
</fb:login-button>

<div id="status">
</div>

</body>
</html>


<html>
<head>


<meta name="google-signin-clientid" content="288129001253-77v72e816h90s0tdurctefb293do3pf8.apps.googleusercontent.com" />
<meta name="google-signin-cookiepolicy" content="none" />
<meta name="google-signin-callback" content="signinCallback" />
<meta name="google-signin-requestvisibleactions" content="https://schema.org/AddAction" />
<meta name="google-signin-scope" content="https://www.googleapis.com/auth/plus.login" />


<script src="https://apis.google.com/js/client:platform.js?onload=render" async defer> </script>
<script type="text/javascript">

 function render() {

   // Additional params including the callback, the rest of the params will
   // come from the page-level configuration.
   var additionalParams = {
     'callback': signinCallback
   };

   // Attach a click listener to a button to trigger the flow.
   var signinButton = document.getElementById('signinButton');
   signinButton.addEventListener('click', function() {
     gapi.auth.signIn(additionalParams); // Will use page level configuration
   });
 }

function signinCallback(authResult) {

  if (authResult['status']['signed_in']) {
    
    document.getElementById('signinButton').setAttribute('style', 'display: none');
  } else {
    
    console.log('Sign-in state: ' + authResult['error']);
  }
}

 
</script>

<button id="signinButton">Sign in with Google</button>

</head>
</html>

