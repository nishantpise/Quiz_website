-- Create database manually in pgAdmin:
-- CREATE DATABASE quiz_app;

-- Then connect: \c quiz_app;

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE topics (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE questions (
    id SERIAL PRIMARY KEY,
    topic_id INT REFERENCES topics(id) ON DELETE CASCADE,
    question TEXT NOT NULL,
    option_a VARCHAR(255),
    option_b VARCHAR(255),
    option_c VARCHAR(255),
    option_d VARCHAR(255),
    correct_option CHAR(1)
);

-- Insert sample topics
INSERT INTO topics (name) VALUES
('Mathematics'),
('General Knowledge'),
('Science'),
('Programming');
('oops');


-- Insert sample questions
INSERT INTO questions (topic_id, question, option_a, option_b, option_c, option_d, correct_option) VALUES
(1, 'What is 12 + 8?', '18', '20', '22', '24', 'B'),
(1, 'Square root of 81?', '7', '8', '9', '10', 'C'),
(2, 'Capital of Japan?', 'Beijing', 'Tokyo', 'Seoul', 'Bangkok', 'B'),
(3, 'H2O is?', 'Oxygen', 'Hydrogen', 'Water', 'Carbon Dioxide', 'C'),
(4, 'Who developed C language?', 'Bjarne Stroustrup', 'Dennis Ritchie', 'James Gosling', 'Guido van Rossum', 'B');
(5,'which is not the piller of oops?','encapsulation','abstraction','inheritance','compilation','D');