<?php

include "functions.php";
require "Database.php";

$config = require("config.php");

$db = new Database($config["database"]);

// Iegūstam visus bērnus un vēstules
$children = $db->query("SELECT * FROM children")->fetchAll();
$letters = $db->query("SELECT * FROM letters")->fetchAll();

// Izveidojam kartiņas HTML
echo "<style>
    /* Basic Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
        color: #333;
        padding: 30px;
        display: flex;
        flex-direction: column;
        align-items: center;
        background-image: url('https://www.transparenttextures.com/patterns/real-wood.png');
        background-size: cover;
    }

    /* Title styling */
    .main-title {
        font-size: 2.5em;
        font-weight: bold;
        color: #e74c3c; /* Red color for Christmas theme */
        text-align: center;
        margin-bottom: 40px;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
        font-family: 'Georgia', serif;
    }

    .cards-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
        margin-top: 40px;
    }

    .card {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        width: 320px;
        max-height: 700px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 5px solid #e74c3c; /* Christmas red border */
        display: flex;
        flex-direction: column;
        padding-bottom: 20px;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    }

    /* Card header (with Christmas color scheme) */
    .card-header {
        background-color: #2ecc71; /* Bright green */
        color: white;
        padding: 20px;
        text-align: center;
        background-image: url('https://www.transparenttextures.com/patterns/dark-mosaic.png');
        background-size: cover;
        border-bottom: 3px solid #27ae60; /* Slight darker green border */
    }

    .blue-query {
        font-weight: bold;
        font-size: 1.1em;
        text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Card body */
    .card-body {
        padding: 20px;
        font-size: 1.1em;
        color: #555;
        background-color: #ecf0f1;
        border-top: 2px solid #3498db;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        min-height: 150px;
    }

    /* Letter text styling */
    .letter-text {
        background-color: #fffbcc;
        border-left: 5px solid #f39c12;
        padding: 15px;
        margin-top: 20px;
        font-style: italic;
        font-size: 1em;
        color: #333;
        border-radius: 5px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .no-letter {
        color: #e74c3c;
        font-weight: bold;
        text-align: center;
        font-size: 1.2em;
    }

    /* Snowflake animation */
    @keyframes snowflakes {
        0% {
            transform: translateY(-100px);
        }
        100% {
            transform: translateY(100vh);
        }
    }

    /* Snowflakes effect */
    .snowflake {
        position: absolute;
        top: -50px;
        font-size: 30px;
        color: white;
        animation: snowflakes 12s linear infinite;
        z-index: 999;
    }

    .snowflake:nth-child(1) {
        left: 10%;
        animation-duration: 15s;
        animation-delay: 0s;
    }

    .snowflake:nth-child(2) {
        left: 25%;
        animation-duration: 18s;
        animation-delay: 3s;
    }

    .snowflake:nth-child(3) {
        left: 40%;
        animation-duration: 16s;
        animation-delay: 2s;
    }

    .snowflake:nth-child(4) {
        left: 55%;
        animation-duration: 20s;
        animation-delay: 5s;
    }

    .snowflake:nth-child(5) {
        left: 70%;
        animation-duration: 25s;
        animation-delay: 7s;
    }

    /* Christmas tree background */
    .tree-background {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        z-index: -1;
        width: 100%;
        height: 250px;
        background-image: url('https://image.shutterstock.com/image-photo/3d-illustration-christmas-tree-over-260nw-1562906886.jpg');
        background-size: cover;
        background-position: center;
    }

    @media (max-width: 768px) {
        .card {
            width: 100%;
            max-width: 350px;
        }
    }
</style>";

echo "<div class='main-title'>Ziemassvētku vēstules</div>"; // Add main title

echo "<div class='cards-container'>";

// Snowflake elements
for ($i = 0; $i < 5; $i++) {
    echo "<div class='snowflake'>&#10052;</div>";
}

foreach ($children as $child) {
    echo "<div class='card'>";
    echo "<div class='card-header'>";
    echo "<h3 class='blue-query'>" . $child['firstname'] . " " . $child['middlename'] . " " . $child['surname'] . ", Vecums: " . $child['age'] . " gadi</h3>";
    echo "</div>";
    echo "<div class='card-body'>";

    // Find the child's letter
    $childLetter = null;
    foreach ($letters as $letter) {
        if ($letter['sender_id'] == $child['id']) {
            $childLetter = $letter['letter_text'];
            break;
        }
    }

    // If letter found, display its text, otherwise display "No letter" message
    if ($childLetter) {
        echo "<p class='letter-text'>" . htmlspecialchars($childLetter) . "</p>";
    } else {
        echo "<p class='no-letter'>Nav vēstules.</p>";
    }

    echo "</div>";
    echo "</div>";
}

echo "</div>";
echo "<div class='tree-background'></div>";

?>
