<?php
session_start();
$msgs = array();
$un = ''; $pwd;
if (isset($_POST['didSubmit'])) {
    $un = isset($_POST['username']) ? $_POST['username'] : '';
    $pwd = isset($_POST['password']) ? $_POST['password'] : '';
    if (empty($un) || empty($pwd)) {
        $msgs[] = 'Username and password are required';
    }
    if (count($msgs) === 0) {
        require_once('includes/dbconn.php');
        $connect = new Connection;
        $connection = $connect->getConnection();
        $sql = $connection->prepare('CALL sp_login(?, ?)');
        $sql->execute(array($un, $pwd));
        $results = $sql->fetch();
        if ($results === false) {
            $msgs[] = 'Wrong username or password';
        } else {
            $_SESSION['uid'] = $results['userID'];
            $_SESSION['fname'] = $results['firstname'];
            $_SESSION['lname'] = $results['lastname'];

            $msgs[] = "Success! UserID = {$_SESSION['uid']} ";
            header('Location: http://stoic-cookbook.com/noteApp.php');
        }
    }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Take Notes!</title>
    <meta name="author" content="Johnathon Southworth">
    <link rel="stylesheet" href="css/main.css">
  </head>
  <body>
    <!--$_SERVER["SCRIPT_NAME"] submit to self no matter if the page name changes-->
    <div class="wrapper">
    <form class="loginForm" action="<?=$_SERVER["SCRIPT_NAME"]?>" method="post">
      <fieldset>
        <legend>Login</legend>
        <?php
        if (count($msgs) > 0) {
            echo '<ul>';
            foreach ($msgs as $error) {
                echo "<li>$error</li>";
            }
            echo '</ul>';
        }
         ?>
        <dl>
          <dt>
          <label for="username">Username</label>
          </dt>
          <dd>
          <input type="text" id="username" name="username" placeholder="Username" value="<?=htmlentities($un);?>" autofocus="autofocus">

          </dd>
          <dt>
          <label for="password">Password</label>
          </dt>
          <dd>
          <input type="password" id="password" name="password" placeholder="Password">
        </dd>
        </dl>

        <button type="submit" name="submit">Submit</button>
        <input type="hidden" name="didSubmit" value="1">
        <hr>
        <a href="register.php">No account? Create one!</a>
        <hr>
        <a href="logout.php">Logout?</a>
      </fieldset>


    </form>
      </div><!--end wrapper-->
  </body>
</html>
