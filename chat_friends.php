<?php 
  require_once 'origin.php';

  if (!$loggedin) header("Location: index.php");

  if (isset($_GET['view'])) $view = clear_Text($_GET['view']);
  else                      $view = $user;

  if ($view == $user)
  {
    $name1 = $name2 = "Your";
    $name3 =          "You are";
  }
  else
  {
    $name1 = "<a href='chat_members.php?view=$view'>$view</a>'s";
    $name2 = "$view's";
    $name3 = "$view is";
  }

  $followers = array();
  $following = array();
   
     $result = $chat->prepare("SELECT * FROM chat_friends WHERE user = :add");
    $result->execute(array('add' => $view)); 
    
$firstRow = $result->fetchAll(PDO::FETCH_ASSOC);  

  foreach($firstRow as $row)
  {
  
    $followers[] = $row['friend'];
  }
  
 $result = $chat->prepare("SELECT * FROM chat_friends WHERE friend = :add");
 $result->execute(array('add' => $view)); 
 $secondRow = $result->fetchAll(PDO::FETCH_ASSOC);  

  foreach($secondRow as $row)
  {
  
    $following[] = $row['user'];
  }


   $mutual    = array_intersect($followers, $following);
  $followers = array_diff($followers, $mutual);
  $following = array_diff($following, $mutual);
  $friends   = FALSE;

 
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
                        <a href="chat_members.php?view=<?php echo $user; ?>"><i class="fa fa-dashboard fa-3x"></i>Personal Page</a>
                    </li>
                    <li>
                        <a  href="chat_members.php"><i class="fa fa-dashboard fa-3x"></i>Members</a>
                    </li>
                     <li>
                        <a class="active-menu"   href="ui.html"><i class="fa fa-desktop fa-3x"></i> Friends</a>
                    </li>
                    <li>
                        <a  href="tab-panel.html"><i class="fa fa-qrcode fa-3x"></i> Messages</a>
                    </li>
               <li  >
                        <a   href="chart.html"><i class="fa fa-bar-chart-o fa-3x"></i> Edit Profile</a>
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

                <div class = "row">
                  <?php 

                   if (sizeof($mutual))
            {
              echo '<div class="col-md-4">';
              echo '<div class="panel panel-default">';
              echo "<div class='panel-heading'>$name2 mutual friends</div>"; 
              echo '<div class="panel-body">';
              echo '<div class="table-responsive">';
              echo '<table class="table table-hover">';
              echo '<thead>';
              echo '<tr>';
              echo '<th>#</th>';
              echo '<th>Username</th>';
              echo '</tr>';
              echo '</thead>';
              echo '<tbody>';
              $count = 0;
              foreach($mutual as $friend){
                echo "<tr>";
                echo "<td>".$count."</td>";
                echo "<td><a href='chat_members.php?view=$friend'>$friend</a></td>";
                echo "</tr>";
                $count += 1;
              }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
              $friends = TRUE;
            }

            if (sizeof($followers))
            {
              echo '<div class="col-md-4">';
              echo '<div class="panel panel-default">';
              echo "<div class='panel-heading'>$name2 followers</div>"; 
              echo '<div class="panel-body">';
              echo '<div class="table-responsive">';
              echo '<table class="table table-hover">';
              echo '<thead>';
              echo '<tr>';
              echo '<th>#</th>';
              echo '<th>Username</th>';
              echo '</tr>';
              echo '</thead>';
              echo '<tbody>';
              $count = 0;
              foreach($followers as $friend){
                echo "<tr>";
                echo "<td>".$count."</td>";
                echo "<td><a href='chat_members.php?view=$friend'>$friend</a></td>";
                echo "</tr>";
                $count += 1;
              }
              echo "</tbody>";
                echo "</table>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
              $friends = TRUE;
            }

            if (sizeof($following))
            {
              echo '<div class="col-md-4">';
              echo '<div class="panel panel-default">';
              echo "<div class='panel-heading'>$name3 following</div>"; 
              echo '<div class="panel-body">';
              echo '<div class="table-responsive">';
              echo '<table class="table table-hover">';
              echo '<thead>';
              echo '<tr>';
              echo '<th>#</th>';
              echo '<th>Username</th>';
              echo '</tr>';
              echo '</thead>';
              echo '<tbody>';
              $count = 0;
              foreach($following as $friend){
                echo "<tr>";
                echo "<td>".$count."</td>";
                echo "<td><a href='chat_members.php?view=$friend'>$friend</a></td>";
                echo "</tr>";
                $count += 1;
              }
              echo "</tbody>";
                echo "</table>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
              $friends = TRUE;
            }

            echo '<div class="col-md-4">';

              if (!$friends) echo "<br>You don't have any friends yet.<br><br>";

              echo "<a class='btn btn-info' href='chat_messages.php?view=$view'>" .
               "View $name2 messages</a>";
            
            echo "</div>"; 

                  ?>

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


