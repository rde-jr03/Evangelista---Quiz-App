<?php

session_start(); //variable para magamit yung ibang session na variable sa buong script. 

// connection sa database, kinukuha yung servername, username, password, at pangalan ng database. 
// kapag connection failed may lalabas na error message. 
$conn = new mysqli("localhost", "root", "root", "quiz_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// chini-check if yung user_id is naka set na if hindi pa mag di-direct sa log in na php.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
} 

// dito nakalagay yung title ng quiz, yung mga questions, options at pati ang mga answers.
$quiz_title = "PHP QUIZ";
$questions = [
    [
        "question" => "1. What does PHP stand for?",
        "options" => ["Private Home Page", "Personal Hypertext Processor", "PHP: Hypertext Processor", "Philippines"],
        "answer" => 2
    ],
    [  
        "question" => "2. All variables in PHP start with which symbol?",
        "options" => ["!", "$", "&", "#"],
        "answer" => 1
    ],
    [
        "question" => "3. How do you write 'Hello World' in PHP",
        "options" => ["'Hello World';", "Document.Write('Hello World');", "echo 'Hello World';", "'Hellow Poe';"],
        "answer" => 0
    ],
    [
        "question" => "4. In PHP in order to access MySQL database you will use:",
        "options" => ["'Mysql-connect() function';", "Mysqlconnect() function", "Sql_connect() function", "Mysql_connect() function"],
        "answer" => 3
    ],
    [
        "question" => "5. PHP is Scripting language",
        "options" => ["Client Side", "Home Side", "Browser Side", "Server Side"],
        "answer" => 3
    ]
];

$score = 0;
$user_id = $_SESSION['user_id'];

//chinicheck if nakapag take na ng quiz si user gamit ang user_id, (ginagamit ang user_id para icheck sa 
//user_scores kung nakapag take na ng quiz)
$query = $conn->prepare("SELECT * FROM user_scores WHERE user_id = ?");
$query->bind_param("s", $user_id);
$query->execute();
$result = $query->get_result();

//kapag nakapag take na ng quiz, may lalabas na message "You have already take this quiz (name ng user)".
//tapos sa baba may link ng leaderboard.
if ($result->num_rows > 0) {
    echo "<h2>You have already taken this quiz, $user_id!</h2>";
    echo "<a href='leaderboard.php'>View Leaderboard</a>";
    exit;
}

//dito chinicheck yung mga sagot ng user kung ilan ang tama, at tsaka sina-save sa database.
// tapos lalabas o mag di-display yung score sa baba may link din ng leaderboard.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($questions as $index => $question) {
        if (isset($_POST["questions$index"]) && $_POST["questions$index"] == $question["answer"]) {
            $score++;
        }
    }

    $sqlquery = $conn->prepare("INSERT INTO user_scores (user_id, score, quiz_title, timestamp) VALUES (?, ?, ?, NOW())");
    $sqlquery->bind_param("sis", $user_id, $score, $quiz_title);
    $sqlquery->execute();

    echo "<h2>Your score: $score / " . count($questions) . "</h2>";
    echo "<a href='leaderboard.php'>View Leaderboard</a>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $quiz_title; ?></title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            text-align: center;
        }
        fieldset {
            text-align: left;
        }
        legend {
            font-weight: bold;
        }
</style>
</head>
<body>
    <div class="container">
        <h1><?php echo $quiz_title; ?></h1>
        <form action="" method="post">
            <!-- looping ng mga questions na may radio button na options. -->
            <?php foreach ($questions as $index => $question): ?> <BR>
                <fieldset>
                    <legend><?php echo $question['question']; ?></legend>
                    <?php foreach ($question['options'] as $optionIndex => $option): ?>
                        <label>
                            <input type="radio" name="questions<?php echo $index; ?>" value="<?php echo $optionIndex; ?>">
                            <?php echo $option; ?>
                        </label><br>
                    <?php endforeach; ?>
                </fieldset>
            <?php endforeach; ?>
            <br>
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>

<!-- NEXT PO IS YUNG LOG IN -->
