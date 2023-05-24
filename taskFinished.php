<?php

require_once "utils/db.inc.php";
require_once "utils/inputValidation.inc.php";
require_once "utils/validators.inc.php";
require_once "utils/generateInput.inc.php";
require_once "utils/tableData.inc.php";

session_start();

if (!isset($_SESSION["email"]) || empty($_SESSION["email"])) {
  header("Location: login.php");
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $id = $_GET["id"];
  }
} else {
  $id = $_POST["id"];
}

$myTask = getOne("activity_id", $id, "activities", $conn);

if (isset($_SESSION["errorMessage"])) {
  echo $_SESSION["errorMessage"];
  unset($_SESSION["errorMessage"]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
  if (validateInput($taskFinishedValidator)) {
    $taskId = $_POST["id"];

    $dateFinish = $_POST["date_finish"];

    $taskHours = $_POST["task_hours"];

    $todayDate = date("y-m-d");

    $dateDiff = strtotime($todayDate) - strtotime($dateFinish);
    $dateDiffDays = floor($dateDiff / (60 * 60 * 24));

    if ($dateDiffDays > 1209600) {
      $_SESSION["errorMessage"] =
        "The data logging timeframe is restricted to a maximum of two weeks prior. caz1";
      header("Location: taskFinished.php?id=$taskId");
    } elseif ($taskHours > 8) {
      $_SESSION["errorMessage"] =
        "You cannot log more than 8 hours per task.caz2";
      header("Location: taskFinished.php?id=$taskId");
    } elseif (strtotime($dateFinish) > strtotime($todayDate)) {
      $_SESSION["errorMessage"] = "You cannot log hours in the future.caz3";
      header("Location: taskFinished.php?id=$taskId");
    } elseif ($dateDiffDays < 14 && $hours <= 8) {
      $updatedInfo = [
        "date_finish" => $dateFinish,
        "task_hours" => $taskHours,
        "task_state" => "finished",
      ];

      $updateVar = update(
        "activity_id",
        $id,
        "activities",
        $conn,
        $updatedInfo
      );

      if (update("activity_id", $taskId, "activities", $conn, $updatedInfo)) {
        $_SESSION["successMessage"] =
          "Your task information been successfully updated.";
        header("Location: userDashBoard.php");
      }
    }
  } else {
    echo "Task id" . $taskId;
    $taskId = $_POST["id"];
    $_SESSION["errorMessage"] = "Please complete all the fields.";
    header("Location: taskFinished.php?id=$taskId");
  }
}
?>

 <html>
    <h1>Update task date & hours</h1>
    <h3 style="display: inline" >Activity name:</h3>
    <h3 style="display: inline"><?php echo $myTask["task_name"] . ";"; ?></h3>
<br>
    <h4 style="display: inline">Department: </h4>
    <h4 style="display: inline"> <?php echo $myTask["department"]; ?></h4>
    <br>
    <br>

<form method="POST" action = "<?php echo htmlspecialchars(
  $_SERVER["PHP_SELF"]
); ?>">
<input name="id" value="<?php echo $myTask["activity_id"]; ?>" type= "hidden" />
    
  
<label>Finish date:</label>
<!-- <input type="date" name="date_finish" value="<?php echo $myTask[
  "date_finish"
]; ?>" placeholder="<?php echo $myTask["date_finish"]; ?>" /> -->

<?php generateInput("date", "date_finish", $myTask["date_finish"]); ?>
<br>


<label>Hours:</label>
<!-- <input type="number" name="task_hours" value="<?php echo $myTask[
  "task_hours"
]; ?>" placeholder="<?php echo $myTask["task_hours"]; ?>" /> -->
<?php generateInput("number", "task_hours", $myTask["task_hours"]); ?>
<br>


<br>

<input type='submit' value="Update" name='update'/>
    <button type="button" onclick="history.back();">Back</button>
</form>




 </html>
