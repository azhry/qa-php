<?php 

require_once __DIR__ . '/vendor/autoload.php';

class Preprocessor
{
	private $stopwordList = ['dan', 'di', 'ke', 'untuk', 'dengan', 'yaitu', 'itu', 'ini', 'yang'];

	public function tokenize($str)
	{
		return explode(' ', $str);
	}

	public function casefold($str)
	{
		return strtolower($str);
	}

	public function stem($str)
	{
		$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
		$stemmer = $stemmerFactory->createStemmer();
		return $stemmer->stem($str);
	}

	public function stopword_removal($strList)
	{
		return array_diff($strList, $this->stopwordList);
	}
}