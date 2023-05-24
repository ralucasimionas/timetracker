<?php
require_once "utils/db.inc.php";
require_once "utils/generateInput.inc.php";
require_once "utils/inputValidation.inc.php";
require_once "utils/validators.inc.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
  if (validateInput($loginValidator)) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE user_email='$email';";
    $result = mysqli_query($conn, $query);
    $rows = mysqli_num_rows($result);

    if ($rows > 0) {
      $user = mysqli_fetch_assoc($result);

      if (password_verify($password, $user["user_password"])) {
        session_regenerate_id(true);
        $_SESSION["id"] = $user["user_id"];
        $_SESSION["email"] = $user["user_email"];
        $_SESSION["name"] = $user["user_name"];
        $_SESSION["role"] = $user["user_role"];
        $_SESSION["active"] = $user["user_active"];

        if ($_SESSION["role"] === "admin" && $_SESSION["active"] === "yes") {
          header("Location: adminDashboard.php");
        } elseif (
          $_SESSION["role"] === "user" &&
          $_SESSION["active"] === "yes"
        ) {
          header("Location: userDashboard.php");
        } elseif ($_SESSION["active"] === "no") {
          echo "Your user id not active momentarily. Please speak with your supervisor.";
        }
      } else {
        echo "The data you provided are not correct.";
      }
    }
  }
}
?>

<html>

<form action="<?php echo htmlspecialchars(
  $_SERVER["PHP_SELF"]
); ?>" method="POST">
   <label>Email: </label>
    <?php generateInput("email", "email", "Enter your e-mail address"); ?>
    <br>
    <label>Password: </label>
    <?php generateInput("password", "password", "Enter your password."); ?>
    <br>
    <input type="submit" name="submit" value="Login" />
</form>

</html>