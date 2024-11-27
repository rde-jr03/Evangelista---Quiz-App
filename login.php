<?php
session_start();

//dito naman kapag wala pang nalalagay na user_id si user mayroong lalabas na error message.
//kapag nakapag input na si user ng name nya, ma di-direct na sya sa index.php (na kung saan nandoon ang quiz.)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = ($_POST['user_id']);

    if (!empty($user_id)) {
        $_SESSION['user_id'] = $user_id;
        header("Location: index.php");
        exit;
    } else {
        $error = "Please enter your name.";
    }
}
?>

<!-- log in form gamit ang html at css -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        form {
            margin-top: 10px;
        }
        input, button {
            padding: 8px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>LOG IN</h1>
        <form method="post">
            <label for="user_id">Enter your name here:</label><br>
            <input type="text" id="user_id" name="user_id" required><br>
            <button type="submit">Start Quiz</button>
        </form>
        <?php if (isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>

<!-- NEXT PO IS YUNG LEADERBOARD. -->