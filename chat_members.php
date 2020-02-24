<?php 
require_once 'origin.php';
 if (!$loggedin) die();
 $view = $user;
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
  <?php
  if (isset($_GET['view']))
  {
    $view = clear_Text($_GET['view']);
    
    if ($view == $user) $name = "Your";
    else                $name = "$view's";
    
    
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
 }

  if (isset($_GET['add']))
  {
    $add = clear_Text($_GET['add']);
    $result = $chat->prepare("SELECT * FROM chat_friends WHERE user = :add AND friend = :friend");
    $result->execute(array('add' => $add, 
      'friend'=> $user)); 
    
$resultN = $chat->prepare("SELECT count(*) FROM chat_friends WHERE user = :add AND friend = :friend"); 
$resultN->execute(array('add' => $add, 
      'friend'=> $user)); 
$num_rows = $resultN->fetchColumn(); 
    if (!$num_rows)
    {
     $result1 = $chat->prepare("INSERT INTO chat_friends(user, friend) VALUES(:add, :friend)");
    $result1->execute(array('add' => $add, 
      'friend'=> $user));
  }
}
  elseif (isset($_GET['remove']))
  {
    $remove = clear_Text($_GET['remove']);
    $result2 = $chat->prepare("DELETE FROM chat_friends WHERE user=:remove AND friend=:user");
    $result2->execute(array('remove' => $remove, 
      'user'=> $user));
    
  }

   ?>
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
                        <a <?php if (isset($rowM['user'])){ echo 'class="active-menu"'; } ?>   href="chat_members.php?view=<?php echo $user; ?>"><i class="fa fa-dashboard fa-3x"></i>Personal Page</a>
                    </li>
                    <li>
                        <a <?php if (!isset($rowM['user'])){ echo 'class="active-menu"'; } ?> href="chat_members.php"><i class="fa fa-dashboard fa-3x"></i>Members</a>
                    </li>
                     <li>
                        <a  href="chat_friends.php"><i class="fa fa-desktop fa-3x"></i> Friends</a>
                    </li>
                    <li>
                        <a  href="tab-panel.html"><i class="fa fa-qrcode fa-3x"></i> Messages</a>
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
                     <h2><?php if(isset($name)){ echo $name." Profile";} ?></h2>   
                        <h5>Welcome <?php echo $_SESSION['name']." ".$_SESSION['surname']; ?> , Love to see you back. </h5>
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
   
           <?php 

 


   

if (isset($rowM['user']))
{

  echo '<div class="row">';
  echo '<div class="col-md-8">';
  echo '<table class="table">';
  echo '<tr>';
  echo '<td>Name</td>';
  echo  '<td>'.$rowM['name'].'</td>';
  echo  '</tr>';
  echo  '<tr>';
  echo  '<td>Surname</td>';
  echo  '<td>'.$rowM['surname'].'</td>';
  echo  '</tr>';
  echo  '<tr>';
  echo  '<td>Designation</td>';
  echo  '<td>'.$rowM['occupation'].'</td>';
  echo  '</tr>';
  echo  '<tr>';
  echo  '<td>Age</td>';
  echo  '<td>'.$rowM['age'].'</td>';
  echo  '</tr>';
  echo  '</table>';
  echo  '</div>';

  echo  '<div class="col-md-4 col-sm-4">';
  echo  '<div class="panel panel-default">';
  echo  '<div class="panel-heading">About Me!</div>';
  echo  '<div class="panel-body">';
  echo  '<p>'.stripslashes($rowM['content']).'</p>';
  echo  '</div>';
  echo  '<div class="panel-footer">';
  echo  "<a class='btn btn-info' href='chat_messages.php?view=$view'>View $name messages</a>";
  echo  '  <a class="btn btn-info" ><i class="fa fa-edit "></i> Edit</a>';
  echo  '</div>';
  echo  '</div>';
  echo  '</div>';



   echo  "</div>";

     
  }
  else
  {
  $result = $chat->prepare("SELECT user FROM chat_members ORDER BY user"); 
  $result->execute(); 
  echo '</hr><div class="row">';
  echo '<div class="col-md-12 col-sm-12 col-xs-12">'; 
  echo '<div class="panel panel-default">';
  echo '<div class="panel-heading">';
  echo  'Other Members';
  echo  '</div>';
  echo  '<div class="panel-body">';
  echo  '<div class="table-responsive">';
  echo  '<table class="table table-striped table-bordered table-hover">';
  echo  '<thead>';
  echo  '<tr>';
  echo  '<th>User</th>';
  echo  '<th>Status</th>';
  echo  '<th>Action</th>';
  echo  '</tr>';
  echo  '</thead>';
  echo  '<tbody>';
    
    echo "<h3>Other Members</h3><ul>";
    $userRow = $result->fetchAll(PDO::FETCH_ASSOC);
    foreach($userRow as $row)
    {
      echo "<tr class='gradeA'>"; 
      if ($row['user'] == $user)
      { 
        continue;
      }
      
      echo "<td><a href='chat_members.php?view=" .
        $row['user'] . "'>" . $row['user'] . "</a></td>";
      $follow = "follow";
      $result1 = $chat->prepare("SELECT * FROM chat_friends WHERE user = :add AND friend = :friend");
      $result1 ->execute(array('add' => $row['user'], 
        'friend'=> $user)); 
      
  $resultN = $chat->prepare("SELECT count(*) FROM chat_friends WHERE user = :add AND friend = :friend"); 
  $resultN->execute(array('add' => $row['user'], 
        'friend'=> $user)); 
  $t1 = $resultN->fetchColumn(); 
      

  $result1 = $chat->prepare("SELECT * FROM chat_friends WHERE user = :add AND friend = :friend");
      $result1 ->execute(array('add' => $user, 
        'friend'=> $row['user'])); 
      
  $resultN = $chat->prepare("SELECT count(*) FROM chat_friends WHERE user = :add AND friend = :friend"); 
  $resultN->execute(array('add' => $user, 
        'friend'=> $row['user'])); 
  $t2 = $resultN->fetchColumn(); 


      if (($t1 + $t2) > 1){ 
        echo "<td> &harr; is a mutual friend</td>"; 
    }
      elseif ($t1){
               echo "<td> &larr; you are following</td>";
             }
      elseif ($t2)       { echo "<td> &rarr; is following you</td>";
        $follow = "recip"; }
        else
        {
          echo "<td> stranger </td>";
        }
      
      if (!$t1){
       echo "<td> [<a href='chat_members.php?add="   .$row['user'] . "'>$follow</a>]</td>";
       }
      else{
           echo "<td> [<a href='chat_members.php?remove=".$row['user'] . "'>drop</a>]</td>";
    }
    
    echo "</tr>";

    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</div>";
    echo "</div>";          
    echo "</div>";      
    echo "</div>";

  }

  

  

 
?>
  
  




            
          
                    
                 <!-- /. ROW  -->           
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