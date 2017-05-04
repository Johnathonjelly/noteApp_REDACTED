<?php
session_start();
require_once('includes/dbconn.php');
if (isset($_SESSION['uid'])) {
  $uid = $_SESSION['uid'];
} else {
  header('Location: login.php');
}
$id = isset($_GET['id']) ? $_GET['id'] : '';
$cmd = isset($_GET['cmd']) ? $_GET['cmd'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($id) && $cmd === "delete") {
  // echo "$id is the current ID and the command is $cmd";
  $connect = new Connection;
  $connection = $connect->getConnection();
  $sql = $connection->prepare('CALL sp_deleteNote(?, ?)');
  $sql->execute(array($id,$uid));

}
// var_dump($uid);
$note = ''; $tags = ''; $msgs = array();
if (isset($_POST['noteSubmit'])) {
  $note = isset($_POST['notes']) ? $_POST['notes'] : '';
  $tags = isset($_POST['tags']) ? $_POST['tags'] : '';
  if (empty($note)) {
    $msgs[] = "You cannot submit a blank note";
  }
    if (count($msgs) === 0) {
      $connect = new Connection;
      $connection = $connect->getConnection();
      $sql = $connection->prepare('CALL sp_addNotes(?, ?)');
      $sql->execute(array($note, $uid));
      $results = $sql->fetch();
      $noteID = $results['noteID'];
      $msgs[] = "Note successfully added. ";
      $sql = $connection->prepare('CALL sp_addTags(?)');
      $sql->execute(array($tags));
      $results = $sql->fetch();
      $tagID = $results['tagID'];
      $sql = $connection->prepare('CALL sp_addNoteTags(?, ?)');
      $sql->execute(array($tagID, $noteID));
      $msgs[] = "Tags added succesfully.";
  }
}
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Notes</title>
    <link rel="stylesheet" href="css/main.css">
  </head>
  <body>
    <fieldset>
      <legend>Take Your Notes</legend>
      <form class="noteApp" action="<?=$_SERVER["SCRIPT_NAME"]?>" method="post">
          <dl>
            <dt>
              <label for="notes">Enter Text</label>
            </dt>
            <dd>
              <textarea name="notes" rows="8" cols="80" autofocus="autofocus"></textarea>
            </dd>
          </dl>
          <dt>
            <label for="tags">Tags</label>
          </dt>
          <dd>
            <input type="text" name="tags" placeholder="Enter Tags Here">
            <button id="noteSubmit" value="1" type="submit" name="noteSubmit">Submit</button>
          </dd>
          <?php
          foreach($msgs as $msg) {
            echo $msg;
          }
          ?>
          <hr>
          <button type="submit" name="showNotes" value="2">Show Notes</button>
          </form>
          <form action="<?=$_SERVER["SCRIPT_NAME"]?>" method="get">
            <input type="text" name="search" placeholder="Search">
          </form>

          <?php
            if (isset($_POST['showNotes']) || $cmd === 'delete' || !empty($search)) {
              $connect = new Connection;
              $connection = $connect->getConnection();
              if (!empty($search)) {
                $sql = $connection->prepare('CALL sp_search(?, ?)');
                $sql->execute(array($uid, $search));
              } else {
              $sql = $connection->prepare('CALL sp_showNote(?)');
              $sql->execute(array($uid));
            }
              $results = $sql->fetchAll();
              ?>
              <br>
              <table>
                <tr>
                  <th>Note</th>
                  <th>Tags</th>
                  <th>Time Saved</th>
                  <th>Action</th>
                </tr>
                <?php
              foreach($results as $result) {
                ?>
                <tr>
                  <td><?=htmlentities($result['notes']);?></td>
                  <td><?=htmlentities($result['tagName']);?></td>
                  <td><?=htmlentities($result['savedtime']);?></td>
                  <td><a onclick="return confirm('Are You Sure?');" href="?cmd=delete&amp;id=<?=$result['ID']?>">Delete</a></td>
                </tr>
                <?php
              };
            }
            ?>
          </tr>
        </table>


          <hr>
          <a href="logout.php">Logout?</a>
          <a href="register.php">Register</a>
        </fieldset>


  </body>
</html>
