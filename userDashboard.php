<?php

require_once "./utils/db.inc.php";
require_once "./utils/generateInput.inc.php";
require_once "./utils/inputValidation.inc.php";
require_once "./utils/validators.inc.php";
require_once "./utils/tableData.inc.php";

session_start();

if (!isset($_SESSION["email"]) || empty($_SESSION["email"])) {
  header("Location: login.php");
}

if (isset($_SESSION["successMessage"])) {
  echo $_SESSION["successMessage"];
  unset($_SESSION["successMessage"]);
}

$id = $_SESSION["id"];
$user = getOne("user_id", $id, "users", $conn);

$upcomingTasksQuery = "SELECT * FROM activities WHERE user_id=$id AND task_state=''";
$upcomingTasksArray = mysqli_query($conn, $upcomingTasksQuery);

$finishedTasksQuery = "SELECT * FROM activities WHERE user_id=$id AND task_state='finished'";
$finishedTasksArray = mysqli_query($conn, $finishedTasksQuery);
?>


<html>

<body>
<h1>Welcome, <?php echo $user["user_name"]; ?>!</h1>

<div>
<h3>Personal Information</h3>

<p>Name: <?php echo $user["user_name"]; ?>.</p>
<p>E-mail: <?php echo $user["user_email"]; ?>.</p>
<p>Mobile: <?php echo $user["user_mobile"]; ?></p>
<p>Current department: <?php echo $user["user_department"]; ?>.</p>

<a class="button" href="userModify.php">Edit your personal info.</a>
<br>
<a  class="button" href="passwordChange.php">Change you password</a>
<br>
<br>
<a href="./utils/logout.inc.php"><input type="submit" name="logout" value="Logout"/></a>


<h3>Upcoming tasks</h3>
<table>
  <tr>
    <th>Task name</th>
    <th>Department</th>
    
    
    <th></th>
  </tr>
<?php foreach ($upcomingTasksArray as $activity): ?>
  <tr>
    <td>
      <?php echo $activity["task_name"]; ?> 
     </td>
    <td> <?php echo $activity["department"]; ?></td>
    <td><a href="taskFinished.php?id=<?php echo $activity[
      "activity_id"
    ]; ?>"> <button>Mark as finished</button></a></td>
   
  </tr>
  <?php endforeach; ?>
</table>

<h3>Finished tasks</h3>
<table>
  <tr>
    <th>Task name</th>
    <th>Department</th>
    <th>Finish date</th>
    <th>Hours</th>
   
  </tr>
<?php foreach ($finishedTasksArray as $activity): ?>
  <tr>
    <td>
      <?php echo $activity["task_name"]; ?> 
     </td>
    <td> <?php echo $activity["department"]; ?></td>
   
    <td><?php echo $activity["date_finish"]; ?></td>
    <td><?php echo $activity["task_hours"]; ?></td>
    <td><a href="taskFinished.php?id=<?php echo $activity[
      "activity_id"
    ]; ?>"><button>Edit</button></a></td>
   
    

    
   
  </tr>
  <?php endforeach; ?>
</table>






</div>



</body>



</html>
