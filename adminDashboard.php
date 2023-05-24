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

if (isset($_SESSION["successMessage"])) {
  echo $_SESSION["successMessage"];
  unset($_SESSION["successMessage"]);
}

$id = $_SESSION["id"];
$user = getOne("user_id", $id, "users", $conn);

$departments = "SELECT * FROM departments";
$departmentsArray = mysqli_query($conn, $departments);

$category = "SELECT * FROM categories";
$categoryArray = mysqli_query($conn, $category);

$users = "SELECT * FROM users";
$usersArray = mysqli_query($conn, $users);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["addNewDep"])) {
  if (validateInput(["required" => true])) {
    $newDepartment = $_POST["new_department_name"];
    $query = "SELECT * FROM departments WHERE department_name='$newDepartment'";
    $queryResponse = mysqli_query($conn, $query);
    $rows = mysqli_num_rows($queryResponse);
    if ($rows > 0) {
      echo "This department already exists.";
    } else {
      $updatedDepartments = "INSERT INTO departments(`department_name`) VALUES('$newDepartment')";
      $result = mysqli_query($conn, $updatedDepartments);
      if ($result) {
        $_SESSION["successMessage"] =
          "Your department has been successfully added to the database.";
        header("Location: adminDashboard.php");
      } else {
        echo "Your department couldn't be added to the database. Error: " .
          mysqli_error($conn);
      }
    }
  }
} elseif (
  $_SERVER["REQUEST_METHOD"] === "POST" &&
  isset($_POST["changeState"])
) {
  echo $user["user_active"];
  echo "new state" . $newState;
  $userId = $_POST["id"];
  $uniqueUser = getOne("user_id", $userId, "users", $conn);
  var_dump($uniqueUser);

  if ($uniqueUser["user_active"] !== "yes") {
    $newState = "yes";
  } elseif ($uniqueUser["user_active"] !== "no") {
    $newState = "no";
  }

  $query = "UPDATE users SET user_active='$newState' WHERE user_id=$userId";
  var_dump($query);
  $result = mysqli_query($conn, $query);
  var_dump($result);

  if ($result) {
    $_SESSION["successMessage"] =
      "Your user state has been successfully updated";
    header("Location: adminDashboard.php");
  } else {
    echo "Your user couldn't be updated " . mysqli_error($conn);
  }
}
?>


<html>

<body>
<h1>Welcome, <?php echo $user["user_name"]; ?>!</h1>

<div>
<h3>Personal Information</h3>

<p>Name: <?php echo $user["user_name"]; ?>.</p>
<p>Role: <?php echo $user["user_role"]; ?>.</p>
<p>E-mail: <?php echo $user["user_email"]; ?>.</p>
<p>Mobile: <?php echo $user["user_mobile"]; ?></p>
<p>Current department: <?php echo $user["user_department"]; ?>.</p>

<a class="button" href="userModify.php">Edit your personal info.</a>
<br>
<a  class="button" href="passwordChange.php">Change you password</a>
<br>
<br>
<a href="./utils/logout.inc.php"><input type="submit" name="logout" value="Logout"/></a>


<br/>
<br/>
<table>
  <tr>
    <th>Department</th>
    </tr>
<?php foreach ($departmentsArray as $department): ?>
  <tr>
    <td>
      <a href="departmentDetails.php?id=<?php echo $department[
        "id"
      ]; ?>"> <?php echo $department["department_name"]; ?></a> 

     </td>
    </tr>
  <?php endforeach; ?>
</table>

<form method="POST" action="<?php echo htmlspecialchars(
  $_SERVER["PHP_SELF"]
); ?>">
<input type="text" name="addNewDep" hidden>
<input type="text" name="new_department_name" placeholder="New department name"/>
<button type="submit" value="addNewDep">Add new department</button>
</form>




<br>
<table>
    <tr><th>User name</th></tr>
    <?php foreach ($usersArray as $user): ?>
    <tr>
        <td> <a href="userDetails.php?user=<?php echo htmlspecialchars(
          $user["user_id"]
        ); ?>"> <?php echo $user["user_name"]; ?></a> </td>

        <td><form method="POST" action = "<?php echo htmlspecialchars(
          $_SERVER["PHP_SELF"]
        ); ?>"> 
        <input name="id" value="<?php echo $user[
          "user_id"
        ]; ?>" hidden /><input type = "text" name="changeState" hidden/>
        <button type="submit" value="changeState"><?php if (
          $user["user_active"] === "yes"
        ): ?> 
        Deactivate
        <?php elseif ($user["user_active"] === "no"): ?>
         Activate
         <?php endif; ?>
         </button></form></td>
    </tr> <?php endforeach; ?> 

</table>
    


<a href="register.php">
<button>Add new user</button></a>






</div>



</body>



</html>

