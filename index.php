<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include("db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Quiz App</title>
  <link rel="stylesheet" href="style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> </head>
<body class="dark-mode">
  <div class="quiz-container">
    <div class="header-nav">
        <h1>🎯 Online Quiz</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <div>
                <a href="profile.php" class="btn-nav">Profile</a>
                <a href="auth/logout.php" class="btn-nav">Logout</a>
            </div>
        <?php else: ?>
            <a href="auth/login.php" class="btn-nav">Login</a>
        <?php endif; ?>
    </div>
    
    <form action="quiz.php" method="GET" class="topic-form">
      <label for="topic-select">Select a Topic to Begin:</label>
      <select name="topic_id" id="topic-select" required>
        <option value="" disabled selected>-- Choose a topic --</option>
        <?php
        $topics = pg_query($conn, "SELECT * FROM topics ORDER BY name");
        while ($row = pg_fetch_assoc($topics)) {
            $topic_id = htmlspecialchars($row['id']);
            $topic_name = htmlspecialchars($row['name']);
            echo "<option value='{$topic_id}'>{$topic_name}</option>";
        }
        ?>
      </select>
      <button type="submit" class="btn">Start Quiz 🚀</button>
    </form>
  </div>
</body>
</html>