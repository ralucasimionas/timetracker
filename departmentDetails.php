<?php

require_once "./utils/db.inc.php";
require_once "./utils/generateInput.inc.php";
require_once "./utils/inputValidation.inc.php";
require_once "./utils/validators.inc.php";
require_once "./utils/tableData.inc.php";

session_start();

if (
  !isset($_SESSION["email"]) ||
  empty($_SESSION["email"]) ||
  $_SESSION["role"] != "admin"
) {
  header("Location: login.php");
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $departmentId = $_GET["id"];
  }
} else {
  $departmentId = $_POST["id"];
}

echo "dep id" . $departmentId;
echo "post id" . $_POST["id"];

$myDepartment = getOne("id", $departmentId, "departments", $conn);
$departmentName = $myDepartment["department_name"];

$tasks = "SELECT * FROM categories WHERE department_name='$departmentName'";
$tasksArray = mysqli_query($conn, $tasks);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["addNewTask"])) {
  if (validateInput(["required" => true])) {
    $depId = $_POST["id"];
    $newTask = $_POST["new_task_name"];
    $query = "SELECT * FROM categories WHERE department_name='$newTask'";
    $queryResponse = mysqli_query($conn, $query);
    $rows = mysqli_num_rows($queryResponse);
    if ($rows > 0) {
      echo "This task already exists.";
    } else {
      $updatedTask = "INSERT INTO categories(`category_name`, `department_name`) VALUES ('$newTask', '$departmentName')";
      $result = mysqli_query($conn, $updatedTask);
      if ($result) {
        $_SESSION["successMessage"] =
          "Your task has been successfully added to the database.";
        header("Location: departmentDetails.php?id=$departmentId");
      } else {
        echo "Your department couldn't be added to the database. Error: " .
          mysqli_error($conn);
        header("Location: departmentDetails.php?id=$departmentId");
      }
    }
  }
} else {
  echo "error";
}
?>



<html>
    <h3>Department name: <?php echo $departmentName; ?></h3>
    <br>

<table>
  
<th>Task name</th>
<th>User</th>
<th>State</th>
<th>Finish Date</th>

<?php foreach ($tasksArray as $task): ?>
  <tr>
  <td> <?php echo $task["category_name"]; ?></td>

  <?php
  $taskDetails =
    "SELECT * FROM activities WHERE task_name = '" .
    $task["category_name"] .
    "'";
  $taskDetailsArray = mysqli_query($conn, $taskDetails);

  while ($taskDetail = mysqli_fetch_assoc($taskDetailsArray)):

    $taskUser =
      "SELECT * FROM users WHERE user_id='" . $taskDetail["user_id"] . "'";
    $taskUserDetails = mysqli_query($conn, $taskUser);

    while ($userDetails = mysqli_fetch_assoc($taskUserDetails)): ?>

      <td><?php echo $userDetails["user_name"]; ?></td> <?php endwhile;
    ?>
        <td> <?php echo $taskDetail["task_state"]; ?></td>
        <td><?php echo $taskDetail["date_finish"]; ?></td>
      <?php
  endwhile;
  ?>
  </tr>
  <?php endforeach; ?>

</table>


<form method="POST" action="<?php echo htmlspecialchars(
  $_SERVER["PHP_SELF"]
); ?>">
<input name="id" value="<?php echo $myDepartment["id"]; ?>" type= "hidden" />
<input type="text" name="new_task_name" placeholder="New task name"/>
<input type="submit" value="Add new task" name="addNewTask"/>
</form>

<a href="adminDashboard.php"><button>Back</button></a>


</html>