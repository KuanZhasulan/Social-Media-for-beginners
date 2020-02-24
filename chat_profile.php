<?php 
  require_once 'origin.php';

  if (!$loggedin) header('Location: index.php');

   try{
     $stmt = $chat->prepare("SELECT * FROM chat_profiles WHERE user = :user");
     $stmt->execute(array(
       ':user' => $user
      )); 
   }
   catch(PDOException $e)
  {
       $e->getMessage();
  }
  
  $row = $stmt->fetch();
    
  if (isset($_POST['content']))
  {
    $text = clear_Text($_POST['content']);
    $text = preg_replace('/\s\s+/', ' ', $text);
    $fName = clear_Text($_POST['name']);
    $fName = preg_replace('/\s\s+/', ' ', $fName);
    $sName = clear_Text($_POST['surname']);
    $sName = preg_replace('/\s\s+/', ' ', $sName);
    $age = clear_Text($_POST['age']);
    $age = preg_replace('/\s\s+/', ' ', $age);
    $occupation = clear_Text($_POST['occupation']);
    $occupation = preg_replace('/\s\s+/', ' ', $occupation);
    

    if (isset($row['user']))
    {
    try {
         $stmt2 = $chat->prepare("UPDATE chat_profiles SET content = :content, name = :fname, surname = :sname, age = :age, occupation = :occu  WHERE user= :user");
         $stmt2->execute(array(
              ':content' => $text,
              ':user' => $user,
              ':fname' => $fName,
              ':sname' => $sName,
              ':age' => $age,
              ':occu' => $occupation
          )); 
          header("location: chat_members.php?view=$user"); 
        } catch (PDOException $e) {
          $e->getMessage();
        }    
    }
    else {
    try {
         $stmt2 = $chat->prepare("INSERT INTO chat_profiles(content, name, surname, age, user, occupation) VALUES(:content, :fname, :sname, :age, :user, :occu)");
         $stmt2->execute(array(
              ':content' => $text,
              ':user' => $user,
              ':fname' => $fName,
              ':sname' => $sName,
              ':age' => $age,
              ':occu' => $occupation
          ));  
         header("location: chat_members.php?view=$user");
        } catch (PDOException $e) {
          $e->getMessage();
        } 
    }
  }
  elseif(isset($row['user']))
    {
    $text = $row['content'];
    $fName = $row['name'];
    $sName = $row['surname'];
    $age = $row['age'];
    $occupation = $row['occupation'];
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


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $_SESSION['name']." ".$_SESSION['surname']; ?></title>
  <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
     <!-- MORRIS CHART STYLES-->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' /> 
   <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
</head>
<body>

  <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html"><?php echo $_SESSION['name']." ".$_SESSION['surname']; ?></a> 
            </div>
  <div style="color: white;
padding: 15px 50px 5px 50px;
float: right;
font-size: 16px;"> Last access : 30 May 2014 &nbsp; <a href="logout.php" class="btn btn-danger square-btn-adjust">Logout</a> </div>
        </nav>   
           <!-- /. NAV TOP  -->
                <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
        <li class="text-center">
          <?php 
          if (file_exists("$user.jpg"))
        {
          echo "<img src='$user.jpg' class='user-image img-responsive'>";
        }
        else
        {
          echo '<img src="assets/img/find_user.png" class="user-image img-responsive"/>';
        }

          ?>
                    
          </li>
        
          
                    <li>
                        <a href="chat_members.php?view=<?php echo $user; ?>"><i class="fa fa-dashboard fa-3x"></i>Personal Page</a>
                    </li>
                    <li>
                        <a  href="chat_members.php"><i class="fa fa-dashboard fa-3x"></i>Members</a>
                    </li>
                     <li>
                        <a  href="chat_friends.php"><i class="fa fa-desktop fa-3x"></i> Friends</a>
                    </li>
                    <li>
                        <a  href="tab-panel.html"><i class="fa fa-qrcode fa-3x"></i> Messages</a>
                    </li>
               <li  >
                        <a class="active-menu"    href="chat_profile.php"><i class="fa fa-bar-chart-o fa-3x"></i> Edit Profile</a>
                    </li> 
                   
                </ul>
               
            </div>
            
        </nav>  

        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h2><?php if(isset($name)){ echo $name." Profile";} ?></h2>   
                        <h5>Welcome <?php echo $_SESSION['name']." ".$_SESSION['surname']; ?> , Love to see you back. </h5>
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />

                <div class="row">
                 <div class="col-md-12">

                  <div class="panel panel-default">
                        <div class="panel-heading">
                            Edit Profile
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <form action='chat_profile.php' enctype='multipart/form-data' method="post">
                                    <div class="form-group">
                                            <label>Name</label>
                                            <input name= 'name' class="form-control" placeholder="PLease Enter Your Name" />
                                        </div>
                                    <div class="form-group">
                                            <label>Surname</label>
                                            <input name= 'surname' class="form-control" placeholder="PLease Enter Your Surname" />
                                    </div>
                                    <div class="form-group">
                                            <label>Age</label>
                                            <input name= 'age' class="form-control" placeholder="PLease Enter Your Age" />
                                    </div>
                                    <div class="form-group">
                                            <label>Occupation</label>
                                            <input name="occupation" class="form-control" placeholder="PLease Enter Your Occupation" />
                                    </div>
                                    <div class="form-group">
                                            <label>About Me!</label>
                                            <textarea name="content" class="form-control" rows="3"></textarea>
                                    </div>  

                                    <div class="form-group">
                                            <label>Avatar</label>
                                            <input name='image' type="file" />
                                    </div>
                                    <div class="form-group">
                                            <input type='submit' value='Save' name="submit" class="btn btn-default"> 
                                    </div> 
                                    </form>    
                                </div>
                            </div>
                        </div>          
                  </div>
                 </div>
                </div>      
</div>
             <!-- /. PAGE INNER  -->
            </div>



    

    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
     <!-- DATA TABLE SCRIPTS -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
    </script>
         <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>

    </ul></div>
  </body>
</html>