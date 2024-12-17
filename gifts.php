<?php

include "functions.php";
require "Database.php";

$config = require("config.php");

$db = new Database($config["database"]);

$children = $db->query("SELECT * FROM children")->fetchAll();
$letters = $db->query("SELECT * FROM letters")->fetchAll();
$gifts = $db->query("SELECT * FROM gifts")->fetchAll();

// HTML sākuma daļa
echo "<!DOCTYPE html><html lang='lv'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Dāvanu Grāmatvedība</title>";
echo "<style>
    /* Reset pamatstili */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
        color: #333;
        padding: 40px;
        display: flex;
        flex-direction: column;
        align-items: center;
        background-image: url('https://www.transparenttextures.com/patterns/real-wood.png');
        background-size: cover;
    }

    h1 {
        font-size: 2.5em;
        font-weight: bold;
        color: #e74c3c;
        text-align: center;
        margin-bottom: 40px;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
        font-family: 'Georgia', serif;
    }

    ol {
        list-style-type: decimal;
        font-size: 1.2em;
        color: #555;
        width: 80%;
        margin-top: 30px;
    }

    li {
        margin-bottom: 20px;
        padding: 15px;
        background-color: #ecf0f1;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    li:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    /* Ziemassvētku krāsas un dekori */
    .gift-name {
        font-weight: bold;
        color: #e74c3c; /* Sarkans */
    }

    .wish-count {
        font-weight: bold;
        color: #f39c12; /* Dzeltena */
    }

    /* Pārāk daudz vai trūkstošas dāvanas */
    .stock-status-missing {
        font-weight: bold;
        color: red; /* Sarkans */
    }

    .stock-status-excess {
        font-weight: bold;
        color: green; /* Zaļš */
    }

    .stock-status-sufficient {
        font-weight: bold;
        color: black; /* Melns */
    }

</style>";

echo "<body>";

// Galvenais virsraksts
echo "<h1>Dāvanu Grāmatvedība - Ziemassvētki</h1>";

// Izvadām dāvanu sarakstu
echo "<ol>";
foreach ($gifts as $gift) {
    // Skaitām, cik reizes konkrēta dāvana ir minēta vēstulēs
    $wishCount = 0;
    foreach ($letters as $letter) {
        if (stripos($letter['letter_text'], $gift['name']) !== false) {
            $wishCount++;
        }
    }

    // Aprēķinām, vai dāvanas ir pārāk daudz vai trūkst
    if ($gift['count_available'] < $wishCount) {
        $stockStatus = "<span class='stock-status-missing'>Trūkst " . ($wishCount - $gift['count_available']) . " dāvanas.</span>";
    } elseif ($gift['count_available'] > $wishCount) {
        $stockStatus = "<span class='stock-status-excess'>Ir par daudz dāvanas.</span>";
    } else {
        $stockStatus = "<span class='stock-status-sufficient'>Pietiekams daudzums dāvanas.</span>";
    }

    // Izvadām katras dāvanas informāciju
    echo "<li><span class='gift-name'>" . htmlspecialchars($gift['name']) . "</span> - vēlas: <span class='wish-count'>" . $wishCount . " bērni</span>, Noliktavā: " . $gift['count_available'] . " dāvanas. " . $stockStatus . "</li>";
}
echo "</ol>";

echo "</body></html>";

?>
