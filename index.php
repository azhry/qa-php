<?php 

require_once 'DocumentRetriever.php';
require_once 'QuestionAnalyzer.php';

// $query 		= 'life learning';
// $directory 	= 'document/';
// $retriever = new DocumentRetriever($query, $directory);
// $retriever->createIndex();
// print_r($retriever->retrieve());

$query = 'apa itu komputer';
$questionAnalyzer = new QuestionAnalyzer();
echo $query . " -> " . $questionAnalyzer->getEAT($query) . "\n";
echo "Kata kunci: " . implode(',', $questionAnalyzer->getKeywords($query)) . "\n";

$query = 'siapa yang paling ganteng';
$questionAnalyzer = new QuestionAnalyzer();
echo $query . " -> " . $questionAnalyzer->getEAT($query) . "\n";
echo "Kata kunci: " . implode(',', $questionAnalyzer->getKeywords($query)) . "\n";

$query = 'mengapa azhary ganteng';
$questionAnalyzer = new QuestionAnalyzer();
echo $query . " -> " . $questionAnalyzer->getEAT($query) . "\n";
echo "Kata kunci: " . implode(',', $questionAnalyzer->getKeywords($query)) . "\n";

$query = 'dimanakah dia sekarang';
$questionAnalyzer = new QuestionAnalyzer();
echo $query . " -> " . $questionAnalyzer->getEAT($query) . "\n";
echo "Kata kunci: " . implode(',', $questionAnalyzer->getKeywords($query)) . "\n";

$query = 'berapa tingkat kegantengan azhary';
$questionAnalyzer = new QuestionAnalyzer();
echo $query . " -> " . $questionAnalyzer->getEAT($query) . "\n";
echo "Kata kunci: " . implode(',', $questionAnalyzer->getKeywords($query)) . "\n";