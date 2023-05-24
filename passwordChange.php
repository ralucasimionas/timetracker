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

$id = $_SESSION["id"];
$user = getOne("user_id", $id, "users", $conn);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
  if (validateInput($updatePassValidator)) {
    $password = $_POST["password"];
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $updatedInfo = [
      "user_password" => $hash,
    ];

    if (update("user_id", $id, "users", $conn, $updatedInfo)) {
      $_SESSION["successMessage"] =
        "Your personal info has been successfully updated.";
      header("Location: userDashBoard.php");
    }
  } else {
    echo "error";
  }
}
?>

<html>

<body>
    <form method="POST" action ="<?php echo htmlspecialchars(
      $_SERVER["PHP_SELF"]
    ); ?>">
       <label>Password:</label>
    <?php generateInput("password", "password", "New password"); ?>
    <br>

    <label>Repeat password:</label>
    <?php generateInput(
      "password",
      "repeat_password",
      "Repeat your password"
    ); ?>
    <br>

    

    <input type='submit' value="Update" name='update' />
    <button type="button" onclick="history.back();">Back</button>
    </form>

   
</body>
</html>
