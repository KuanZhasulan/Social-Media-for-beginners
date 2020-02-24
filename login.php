<?php 
  require_once 'origin.php';
  echo "<div class='main'><h3>Please enter your details to log in</h3>";
  $error = $user = $pass = "";

  if (isset($_POST['user']))
  {
    $user = clear_Text($_POST['user']);
    $pass = clear_Text($_POST['pass']);
    
    if ($user == "" || $pass == "")
        $error = "Not all fields were entered<br>";
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
        header("Location: chat_messages.php");
      }
      else
      {
          $error = "<span class='error'>Username/Password
                  invalid</span><br><br>";
      } 
    
    }
    catch(PDOException $e)
    {
           $e->getMessage();
    }
    }
  }

  echo <<<_END
    <form method='post' action='login.php'>$error
    <span class='fieldname'>Username</span><input type='text'
      maxlength='16' name='user' value='$user'><br>
    <span class='fieldname'>Password</span><input type='password'
      maxlength='16' name='pass' value='$pass'>
_END;
?>

    <br>
    <span class='fieldname'>&nbsp;</span>
    <input type='submit' value='Login'>
    </form><br></div>
  </body>
</html>