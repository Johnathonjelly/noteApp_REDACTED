<?php
session_start();
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Logging Out</title>
  </head>
  <body>
    <script>
    window.setTimeout( () => {
        window.location = 'login.php';
      }, 3000);
    </script>
    <div>
          <p>You have been logged out.</p>
    </div>

  </body>
</html>
<?php
  session_destroy();
 ?>
