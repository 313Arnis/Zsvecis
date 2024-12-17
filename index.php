<?php

include "functions.php";
require "Database.php";

$config = require("config.php");

$db = new Database($config["database"]);
$posts = $db->query("SELECT * FROM posts")->fetchAll();

echo "<ul>";
foreach ($posts as $post) {
    echo "<li>" . "{$post['content']}". "</li>";  
}
echo "</ul>"; 