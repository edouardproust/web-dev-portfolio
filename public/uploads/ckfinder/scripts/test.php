<?php

$name = 'David';
$limit = 1;
// Prepare query
$stmt = $mysqli->prepare('SELECT age, address FROM students WHERE name = ? LIMIT ?');
// data types: i = integer, s = string, d = double, b = blog 
$stmt->bind_param('si', $name, $limit);
// Execute query
$stmt->execute();
// Bind the result
$stmt->bind_result($age, address);
