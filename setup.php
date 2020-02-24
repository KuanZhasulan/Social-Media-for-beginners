<?php
require_once 'origin.php';
try
{
$stmt = $chat->exec("CREATE TABLE chat_members(
	memberID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user VARCHAR(16),
	pass VARCHAR(16))");

}
catch(PDOException $e) {
            echo $e->getMessage();
        }
try
{
$stmt2 = $chat->exec("CREATE TABLE chat_friends(
	user VARCHAR(16),
	friend VARCHAR(16))");


}
catch(PDOException $e) {
            echo $e->getMessage();
        }

try
{
$stmt7 = $chat->exec("CREATE TABLE chat_messages(
	          messageID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              auth VARCHAR(16),
              recip VARCHAR(16),
              pm CHAR(1),
              mDate INT UNSIGNED,
              message VARCHAR(4096))");


}
catch(PDOException $e) {
            echo $e->getMessage();
        }
try
{
$stmt8 = $chat->exec("CREATE TABLE chat_profiles(
    occupation VARCHAR(1024),
	user VARCHAR(16),
	name VARCHAR(32),
	content TEXT,
	surname VARCHAR(32),
    age INT)");

}
catch(PDOException $e) {
            echo $e->getMessage();
        }