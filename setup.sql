CREATE DATABASE IF NOT EXISTS workshop_db;
USE workshop_db;

CREATE TABLE IF NOT EXISTS registrations (
    id       INT PRIMARY KEY AUTO_INCREMENT,
    name     VARCHAR(100)  NOT NULL,
    email    VARCHAR(100)  NOT NULL,
    age      INT,
    course   VARCHAR(100),
    gender   VARCHAR(10),
    hobbies  VARCHAR(255),
    status   VARCHAR(20)   DEFAULT 'active'
);

INSERT INTO registrations (name, email, age, course, gender, hobbies, status) VALUES
('Ahmad Faris',    'faris@utp.edu.my',   21, 'Computer Science',      'Male',   'Coding, Gaming',        'active'),
('Nurul Izzati',   'izzati@utp.edu.my',  20, 'Information Technology','Female', 'Reading, Photography',  'active'),
('Haziq Danial',   'haziq@utp.edu.my',   22, 'Software Engineering',  'Male',   'Sports, Music',         'active'),
('Siti Aisyah',    'aisyah@utp.edu.my',  21, 'Data Science',          'Female', 'Travelling, Reading',   'active'),
('Muhammad Arif',  'arif@utp.edu.my',    23, 'Electrical Engineering','Male',   'Gaming, Coding',        'deleted');
