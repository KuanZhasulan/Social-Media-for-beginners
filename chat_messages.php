<?php   
require_once 'origin.php';

  if (!$loggedin) header("Location: index.php");

  if (isset($_GET['view'])) $view = clear_Text($_GET['view']);
  else                      $view = $user;

  if (isset($_POST['text']))
  {
    $text = clear_Text($_POST['text']);

    if ($text != "")
    {
      $pm   = substr(clear_Text($_POST['pm']),0,1);
      $time = time();
       $stmt2 = $chat->prepare("INSERT INTO chat_messages(auth, recip, pm, mDate, message) VALUES(:auth, :recip, :pm, :mDate, :message)");
         $stmt2->execute(array(
              'auth' => $user,
              'recip' => $view,
              'pm' => $pm,
              'mDate' => $time,
              'message' => $text
          )); 
    
    }
  }

  if ($view != "")
  {
    if ($view == $user) $name = $name1 = $name2 = "Your";
    else
    {
      $name1 = "<a href='chat_members.php?view=$view'>$view</a>'s";
      $name2 = "$view's";
      $name = "$view's";
    }

   
  
      try{
     $stmt = $chat->prepare("SELECT * FROM chat_profiles WHERE user = :user");
     $stmt->execute(array(
       ':user' =>$view
      )); 
   }
   catch(PDOException $e)
   {
    echo $e->getMessage();
   }
    $rowM = $stmt->fetch(); 
if (isset($rowM['user']))
    {
    
    
   
    }

    if (isset($_GET['erase']))
    {
      $erase = clear_Text($_GET['erase']);
       $stmt3 = $chat->prepare("DELETE FROM chat_messages WHERE messageID = :erase AND recip = :recip ");
         $stmt3->execute(array(
              'recip' => $user,
              'erase' => $erase
          )); 
      
    }
    
     $stmt4 = $chat->prepare("SELECT * FROM chat_messages WHERE recip = :recip ORDER BY mDate DESC");
         $stmt4->execute(array(
              'recip' => $view
          )); 
      $firstRow = $stmt4->fetchAll(PDO::FETCH_ASSOC);
      $stmtN = $chat->prepare("SELECT count(*) FROM chat_messages WHERE recip = :recip ORDER BY mDate DESC");
         $stmtN->execute(array(
              'recip' => $view
          ));
      $num = $stmtN->fetchColumn();   
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
          if (file_exists("$view.jpg"))
        {
          echo "<img src='$view.jpg' class='user-image img-responsive'>";
        }
        else
        {
          echo '<img src="assets/img/find_user.png" class="user-image img-responsive"/>';
        }

          ?>
                    
          </li>
        
          
                    <li>
                        <a  href="chat_members.php?view=<?php echo $user; ?>"><i class="fa fa-dashboard fa-3x"></i>Personal Page</a>
                    </li>
                    <li>
                        <a  href="chat_members.php"><i class="fa fa-dashboard fa-3x"></i>Members</a>
                    </li>
                     <li>
                        <a  href="chat_friends.php"><i class="fa fa-desktop fa-3x"></i> Friends</a>
                    </li>
                    <li>
                        <a  class="active-menu"  href="tab-panel.html"><i class="fa fa-qrcode fa-3x"></i> Messages</a>
                    </li>
               <li  >
                        <a   href="chat_profile.php"><i class="fa fa-bar-chart-o fa-3x"></i> Edit Profile</a>
                    </li> 
                   
                </ul>
               
            </div>
            
        </nav>  

        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h2><?php  echo "<div class='main'><h3>$name1 Messages</h3>"; ?></h2>   
                        <h5>Welcome <?php echo $_SESSION['name']." ".$_SESSION['surname']; ?> , Love to see you back. <?php 
                        if($num == 0)
  { 
    echo "<span class='info'>No messages yet</span>";
  } 
  ?> </h5>
                    </div>
                </div>

                 <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                   
                    <form class="chat-panel panel panel-default chat-boder chat-panel-head" method='post' action='chat_messages.php?view=<?php echo $view; ?>'>
                        <div class="panel-heading">
                            <i class="fa fa-comments fa-fw"></i>
                            Chat Box
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu slidedown">
                                    <li>
                                        <a href="chat_messages.php?view=<?php echo $view; ?>">
                                            <i class="fa fa-refresh fa-fw"></i>Refresh
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-check-circle fa-fw"></i>Available
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-times fa-fw"></i>Busy
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-clock-o fa-fw"></i>Away
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-sign-out fa-fw"></i>Sign Out
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="panel-body">
                            <ul class="chat-box">
                              <?php
                          if ($view != ""){
                              foreach($firstRow as $row)
     { 
    
      if ($row['pm'] == 0 || $row['auth'] == $user || $row['recip'] == $user)
      {
        if ($row['pm'] == 0){
          $side = "left";
          $pull = "pull-left"; 
        } 
        else
        {
          $side = "right";
          $pull = "pull-right"; 
        }
        echo "<li class='".$side." clearfix'>";
        echo '<span class="chat-img '.$pull.'">';
            if (file_exists($row['auth'].".jpg"))
            {
              echo "<img src='".$row['auth'].".jpg' class='img-circle'>";
            }
            else
            {
              echo '<img src="assets/img/1.png" class="img-circle"/>';
            }
        echo '</span>';
        echo '<div class="chat-body">';                                        
        echo "<strong ><a href='chat_messages.php?view=" . $row['auth'] . "'>" . $row['auth']. "</a></strong>";
        echo '<small class="pull-right text-muted">';
        echo '<i class="fa fa-clock-o fa-fw"></i>'.date('M jS \'y g:ia:', $row['mDate']).'</small>';                     
        echo "<p>";
        echo  $row['message'];
        if ($row['recip'] == $user)
        {
          echo "<br><a class='btn btn-danger btn-sm' href='chat_messages.php?view=$view" .
               "&erase=" . $row['messageID'] . "'>erase</a>";
        }
        echo "</p>";
        echo "</div>";
        echo "</li>";

    
      }
    }
}
                              ?>

                               
                            </ul>
                        </div>


                        <div class="panel-footer">
                            <div class="form-group">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name='pm' value='0' id="optionsRadios1" checked />Public
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name='pm' value='1' id="optionsRadios2" />Private
                                                </label>
                                            </div>
                              </div>

                            <div class="input-group">
                                
                                <input name='text' id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message to send..." />
                                <span class="input-group-btn">
                                    <button class="btn btn-warning btn-sm" id="btn-chat">
                                        Send
                                    </button>
                                </span>
                            </div>
                        </div>

                    </form>
                    
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