<?php 

require_once 'DocumentRetriever.php';
require_once 'QuestionAnalyzer.php';
require_once 'AnswerFinder.php';

// $query 		= 'sel';
// $directory 	= 'document/';
// $retriever = new DocumentRetriever($query, $directory);
// $retriever->createIndex();
// print_r($retriever->retrieve());

$query = 'apa itu sel';
$questionAnalyzer = new QuestionAnalyzer();
echo $query . " -> " . $questionAnalyzer->getEAT($query) . "\n";
echo "Kata kunci: " . implode(',', $questionAnalyzer->getKeywords($query)) . "\n";

$directory = 'document/';
$documentRetriever = new DocumentRetriever($query, $directory);
$documentRetriever->createIndex();
$retrieved = $documentRetriever->retrieve();

$answerFinder = new AnswerFinder($query, $questionAnalyzer->getEAT($query), $questionAnalyzer->getKeywords($query), array_keys($retrieved), $directory);
print_r($answerFinder->getAnswer());

// $query = 'siapa yang paling ganteng';
// $questionAnalyzer = new QuestionAnalyzer();
// echo $query . " -> " . $questionAnalyzer->getEAT($query) . "\n";
// echo "Kata kunci: " . implode(',', $questionAnalyzer->getKeywords($query)) . "\n";

// $query = 'mengapa azhary ganteng';
// $questionAnalyzer = new QuestionAnalyzer();
// echo $query . " -> " . $questionAnalyzer->getEAT($query) . "\n";
// echo "Kata kunci: " . implode(',', $questionAnalyzer->getKeywords($query)) . "\n";

// $query = 'dimanakah dia sekarang';
// $questionAnalyzer = new QuestionAnalyzer();
// echo $query . " -> " . $questionAnalyzer->getEAT($query) . "\n";
// echo "Kata kunci: " . implode(',', $questionAnalyzer->getKeywords($query)) . "\n";

// $query = 'berapa tingkat kegantengan azhary';
// $questionAnalyzer = new QuestionAnalyzer();
// echo $query . " -> " . $questionAnalyzer->getEAT($query) . "\n";
// echo "Kata kunci: " . implode(',', $questionAnalyzer->getKeywords($query)) . "\n";