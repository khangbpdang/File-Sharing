# Interactive File Sharing Website
### Contributed by Nurul Haque, Khang Dang and Lawrence DeAngelo

This website serves as a common ground for showcasing users' files. Currently supported file formats are PDF, PNG, JPG, TXT, DOCX and MP3.

# Database Setup
## Table creation
### Create user management table
```mysql
CREATE TABLE users (
	username varchar(50) NOT NULL PRIMARY KEY,
	password varchar(50) NOT NULL,
	email varchar(50) NOT NULL,
	name varchar(50) NOT NULL,
	prof_name_hash VARCHAR(50), 
	prof_file_type VARCHAR(10)
);
```

### Create file management table
```mysql
CREATE TABLE files (
	file_id INT(6) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	file_hash VARCHAR(50) NOT NULL,
	file_name VARCHAR(255) NOT NULL,
	username VARCHAR(255) NOT NULL,
	file_type VARCHAR(10) NOT NULL,
	size INT(8) NOT NULL,
	file_desc VARCHAR(5000),
	dt_uploaded DATETIME NOT NULL,
	downloads INT UNSIGNED,
	filesound VARCHAR(5000) NOT NULL
);
```

### Create followers table
```mysql
CREATE TABLE follow (
	follow_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	sender_id VARCHAR(50) NOT NULL,
	receiver_id VARCHAR(50) NOT NULL
);
```

### Create comment section
```mysql
CREATE TABLE comment (
	comment_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(50) NOT NULL,
	comment VARCHAR(10000) NOT NULL,
	comment_dt DATETIME NOT NULL,
	file_id INT(6) UNSIGNED NOT NULL
);
```

## SELECT queries for various page (also available in the file themselves)
### Select comments for filepage.php
```mysql
SELECT * FROM (select 
    comment.*, users.prof_name_hash, users.prof_file_type
from (SELECT username FROM comment UNION 
      SELECT username FROM users
     ) n 
     LEFT JOIN
     comment
     ON comment.username = n.username LEFT JOIN
     users
     ON users.username = n.username) m where file_id = <insert variable>;
```

### Select files for following.php | receiver_id
```mysql
SELECT * FROM (SELECT files.*, follow.sender_id from (SELECT receiver_id FROM follow where sender_id ='<insert variable>') n 
	LEFT JOIN 
	follow 
	ON follow.receiver_id = n.receiver_id LEFT JOIN
	files
	ON files.username = n.receiver_id) m ORDER BY dt_uploaded DESC;
```

### Select follow for following.php
```mysql
SELECT * FROM (SELECT users.* from (SELECT receiver_id FROM follow WHERE sender_id = '<insert variable>') n 
	LEFT JOIN
	users
	ON users.username = n.receiver_id
	LEFT JOIN
	follow
	ON follow.receiver_id = n.receiver_id AND follow.sender_id = '<insert variable>') m;
```

## Server configurations
### Server permissions for directory upload. User group and name for the web server will depend on the OS. This following code should work for Ubuntu and Debian. 
```
chown -R www-data:www-data <insert directory name>
```
### In addition, make sure to check php.ini on the server and configure these settings appropriately to enable file upload via PHP
```ini
    file_uploads
    upload_max_filesize
    max_input_time
    memory_limit
    max_execution_time
    post_max_size
```
