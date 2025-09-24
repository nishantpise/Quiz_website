<?php
session_start();
include_once 'db.php';

// If user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch quiz history for the logged-in user
$query = "
    SELECT r.score, r.total_questions, r.quiz_date, t.name as topic_name
    FROM quiz_results r
    JOIN topics t ON r.topic_id = t.id
    WHERE r.user_id = $1
    ORDER BY r.quiz_date DESC
";
pg_prepare($conn, "fetch_history", $query);
$result = pg_execute($conn, "fetch_history", array($user_id));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dark-mode">
    <div class="quiz-container">
        <div class="header-nav">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <a href="auth/logout.php" class="btn-logout">Logout</a>
        </div>
        <h2>Your Quiz History</h2>
        <div class="history-table">
            <?php if (pg_num_rows($result) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Topic</th>
                            <th>Score</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = pg_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['topic_name']); ?></td>
                                <td><?php echo $row['score']; ?> / <?php echo $row['total_questions']; ?></td>
                                <td><?php echo date('M d, Y - h:i A', strtotime($row['quiz_date'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You haven't completed any quizzes yet. <a href="index.php">Start one now!</a></p>
            <?php endif; ?>
        </div>
        <a href="index.php" class="btn" style="margin-top: 20px;">Back to Home</a>
    </div>
</body>
</html>