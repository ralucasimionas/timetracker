<?php

require_once "utils/db.inc.php";
require_once "utils/inputValidation.inc.php";
require_once "utils/validators.inc.php";
require_once "utils/generateInput.inc.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
  if (validateInput($registerValidator)) {
    $name = $_POST["name"];
    $mobile = $_POST["mobile"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $repeat_password = $_POST["repeat_passowrd"];

    $query = "SELECT * FROM users WHERE user_email='$email';";
    $response = mysqli_query($conn, $query);
    $rows = mysqli_num_rows($response);
    if ($rows > 0) {
      echo "A user with this email address already exists.";
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);

      $query = "INSERT INTO users (user_name, user_mobile, user_email, user_password, user_department, user_role, user_active) VALUES ('$name', '$mobile', '$email', '$hash' , 'general', 'user', 'yes');";
      mysqli_query($conn, $query);

      if (
        isset($_SESSION["email"]) ||
        !empty($_SESSION["email"]) ||
        $_SESSION["role"] === "admin"
      ) {
        header("Location: adminDashboard.php");
      } else {
        header("Location: login.php");
      }
    }
  }
}
?>

<html>


<form action="<?php echo htmlspecialchars(
  $_SERVER["PHP_SELF"]
); ?>" method="POST">

<br>
   <label>User name:</label>
    <?php generateInput("text", "name", "User full name"); ?>
    <br>

    <label>Mobile:</label>
    <?php generateInput("text", "mobile", "User mobile number"); ?>
    <br>

    <label>E-mail:</label>
    <?php generateInput("email", "email", "User e-mail address"); ?>
    <br>
 
    <label>Password:</label>
    <?php generateInput("password", "password", "User password"); ?>
    <br>

    <label>Repeat password:</label>
    <?php generateInput("password", "repeat_password", "Repeat  password"); ?>
    <br>
    <input type='submit' value="Submit" name='submit' />
</form>

<?php if (
  !isset($_SESSION["email"]) ||
  empty($_SESSION["email"]) ||
  $_SESSION["role"] != "admin"
): ?>
<p>Already have an account? <a href="login.php">Login here</a></p>
<?php endif; ?>


</html>