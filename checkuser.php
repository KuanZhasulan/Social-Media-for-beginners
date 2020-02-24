<?php 
  require_once 'origin.php';

  if (isset($_POST['user']))
  {
    $test   = clear_Text($_POST['user']);
    try
      {
        $stmt = $chat->prepare("SELECT * FROM chat_members WHERE user = :user");
        $stmt->execute(array(
        ':user' => $test
    ));
        $row = $stmt->fetch();
        if(isset($row['user']))
        {
      echo  "<span class='taken'>&nbsp;&#x2718; " .
            "This username is taken</span>";
          }
    else{
      echo "<span class='available'>&nbsp;&#x2714; " .
           "This username is available</span>";
    }
  }
  catch(PDOException $e)
  {
    $e->getMessage();
  }
  }

?>
