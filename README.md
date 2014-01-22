SimpleCMS
=========

A really simple Content Management System developed with Zend2

This is just a small project used to improve my skills, and it is not to be
used in a commercial environment.

==Description
This project was developed using ZendStudio and it is based on the "Skeleton Application".

It was used Zend Fraework 2.2.4

It was tested on a Ubuntu 12.04, with Apache 2.2.22, PHP 5.3.10 and MySQL 5.5.34. 

==
Running:

Download the source:
git clone https://github.com/henriquetb/SimpleCMS.git

Add an Apache VirtualHost to the sites config file.
<VirtualHost *:80>
    ServerName simplecms.local
    DocumentRoot my/project/dir/public
    SetEnv APPLICATION_ENV "development" // make sure to set the application environment
</VirtualHost>

You may need to change the hosts file to add the server name described above. Add the following line:
127.0.0.1 simplecms.local

Create the database and the table (project/data/simplecms.sql)

Change the file /config/autoload/global.php in the line:
'dsn'            => 'mysql:dbname=simplecms;host=localhost',
Change "simplecms" the DB name you created.

Change the file /config/autoload/local.php
'username' => 'Your_User_Name',
'password' => 'Your_Password',
Inserting your user name and password to connect to the DB.

It should work.

