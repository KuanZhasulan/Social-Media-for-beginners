<?php 
  require_once 'origin.php';

  if (isset($_SESSION['user']))
  {
    destroy_Session();
    header('Location: index.php');
  }
  else echo "<div class='main'><br>" .
            "You cannot log out because you are not logged in";
?>

    <br><br></div>
  </body>
</html>
