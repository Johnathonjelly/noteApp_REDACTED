<?php
//start a session because why not you know
//make an array to hold messages we may need to display to user like "Hey! You need a password!"
//initialize the variables needed
//now we have the isset submit checks
//if the msgs array has no error messages inside of it connect to the DB
//the variable cost is for encryption
//the variable salt is for encryption
session_start();
$msgs = array();
$cost = 3;
$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
// Prefix information about the hash so PHP knows how to verify it later.
// "$2a$" Means I'm using the Blowfish algorithm. The following two digits are the cost parameter.
$salt = sprintf("$2a$%02d$", $cost) . $salt;
$email = ''; $firstname = ''; $lastname = ''; $un = ''; $pwd = '';
if (isset($_POST['didSubmit'])) {
    $un = isset($_POST['username']) ? $_POST['username'] : '';
    $pwd = isset($_POST['password']) ? $_POST['password'] : '';
    // $email = isset($_POST['email']) ? $_POST['email'] : '';
    $firstname = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $lastname = isset($_POST['lastName']) ? $_POST['lastName'] : '';
    $hash = crypt($pwd, $salt);
    if (empty($un) || empty($pwd)) {
        $msgs[] = 'Username and password are required';
    }
    if (empty($firstname) || empty($lastname)) {
      $msgs[] = 'Your first and last name are required.';
    }
      if (count($msgs) === 0) {
        require_once('includes/dbconn.php');
        $connect = new Connection;
        $connection = $connect->getConnection();
        $sql = $connection->prepare('CALL sp_addUser(?, ?, ?, ?)');
        $sql->execute(array($firstname, $lastname, $un, $hash));
        // $msgs[] = "Success! UserID = {$_SESSION['uid']} ";
        header('Location: https://stoic-cookbook.com/login.php');
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
    <h1 class ="text-center">Register to begin note taking</h1>
    <form class="register" action="register.php" method="post">
      <fieldset>
        <legend>Register</legend>
        <?php
        //if the count of msgs is greater than zero than an error message was stored. Display those to the user
          if (count($msgs) > 0) {
            echo '<ul>';
            foreach ($msgs as $errors) {
              echo "<li>$errors</li>";
            }
            echo "</ul>";
          }
         ?>
        <input type="hidden" name="submitted" id="submitted" value="1">
        <dl>
          <dt>
            <label for="Full Name">Your Full Name*:</label>
          </dt>
          <dd>
            <input type="text" name="firstName" placeholder="First Name" maxlength="50" autofocus="autofocus" required>
            <input type="text" name="lastName" placeholder="Last Name" maxlength="50" required>
          </dd>

          <!-- <dt>
            <label for="email">Email Address:*</label>
          </dt>

          <dd>
            <input type="text" name="email" placeholder="Email" id="email" required>
          </dd> -->

          <dt>
            <label for="username">Username:*</label>
          </dt>

          <dd>
            <input type="text" name="username" placeholder="Username" maxlength="50" required >
          </dd>

          <dt>
            <label for="password">Password:*</label>
          </dt>

          <dd>
            <input type="password" name="password" placeholder="Password" maxlength="150" required>
          </dd>

            <input type="submit" name="didSubmit" value="Submit">
        </dl>
        <a href="login.php">Login</a>
        <a href="logout.php">Logout?</a>
      </fieldset>

    </form>
  </body>
</html>
