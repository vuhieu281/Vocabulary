<?php
// test_search.php - Test search functionality

require_once './config/database.php';
require_once './models/Word.php';

$database = new Database();
$db = $database->getConnection();
$word = new Word($db);

// Test search
$keyword = 'have';
echo "<h1>Search Test for: '$keyword'</h1>";

$results = $word->search($keyword, 10, 0);
echo "<h2>Results (" . count($results) . ")</h2>";
echo "<pre>";
print_r($results);
echo "</pre>";

$total = $word->countSearch($keyword);
echo "<h2>Total Count: $total</h2>";

// Test autocomplete
echo "<h1>Autocomplete Test for: '$keyword'</h1>";
$autocomplete = $word->autocomplete($keyword);
echo "<pre>";
print_r($autocomplete);
echo "</pre>";
?>
