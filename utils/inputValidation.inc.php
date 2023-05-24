<?php
// require_once "utils/logging.inc.php";
function validateInput($reguliDeValidare)
{
  foreach ($reguliDeValidare as $numeCamp => $reguli) {
    foreach ($reguli as $regulaKey => $regulaValue) {
      switch ($regulaKey) {
        case "required":
          if ($regulaValue) {
            if (!isset($_POST[$numeCamp]) || empty($_POST[$numeCamp])) {
              $_POST["errors"][$numeCamp] =
                "The field " . $numeCamp . " in mandatory.";
              //   errorsLog($_POST["errors"][$numeCamp]);
            }
          }

          break;

        case "length":
          if (strlen($_POST[$numeCamp]) != $regulaValue) {
            $_POST["errors"][$numeCamp] =
              "The field " .
              $numeCamp .
              " must have " .
              $regulaValue .
              " digits.";
          }
          break;

        case "min":
          if (strlen($_POST[$numeCamp]) < $regulaValue) {
            $_POST["errors"][$numeCamp] =
              "The field  " .
              $numeCamp .
              " must have at least " .
              $regulaValue .
              " characters.";
            // errorsLog($_POST["errors"][$numeCamp]);
          }

          break;

        case "max":
          if (strlen($_POST[$numeCamp]) > $regulaValue) {
            $_POST["errors"][$numeCamp] =
              "The field " .
              $numeCamp .
              " must have maximum  " .
              $regulaValue .
              " characters.";
            // errorsLog($_POST["errors"][$numeCamp]);
          }
          break;

        case "upperCase":
          if (!preg_match("@[A-Z]@", $_POST[$numeCamp])) {
            $_POST["errors"][$numeCamp] =
              "Your password must contain at least one uppercase letter.";
            // errorsLog($_POST["errors"][$numeCamp]);
          }
          break;

        case "lowerCase":
          if (!preg_match("@[a-z]@", $_POST[$numeCamp])) {
            $_POST["errors"][$numeCamp] =
              "Your password must contain at least one lowercase letter.";
            // errorsLog($_POST["errors"][$numeCamp]);
          }
          break;

        case "number":
          if (!preg_match("@[0-9]@", $_POST[$numeCamp])) {
            $_POST["errors"][$numeCamp] =
              "Your password must contain at least one digit.";
            // errorsLog($_POST["errors"][$numeCamp]);
          }
          break;

        case "specialChar":
          if (!preg_match("@[^\w]@", $_POST[$numeCamp])) {
            $_POST["errors"][$numeCamp] =
              "Your password must contain at least one special character.";
            // errorsLog($_POST["errors"][$numeCamp]);
          }
          break;

        case "validate":
          if (!filter_var($_POST[$numeCamp], FILTER_VALIDATE_EMAIL)) {
            $_POST["errors"][$numeCamp] =
              "Please provide a valid email address.";
            // errorsLog($_POST["errors"][$numeCamp]);
          }
          break;

        case "passwordMatch":
          //   errorsLog($regulaValue);
          if ($_POST[$numeCamp] != $regulaValue) {
            $_POST["errors"][$numeCamp] = "The two password do not match.";
          }
          break;

        default:
          break;
      }
    }
  }

  if (isset($_POST["errors"]) && !empty($_POST["errors"])) {
    return false;
  }

  return true;
}
