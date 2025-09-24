# PHP & PostgreSQL Quiz Website 🎯

A dynamic, web-based quiz application built with PHP and a PostgreSQL backend. This project allows users to test their knowledge on various topics, track their scores, and view their quiz history.

## ✨ Features

* **User Authentication**: Secure login and logout functionality.
* **Topic Selection**: Users can choose from a variety of quiz topics.
* **Timed Quizzes**: Each quiz has a 5-minute timer to challenge the user.
* **Dynamic Questions**: Questions and options are fetched dynamically from the PostgreSQL database.
* **Instant Results**: View your score and a detailed answer breakdown immediately after submission.
* **Quiz History**: A personal profile page that displays a history of all quizzes taken, including scores and dates.
* **Responsive Design**: A clean, dark-mode interface that works on both desktop and mobile devices.

## 🛠️ Tech Stack

* **Backend**: PHP
* **Database**: PostgreSQL
* **Frontend**: HTML5, CSS3, JavaScript (for the timer)
* **Web Server**: Apache (recommended)

## 🚀 Getting Started (Local Setup)

To get a local copy up and running, follow these steps.

### Prerequisites

* A local web server environment (like XAMPP, WAMP, or MAMP with PostgreSQL).
* PHP installed.
* PostgreSQL server installed.
* Git command-line tool.

### Installation Steps

1.  **Clone the repository:**
    ```bash
    git clone [https://github.com/nishantpise/Quiz_website.git](https://github.com/nishantpise/Quiz_website.git)
    cd Quiz_website
    ```

2.  **Set up the database:**
    * Log in to your PostgreSQL instance.
    * Create a new database and a user.
    ```sql
    CREATE DATABASE quiz_app;
    CREATE USER myuser WITH PASSWORD 'mypassword';
    GRANT ALL PRIVILEGES ON DATABASE quiz_app TO myuser;
    ```

3.  **Import the sample data:**
    Use the `sample_data.sql` file to create the necessary tables and insert sample questions.
    ```bash
    psql -U myuser -d quiz_app -h localhost -f sample_data.sql
    ```

4.  **Configure the database connection:**
    * Open the `db.php` file.
    * Update the credentials to match the database you created in step 2.
    ```php
    <?php
    $host = "localhost";
    $port = "5432";
    $dbname = "quiz_app";
    $user = "myuser";       // Your database username
    $password = "mypassword"; // Your database password

    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
    // ...
    ?>
    ```

5.  **Run the application:**
    Place the project folder in your web server's root directory (e.g., `htdocs` for XAMPP) and navigate to `http://localhost/Quiz_website` in your browser.

## 📂 File Structure
