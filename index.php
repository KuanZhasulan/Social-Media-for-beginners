<?php
 require_once('origin.php');
 if($loggedin) header("Location: chat_members.php?view=$user");
 $error = $reg_sname = $reg_name = $reg_error = $reg_user = $reg_pass = "";

  if (isset($_POST['registr']))
  {
    $reg_user = clear_Text($_POST['reg']);
    $reg_pass = clear_Text($_POST['reg_pass']);
    $reg_name = clear_Text($_POST['reg_name']);
    $reg_sname = clear_Text($_POST['reg_sname']);

    if ($reg_user == "" || $reg_pass == "" || $reg_name == "" || $reg_sname == "")
    {
      $reg_error = "Not all fields were entered<br><br>";
    }
    else
    {
      try
      {
        $stmt = $chat->prepare("SELECT * FROM chat_members WHERE user = :user");
        $stmt->execute(array(
        ':user' => $reg_user
    ));
        $row = $stmt->fetch();
        if(isset($row['memberID']))
        {
          $reg_error = "That username already exists<br><br>";
        }
        else
        {
         try {
           $stmt1 = $chat->prepare("INSERT INTO chat_members(user, pass) VALUES(:user, :pass)");
           $stmt1->execute(array(
            ':user' => $reg_user,
            ':pass' => $reg_pass
            ));
           $stmt2 = $chat->prepare("INSERT INTO chat_profiles(user, name, surname) VALUES(:user, :name, :sname)");
           $stmt2->execute(array(
            ':user' => $reg_user,
            ':name' => $reg_name,
            ':sname' => $reg_sname
            ));
            $_SESSION['user'] = $reg_user;
            $_SESSION['pass'] = $reg_pass;
            $_SESSION['name'] = $reg_name;
            $_SESSION['surname'] = $reg_sname;
            header("Location: chat_members.php?view=$reg_user");             
          } catch (PDOException $e) {
            echo $e->getMessage();
          } 
        }
      }
      catch(PDOException $e)
      {
       echo $e->getMessage();
      }

      
    }
  }


    $err = $user = $pass = "";

  if (isset($_POST['login']))
  {
    $user = clear_Text($_POST['log_name']);
    $pass = clear_Text($_POST['log_pass']);
    
    if ($user == "" || $pass == "")
        $err = "Not all fields were entered<br>";
    else
    {
        try
      {
        $stmt = $chat->prepare("SELECT * FROM chat_members WHERE user = :user AND pass = :pass");
        $stmt->execute(array(
        ':user' => $user,
        ':pass' => $pass
    ));
        $row = $stmt->fetch();
        if(isset($row['memberID']))
      {
        $_SESSION['user'] = $user;
        $_SESSION['pass'] = $pass;
        $_SESSION['name'] = $row['name'];
        $_SESSION['surname'] = $row['surname'];
        header("Location: chat_members.php?view=$user");
      }
      else
      {
          $err = "<span class='error'>Username/Password
                  invalid</span><br><br>";
      } 
    
    }
    catch(PDOException $e)
    {
           $e->getMessage();
    }
    }
  }

?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>NIS CONNECTION</title>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900'>
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Montserrat:400,700'>
  <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
  <link rel="stylesheet" href="css/style.css">

  
</head>

<body>
  
<div class="container">
  <div class="info">
  <h1>Nis Connection</h1><span>Made with <i class="fa fa-heart"></i> by <a href="http://andytran.me">Ernar Salim</a></span>
  </div>
</div>
<div class="form">
  <div class="thumbnail"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/169963/hat.svg"/></div>
  
  <form class="register-form" method="post" action="index.php">
    <?php echo $reg_error; ?>
    <input type="text"  name="reg_name" placeholder="name"/>
    <input type="text" name="reg_sname"  placeholder="surname"/>
    <input type="password" name="reg_pass" placeholder="password"/>
    <input type="text" name="reg" placeholder="email address"/>
    <input type="hidden" name="registr" value="muahaha">
    <input type="submit" value="create" class="button">
    <p class="message">Already registered? <a href="#">Sign In</a></p>
  </form>


  <form class="login-form" method="post" action="index.php"> 
    <?php echo $reg_error; ?>
    <input type="text" name="log_name" placeholder="username"/>
    <input type="password"  name="log_pass"  placeholder="password"/>
    <input class="button" type="submit" value="Login">
    <input type="hidden" name="login">
    <p class="message">Not registered? <a href="#">Create an account</a></p>
  </form>
</div>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

  <script  src="js/index.js"></script>



<script>
    function checkUser(user)
    {
      if (user.value == '')
      {
        O('info').innerHTML = ''
        return
      }

      params  = "user=" + user.value
      request = new ajaxRequest()
      request.open("POST", "checkuser.php", true)
      request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
      request.setRequestHeader("Content-length", params.length)
      request.setRequestHeader("Connection", "close")

      request.onreadystatechange = function()
      {
        if (this.readyState == 4)
          if (this.status == 200)
            if (this.responseText != null)
              O('info').innerHTML = this.responseText
      }
      request.send(params)
    }

    function ajaxRequest()
    {
      try { var request = new XMLHttpRequest() }
      catch(e1) {
        try { request = new ActiveXObject("Msxml2.XMLHTTP") }
        catch(e2) {
          try { request = new ActiveXObject("Microsoft.XMLHTTP") }
          catch(e3) {
            request = false
      } } }
      return request
    }
  </script>

</body>
</html>