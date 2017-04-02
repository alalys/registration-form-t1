<?php
try {
    $db = new PDO(
      'mysql:host=localhost;
       dbname=reg-task',
      'root',
      ''
    );
} catch (Exception $e) {
    echo $e->getMessage();
}
?>