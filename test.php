<?php
include "db.php";

$result = pg_query($conn, "SELECT * FROM questions LIMIT 5");

while ($row = pg_fetch_assoc($result)) {
    echo $row['question'] . "<br>";
}
?>
