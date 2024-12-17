<?php

include "functions.php";
require "Database.php";

$config = require("config.php");

$db = new Database($config["database"]);
$gifts = $db->query("SELECT * FROM gifts")->fetchAll();





echo "<ol>";
foreach ($gifts as $gift) {
    echo "<li>" . $gift['name'] . " " . $gift['count_available'].  "</li>";
}
echo "</ol>";
?>
