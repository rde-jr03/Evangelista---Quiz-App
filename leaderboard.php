<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }
        .container {
            text-align: center;
        }
        table {
            margin: 20px auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px 12px;
        }
    </style>
</head>
<body>
    <!-- dito po nakalagay yung php sa loob ng html. -->
    <div class="container">
        <?php
        // meron din pong connection sa database, kinukuha din yung servername, username, password, at
        // pangalan ng database.
        $conn = new mysqli("localhost", "root", "root", "quiz_db");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        // sql query na kinukuha yung top 10 na scores. naka desc so ang pinakamataas ay ang nasa taas.
        // kapag may kaparehong scores, ang basehan ay kung gaano kabilis nya natapos ang quiz.
        $query = "SELECT user_id, score, DATE(timestamp) AS quiz_date, TIME(timestamp) AS quiz_time 
                  FROM user_scores 
                  ORDER BY score DESC, timestamp ASC 
                  LIMIT 10";
        $result = $conn->query($query);

        echo "<h1>TOP 10 PLAYERS</h1>";
        if ($result->num_rows > 0) { //kapag merong one or more na rows ang na return magkakaroon ng table. 
            echo "<table>
                    <tr>
                        <th>User</th>
                        <th>Score</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['user_id']}</td>
                        <td>{$row['score']}</td>
                        <td>{$row['quiz_date']}</td>
                        <td>{$row['quiz_time']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No scores yet.</p>";
        }
        ?>
        <!-- link if gusto mag take ulit ng quiz as another user at link din para malaman kung
         naka take na si user ng quiz. -->
        <div>
            <a href="index.php">Take Quiz Again</a> <BR> <BR>  
            <a href="login.php">Take Quiz as Another User</a>
        </div>
    </div>
</body>
</html>
