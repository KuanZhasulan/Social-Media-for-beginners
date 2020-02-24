<?php 
  require_once 'origin.php';
  if($loggedin) header("Location: members.php?view=$user");
  echo <<<_END
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
  <div class='main'><h3>Please enter your details to sign up</h3>
_END;

  $error = $user = $pass = "";

  if (isset($_POST['user']))
  {
    $user = clear_Text($_POST['user']);
    $pass = clear_Text($_POST['pass']);

    if ($user == "" || $pass == "")
    {
      $error = "Not all fields were entered<br><br>";
    }
    else
    {
      try
      {
        $stmt = $chat->prepare("SELECT * FROM chat_members WHERE user = :user");
        $stmt->execute(array(
        ':user' => $user
    ));
        $row = $stmt->fetch();
        if(isset($row['memberID']))
        {
          $error = "That username already exists";
        }
        else
        {
         try {
           $stmt1 = $chat->prepare("INSERT INTO chat_members(user, pass) VALUES(:user, :pass)");
           $stmt1->execute(array(
            ':user' => $user,
            ':pass' => $pass
            ));
            $_SESSION['user'] = $user;
            $_SESSION['pass'] = $pass;
            header("Location: chat_members.php?view$user");             
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

  echo <<<_END
    <form method='post' action='signup.php'>$error
    <span class='fieldname'>Username</span>
    <input type='text' maxlength='16' name='user' value='$user'
      onBlur='checkUser(this)'><span id='info'></span><br>
    <span class='fieldname'>Password</span>
    <input type='text' maxlength='16' name='pass'
      value='$pass'><br>
_END;
?>

    <span class='fieldname'>&nbsp;</span>
    <input type='submit' value='Sign up'>
    </form></div><br>
  </body>
</html>