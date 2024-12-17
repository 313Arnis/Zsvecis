<?php

include "functions.php";
require "Database.php";

$config = require("config.php");

$db = new Database($config["database"]);

$children = $db->query("SELECT * FROM children")->fetchAll();
$letters = $db->query("SELECT * FROM letters")->fetchAll();
$gifts = $db->query("SELECT * FROM gifts")->fetchAll();
$grades = $db->query("SELECT * FROM grades")->fetchAll();

$giftsNames = array_map(function($gift) {
    return $gift['name'];
}, $gifts);

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
        gap: 20px;
        margin-top: 20px;
    }

    .card {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        width: 320px;
        max-width: 100%;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 5px solid #e74c3c; /* Christmas red border */
        display: flex;
        flex-direction: column;
        padding-bottom: 15px; /* Reduced padding for compactness */
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    }

    /* Card header */
    .card-header {
        background-color: #2ecc71; /* Bright green */
        color: white;
        padding: 15px;
        text-align: center;
        background-image: url('https://www.transparenttextures.com/patterns/dark-mosaic.png');
        background-size: cover;
        border-bottom: 3px solid #27ae60; /* Slight darker green border */
        font-size: 1.1em;
        word-wrap: break-word;
    }

    .blue-query {
        font-weight: bold;
        font-size: 1.1em;
        text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.3);
    }

    /* Card body */
    .card-body {
        padding: 15px;
        font-size: 1em;
        color: #555;
        background-color: #ecf0f1;
        border-top: 2px solid #3498db;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        height: auto; /* Adjust height based on content */
    }

    /* Letter text styling */
    .letter-text {
        background-color: #fffbcc;
        border-left: 5px solid #f39c12;
        padding: 15px;
        margin-top: 10px;
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
        margin-top: 15px;
    }

    .wishlist-title {
        font-size: 1.1em;
        margin-top: 15px;
        font-weight: bold;
        text-decoration: underline;
    }

    .gift-list {
        list-style-type: disc;
        margin-top: 10px;
        margin-left: 20px;
    }

    .low-grade {
        color: red;
    }

    .good-grade {
        color: green;
    }
</style>";

echo "<div class='main-title'>Ziemassvētku vēstules</div>";

echo "<div class='cards-container'>";

foreach ($children as $child) {
    // Aprēķinām bērna vidējo atzīmi no visiem priekšmetiem
    $childGrades = array_filter($grades, function($grade) use ($child) {
        return $grade['student_id'] == $child['id'];
    });

    $totalGrades = 0;
    $gradeCount = count($childGrades);
    foreach ($childGrades as $grade) {
        $totalGrades += $grade['grade'];
    }

    $averageGrade = $gradeCount > 0 ? $totalGrades / $gradeCount : 0;
    $gradeClass = ($averageGrade < 5) ? 'low-grade' : 'good-grade';
    
    echo "<div class='card'>";
    echo "<div class='card-header'>";
    echo "<h3 class='blue-query'>" . $child['firstname'] . " " . $child['middlename'] . " " . $child['surname'] . ", Vecums: " . $child['age'] . " gadi</h3>";
    echo "</div>";
    echo "<div class='card-body'>";

    // Attēlo bērna vidējo atzīmi
    echo "<p class='$gradeClass'>Vidējā atzīme no visiem priekšmetiem: " . number_format($averageGrade, 2) . "</p>";

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

        // Display full wish list of the child (showing gifts that appear in the letter)
        echo "<div class='wishlist-title'>Pilns vēlmes saraksts:</div>";
        echo "<ul class='gift-list'>";
        foreach ($giftsNames as $giftName) {
            if (stripos($childLetter, $giftName) !== false) {
                echo "<li class='" . ($averageGrade < 5 ? 'low-grade' : 'good-grade') . "'>" . $giftName . "</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "<p class='no-letter'>Nav vēstules.</p>";
    }

    echo "</div>";
    echo "</div>";
}

echo "</div>";

?>
