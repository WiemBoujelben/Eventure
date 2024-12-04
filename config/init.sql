CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
	photo varchar(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    age INT,
    role ENUM('user', 'admin', 'superadmin'),
    rating FLOAT DEFAULT 0,
    profile_details TEXT
);
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    photo varchar(255),
    title VARCHAR(255),
    category VARCHAR(255),
    city VARCHAR(255),
    date_time DATETIME,
    description TEXT,
    supervisor_id INT,
    status ENUM('pending', 'approved', 'canceled'),
    FOREIGN KEY (supervisor_id) REFERENCES users(id)
);
CREATE TABLE participants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT,
    user_id INT,
    status ENUM('pending', 'confirmed', 'rejected'),
    FOREIGN KEY (event_id) REFERENCES events(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporter_id INT,
    reported_id INT,
    reason TEXT,
    status ENUM('pending', 'reviewed'),
    FOREIGN KEY (reporter_id) REFERENCES users(id),
    FOREIGN KEY (reported_id) REFERENCES users(id)
);
CREATE TABLE ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT,
    rater_id INT,
    rating FLOAT,
    comment TEXT,
    FOREIGN KEY (event_id) REFERENCES events(id),
    FOREIGN KEY (rater_id) REFERENCES users(id)
);
