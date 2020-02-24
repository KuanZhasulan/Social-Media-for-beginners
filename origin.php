<?php 
ob_start();
session_start();

define('DBHOST', "localhost");
define('DBUSER', "root");
define('DBPASS', "");
define('DBNAME', "yernarchat");
$site_name = "Yernar's CHat";

$chat = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
$chat->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

date_default_timezone_set("Asia/Aqtobe");



  $userstr = ' (Visitor)';

  if (isset($_SESSION['user']))
  {
    $user     = $_SESSION['user'];
    $loggedin = TRUE;
    $userstr  = " ($user)";
  }
  else $loggedin = FALSE;


  /*
  if ($loggedin)
  {
    echo "<br ><ul class='menu'>" .
         "<li><a href='chat_members.php'>Members</a></li>"         .
         "<li><a href='chat_friends.php'>Friends</a></li>"         .
         "<li><a href='chat_messages.php'>Messages</a></li>"       .
         "<li><a href='chat_profile.php'>Edit Profile</a></li>"    .
         "<li><a href='logout.php'>Log out</a></li></ul><br>";
  }
  
  */


  function destroy_Session()
  {
    $_SESSION=array();

    if (session_id() != "" || isset($_COOKIE[session_name()]))
      setcookie(session_name(), '', time()-2592000, '/');

    session_destroy();
  }

  function clear_Text($var)
  {
   
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return $var;
  }
?>