<?php

$file = file_get_contents("C:/scale/weight.txt");

$lines = explode("\n", trim($file));

$last = end($lines);

echo json_encode([
    'weight' => trim($last)
]);