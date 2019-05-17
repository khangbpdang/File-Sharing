# Interactive File Sharing Website
### Contributed by Nurul Haque, Khang Dang and Lawrence DeAngelo

This website serves as a hub for showcasing individual users' files. Currently supported file formats are PDF, PNG, JPG, TXT, DOCX and MP3, but it can be expanded by changes various settings.

## Project Specifications and Setup
1. **Languages:** 
   - Backend: 
     - PHP 7.0 or above: 
       - For Ubuntu: https://www.vultr.com/docs/configure-php-7-2-on-ubuntu-18-04
       - For Debian: https://www.rosehosting.com/blog/how-to-install-php-7-2-on-debian-9/
       - For MacOS:  https://tecadmin.net/install-php-macos/
     - MySQL:
       - For Ubuntu: https://support.rackspace.com/how-to/installing-mysql-server-on-ubuntu/
       - For Debian: https://www.digitalocean.com/community/tutorials/how-to-install-the-latest-mysql-on-debian-9
       - For MacOS:  https://tableplus.io/blog/2018/11/how-to-download-mysql-mac.html  
   - Frontend: CSS, HTML5, JavaScript, Bootstrap.
   
2. **Tested OS environment:**
   - Linux: Debian 9 or above, Ubuntu 18 or above.
   - MacOS: Mojave.
   
3. **Web server:**
   - Apache web server: https://www.digitalocean.com/community/tutorials/how-to-install-the-apache-web-server-on-debian-9   
   
   
## Database Setup
#### Table creation
##### Create user management table
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

##### Create file management table
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

##### Create followers table
```mysql
CREATE TABLE follow (
	follow_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	sender_id VARCHAR(50) NOT NULL,
	receiver_id VARCHAR(50) NOT NULL
);
```

##### Create comment section
```mysql
CREATE TABLE comment (
	comment_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(50) NOT NULL,
	comment VARCHAR(10000) NOT NULL,
	comment_dt DATETIME NOT NULL,
	file_id INT(6) UNSIGNED NOT NULL
);
```

#### SELECT queries for various pages (also available in the file themselves)
##### Select comments for filepage.php
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

##### Select files for following.php | receiver_id
```mysql
SELECT * FROM (SELECT files.*, follow.sender_id from (SELECT receiver_id FROM follow where sender_id ='<insert variable>') n 
	LEFT JOIN 
	follow 
	ON follow.receiver_id = n.receiver_id LEFT JOIN
	files
	ON files.username = n.receiver_id) m ORDER BY dt_uploaded DESC;
```

##### Select follow for following.php
```mysql
SELECT * FROM (SELECT users.* from (SELECT receiver_id FROM follow WHERE sender_id = '<insert variable>') n 
	LEFT JOIN
	users
	ON users.username = n.receiver_id
	LEFT JOIN
	follow
	ON follow.receiver_id = n.receiver_id AND follow.sender_id = '<insert variable>') m;
```


## Server Configurations
#### Server permissions for directory upload. User group and name for the web server will depend on the OS. This following code should work for Ubuntu and Debian. 
```shell
chown -R www-data:www-data <insert directory name>
```

#### In addition, make sure to check php.ini on the server and configure these settings appropriately to enable file upload via PHP
```ini
file_uploads
upload_max_filesize
max_input_time
memory_limit
max_execution_time
post_max_size
```

## File Support
#### Adding support for more file types
1. **Altering codes in filesLogic.php, particularly this line of code to allow for additional file type to be uploaded onto the server**

**_BE CAREFUL_** and think hard about what kind of file should be allowed to be uploaded onto the server though.
```php
!in_array($extension, ['txt', 'jpg', 'png', 'mp3', 'pdf', 'docx'])
```

2. **Adding file posts display in homepage_2.php, following.php, userpage2.php, filepage.php**

Each of these file spawn file posts in a **foreach** statement with a **switch** statement for the file type similar to what is shown below. Just add another ```php case "<insert file type>"``` for the added file type(s).
```php
foreach ($files as $file):
	switch($file['file_type']):
		case "docx":
```

3. **Filtering files in homepage_2.php, following.php, userpage2.php**

Each page has a filter section that utilize JQuery in order to filter files according their respective categories. Currently, there are 4 categories: All, Audio (MP3), Documents (TXT, DOCX, PDF), and Images (JPG, PNG).

Each post of a certain file type will have an _HTML_ class name associated to it which corresponds to the category. For example:
```html
case "png":
	<div class="column images col-lg-6 col-md-6">
		
case "txt":
	<div class="column documents col-lg-6 col-md-6">
```
The _PNG_ file post will have **images** as a part of its class name while the _TXT_ file post will have **documents**. The reason for that is due to the codes below

```html
<h4 class="title">Post Categories</h4>
		<div id="myBtnContainer">
			<button class="btncust active" onclick="filterSelection('all')"> <p class="p1"><img src="img/bullet.png" alt=""> All</p></button> <br>
			<button class="btncust" onclick="filterSelection('audio')"><p class="p1"><img src="img/bullet.png" alt=""> Audio</p></button> <br>
			<button class="btncust" onclick="filterSelection('documents')"> <p class="p1"><img src="img/bullet.png" alt=""> Documents</p></button> <br>
			<button class="btncust" onclick="filterSelection('images')"> <p class="p1"><img src="img/bullet.png" alt=""> Images</p></button> <br>

		</div>
```
Each button on the webpage are for filtering file categories based on the _HTML_ class name. Therefore, you can have the choice to add more categories, set it as your new class name and add a new filter button, or use one of the existing categories for your file type(s). 
