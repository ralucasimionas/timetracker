<?php

$registerValidator = [
  "name" => ["required" => true],

  "mobile" => ["required" => true, "length" => 10],

  "email" => ["required" => true, "validate" => true],

  "password" => [
    "required" => true,
    "min" => "6",
    "max" => "20",
    "lowerCase" => true,
    "upperCase" => true,
    "number" => true,
    "specialChar" => true,
  ],
  "repeat_password" => [
    "required" => true,
    "passwordMatch" => $_POST["password"],
  ],
];

$loginValidator = [
  "email" => ["required" => true, "validate" => true],
  "password" => ["required" => true],
];

$updateValidator = [
  "name" => ["required" => true],

  "mobile" => ["required" => true, "length" => 10],

  "email" => ["required" => true, "validate" => true],
];

$updatePassValidator = [
  "password" => [
    "required" => true,
    "min" => "6",
    "max" => "20",
    "lowerCase" => true,
    "upperCase" => true,
    "number" => true,
    "specialChar" => true,
  ],
  "repeat_password" => [
    "required" => true,
    "passwordMatch" => $_POST["password"],
  ],
];

$taskFinishedValidator = [
  "date_finish" => ["required" => true],
  "task_hours" => ["required" => true],
];
?>
