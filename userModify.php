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

$id = htmlspecialchars($_SESSION["id"]);
$user = getOne("user_id", $id, "users", $conn);
// var_dump($user);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
  if (validateInput($updateValidator)) {
    $name = $_POST["name"];
    $mobile = $_POST["mobile"];
    $email = $_POST["email"];

    $updatedInfo = [
      "user_name" => $name,
      "user_mobile" => $mobile,
      "user_email" => $email,
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
       <label>Full name:</label>
    <?php generateInput("text", "name", "$user[user_name]"); ?>

   
    <br>

    <label>Mobile:</label>
    <?php generateInput("text", "mobile", $user["user_mobile"]); ?>
   
  
    <br>

    <label>E-mail:</label>
    <?php generateInput("email", "email", "$user[user_email]"); ?>
  
  
    <br>

    <input type='submit' value="Update" name='update' />
    <button type="button" onclick="history.back();">Back</button>
    </form>

   
</body>
</html>
