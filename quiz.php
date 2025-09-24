<?php
session_start();
include_once 'db.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

if (!isset($_GET['topic_id']) || !is_numeric($_GET['topic_id'])) {
    header('Location: index.php');
    exit();
}

$topic_id = (int)$_GET['topic_id'];
$num_questions = 10; // Number of questions per quiz

// Fetch questions
$query = "SELECT * FROM questions WHERE topic_id = $1 ORDER BY RANDOM() LIMIT $2";
pg_prepare($conn, "fetch_questions", $query);
$result = pg_execute($conn, "fetch_questions", array($topic_id, $num_questions));

if ($result === false || pg_num_rows($result) == 0) {
    die("Error: No questions found for this topic.");
}

// Fetch topic name for display
$topic_result = pg_query_params($conn, "SELECT name FROM topics WHERE id = $1", array($topic_id));
$topic_name = pg_fetch_assoc($topic_result)['name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Time!</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dark-mode">
    <div class="quiz-container">
        <div class="quiz-header">
            <h1><?php echo htmlspecialchars($topic_name); ?> Quiz</h1>
            <div id="timer">Time Left: 05:00</div>
        </div>
        <form id="quizForm" action="result.php" method="POST">
            <input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">
            <?php
            $qno = 1;
            while ($row = pg_fetch_assoc($result)) {
                $qid = (int)$row['id'];
                $question = htmlspecialchars($row['question']);
                $options = ['A', 'B', 'C', 'D'];
                
                echo "<div class='question'>";
                echo "<h2>Q{$qno}. {$question}</h2>";
                
                // Shuffle options for variation
                shuffle($options);

                foreach ($options as $opt) {
                    $value = strtolower($opt);
                    $option_text = htmlspecialchars($row["option_{$value}"]);
                    $unique_id = "q{$qid}_{$opt}";

                    echo "<div class='option-container'>";
                    echo "<input type='radio' name='answers[{$qid}]' id='{$unique_id}' value='{$opt}' required>";
                    echo "<label for='{$unique_id}'>{$option_text}</label>";
                    echo "</div>";
                }
                echo "</div>";
                $qno++;
            }
            ?>
            <button type="submit" class="btn">Submit Quiz</button>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const timerDisplay = document.getElementById('timer');
        const quizForm = document.getElementById('quizForm');
        let timeLeft = 600; // 5 minutes in seconds

        const timer = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(timer);
                alert('Time is up! Submitting your answers.');
                quizForm.submit();
            } else {
                timeLeft--;
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerDisplay.textContent = `Time Left: ${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }
        }, 1000);
    });
    </script>
</body>
</html>