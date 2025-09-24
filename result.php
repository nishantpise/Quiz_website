<?php
session_start();
include "db.php";

// Redirect if not logged in or no answers submitted
if (!isset($_SESSION['user_id']) || !isset($_POST['answers'])) {
    header('Location: index.php');
    exit();
}

$user_answers = $_POST['answers'];
$topic_id = (int)$_POST['topic_id'];
$question_ids = array_keys($user_answers);
$score = 0;

// --- EFFICIENT QUERY ---
// Fetch all correct answers for the submitted questions in a single query
// --- EFFICIENT & SECURE QUERY ---

// 1. Sanitize the incoming keys to ensure they are all integers.
$question_ids = array_map('intval', array_keys($user_answers));

// 2. Create the correct number of placeholders (e.g., $1, $2, $3) for the query.
$placeholders = implode(',', array_map(fn($n) => '$' . $n, range(1, count($question_ids))));

// 3. Prepare the SQL query using the placeholders.
$query = "SELECT id, question, option_a, option_b, option_c, option_d, correct_option FROM questions WHERE id IN ({$placeholders})";

// 4. Execute the query safely by passing the sanitized IDs as parameters.
$result = pg_query_params($conn, $query, $question_ids);

// --- END QUERY ---

$correct_answers = [];
$questions_data = [];
while ($row = pg_fetch_assoc($result)) {
    $correct_answers[$row['id']] = trim($row['correct_option']);
    $questions_data[$row['id']] = $row;
}
// --- END EFFICIENT QUERY ---

// Calculate score
foreach ($user_answers as $qid => $ans) {
    if (isset($correct_answers[$qid]) && $correct_answers[$qid] == $ans) {
        $score++;
    }
}

// Save result to the database
$total = count($question_ids);
$user_id = $_SESSION['user_id'];
$insert_query = "INSERT INTO quiz_results (user_id, topic_id, score, total_questions) VALUES ($1, $2, $3, $4)";
pg_prepare($conn, "save_result", $insert_query);
pg_execute($conn, "save_result", array($user_id, $topic_id, $score, $total));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Result</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="dark-mode">
    <div class="quiz-container result-container">
        <h1>Your Score: <?php echo $score; ?> / <?php echo $total; ?></h1>
        
        <div class="results-breakdown">
            <h2>Results Breakdown</h2>
            <?php foreach ($questions_data as $qid => $q_data): ?>
                <div class="question-result">
                    <p><strong><?php echo htmlspecialchars($q_data['question']); ?></strong></p>
                    <ul>
                        <?php
                        $user_ans = $user_answers[$qid];
                        $correct_ans = $correct_answers[$qid];
                        
                        foreach (['A', 'B', 'C', 'D'] as $opt) {
                            $option_text = htmlspecialchars($q_data['option_' . strtolower($opt)]);
                            $class = '';
                            if ($opt == $correct_ans) {
                                $class = 'correct';
                            } elseif ($opt == $user_ans && $user_ans != $correct_ans) {
                                $class = 'incorrect';
                            }
                            echo "<li class='{$class}'>{$opt}: {$option_text}</li>";
                        }
                        ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>

        <a href="index.php" class="btn">Try Another Topic</a>
        <a href="profile.php" class="btn">View Your Profile</a>
    </div>
</body>
</html>