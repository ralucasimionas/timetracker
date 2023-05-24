<?php

function generateInput($type, $name, $placeholder = "")
{
  $nameValue =
    isset($_POST[$name]) && !empty($_POST[$name]) ? $_POST[$name] : "";

  $input =
    '<input type="' .
    $type .
    '" name="' .
    $name .
    '" value="' .
    $nameValue .
    '" placeholder="' .
    $placeholder .
    '"/> <br>';

  if (isset($_POST["errors"][$name]) && !empty($_POST["errors"][$name])) {
    $input .= '<p style="color:red">' . $_POST["errors"][$name] . "</p>";
  }

  echo $input;
}
