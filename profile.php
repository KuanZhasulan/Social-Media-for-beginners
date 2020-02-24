<?php 
  require_once 'origin.php';

  if (!$loggedin) header('Location: index.php');

  echo "<div class='main'><h3>Your Profile</h3>";
   try{
     $stmt = $chat->prepare("SELECT * FROM chat_profiles WHERE user = :user");
     $stmt->execute(array(
       ':user' =>$user
      ));
      $row = $stmt->fetch(); 
   }
   catch(PDOException $e)
  {
       $e->getMessage();
  }
  
    
  if (isset($_POST['content']))
  {
    $text = clearText($_POST['content']);
    $text = preg_replace('/\s\s+/', ' ', $text);
    $fName = clearText($_POST['name']);
    $fName = preg_replace('/\s\s+/', ' ', $fName);
    $sName = clearText($_POST['surname']);
    $sName = preg_replace('/\s\s+/', ' ', $sName);
    $age = clearText($_POST['age']);
    $age = preg_replace('/\s\s+/', ' ', $age);
    $occupation = clearText($_POST['occupation']);
    $occupation = preg_replace('/\s\s+/', ' ', $age);
    

    if (isset($row['user']))
    {
    try {
         $stmt2 = $chat->prepare("UPDATE chat_profiles SET content = :content, name = :fname, surname = :sname, age = :age, occupation = :occu  WHERE user= :user");
         $stmt2->execute(array(
              ':text' => $text,
              ':user' => $user,
              ':fname' => $fName,
              ':sName' => $sName,
              ':age' => $age,
              ':occu' => $occupation
          ));  
        } catch (PDOException $e) {
          $e->getMessage();
        }    
    }
    else {
    try {
         $stmt2 = $chat->prepare("INSERT INTO chat_profiles(content, fname, sname, age, user, occupation) VALUES(:content, :fname, :sname, :age, :user, :occu)");
         $stmt2->execute(array(
              ':content' => $text,
              ':user' => $user,
              ':fname' => $fName,
              ':sname' => $sName,
              ':age' => $age,
              ':occu' => $occupation
          ));  
        } catch (PDOException $e) {
          $e->getMessage();
        } 
    }
  }
  elseif(isset($row['user']))
    {
    $text = $row['content'];
    $fName = $row['fname'];
    $sName = $row['sname'];
    $age = $row['age'];
    $occupation = $row['occupation']
   }
   else
   {
    $text= $fName= $sName = $occupation = $age = 0;
   }
  

  

  $text = stripslashes(preg_replace('/\s\s+/', ' ', $text));

  if (isset($_FILES['image']['name']))
  {
    $saveto = "$user.jpg";
    move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
    $typeok = TRUE;

    switch($_FILES['image']['type'])
    {
      case "image/gif":   $src = imagecreatefromgif($saveto); break;
      case "image/jpeg":  
      case "image/pjpeg": $src = imagecreatefromjpeg($saveto); break;
      case "image/png":   $src = imagecreatefrompng($saveto); break;
      default:            $typeok = FALSE; break;
    }

    if ($typeok)
    {
      list($w, $h) = getimagesize($saveto);

      $max = 100;
      $tw  = $w;
      $th  = $h;

      if ($w > $h && $max < $w)
      {
        $th = $max / $w * $h;
        $tw = $max;
      }
      elseif ($h > $w && $max < $h)
      {
        $tw = $max / $h * $w;
        $th = $max;
      }
      elseif ($max < $w)
      {
        $tw = $th = $max;
      }

      $tmp = imagecreatetruecolor($tw, $th);
      imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
      imageconvolution($tmp, array(array(-1, -1, -1),
        array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
      imagejpeg($tmp, $saveto);
      imagedestroy($tmp);
      imagedestroy($src);
    }
  }

  echo "<h1>PROFILE</h1>";
   if (file_exists("$user.jpg"))
    {
      echo "<img src='$user.jpg' style='float:left;'>";
      }
     echo '<table>';
       echo '<tr>';
        echo '<th>First Name</th>';
        echo '<th>Second Name</th>';
        echo '<th>Age</th>';
        echo '<th>Occupation</th>'; 
        echo '</tr>';
if (isset($row['user']))
    {
     
       echo '<tr>';
        echo "<td>".$row['name']."</td>";
        echo "<td>".$row['surname']."</td>";
        echo "<td>".$row['age']."</td>";
        echo "<td>".$row['occupation']."</td>";
       echo '</tr>'; 
       echo "<h2>Description</h2>";
      echo stripslashes($row['content']) . "<br style='clear:left;'><br>";
       echo "<h2>Description</h2>";      
      echo "</table>";
    }
    echo "<a class='button' href='chat_messages.php?view=$view'>" .
         "View $name messages</a><br><br>";
  
    

  echo <<<_END
    <form method='post' action='profile.php' enctype='multipart/form-data'>
    <h3>Enter or edit your details and/or upload an image</h3>
    <textarea name='content' cols='50' rows='3'>$text</textarea><br>
    First Name : <input type = 'text' name= 'fname' value ='$fName'><br>
    Second Name : <input type = 'text' name= 'sname' value = '$sName'><br>
    OCcupation: <input type = 'text' name= 'occupation' value = '$occupation'><br>
    Age: <input type = 'text' name= 'age' value = '$age'><br>
_END;
?>

    Image: <input type='file' name='image' size='14'>
    <input type='submit' value='Save' name="submit">  
    </form></div><br>
  </body>
</html>