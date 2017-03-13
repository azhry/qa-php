<?php 

require_once 'Preprocessor.php';

class AnswerFinder
{
	private $directory;
	private $EAT;
	private $query;
	private $keywords;
	private $preprocessor;
	private $listdir;

	public function __construct($query, $EAT, $keywords, $directory)
	{
		$this->preprocessor = new Preprocessor();
		$this->directory 	= $directory;
		$this->listdir 		= scandir($this->directory);
		$this->listdir 		= array_diff($this->listdir, ['.', '..']);
		$this->EAT 			= $EAT;
		$this->query 		= $this->preprocessor->casefold($query);
	}

	private function findWord($sentence, $words)
	{
		foreach ($words as $word)
		{
			if (in_array($word, $sentence))
				return TRUE;
		}

		return FALSE;
	}

	public function getAnswer()
	{
		$clueWords 			= '';
		$wordBeforeClueWord = [];
		$wordAfterClueWord 	= [];

		switch ($this->EAT)
		{
			case 'definition':
				$clueWords = 'definition';
				break;
			case 'reason':
				$clueWords = 'reason';
				break;
			case 'method':
				$clueWords = 'method';
				break;
			default:
				$clueWords = 'definition';
				break;
		}

		$clueWordsBefore = ["dikenal", "bergelar", "dinamakan", "disebut", "tersebut", "bernama", "adalah", "ialah", "yaitu", "diistilahkan","bergelar", "berjudul", "di", "ke", "dari", "menuju", "asal","tujuan", "sekitar","daerah", "pada","sejak", "dari", "tanggal", "abad", "tahun", "bulan", "hari","dikenal","panjang", "lebar", "luas", "jumlah", "suhu"];
		$clueWordsAfter = ["adalah", "ialah", "yaitu", "yakni", "diartikan", "berarti", "merupakan", "bernama","berjudul", "merupakan","berjumlah", "sebanyak", "sepanjang", "selebar", "seberat", "rata-rata", "sebesar", "antara", "sekitar", "beriksar"];

		$answers = [];
		for ($i = 0; $i < count($this->listdir); $i++)
		{
			$content	= file_get_contents($this->directory . '/' . $this->listdir[$i]);
			$paragraphs = explode('.', $content);
			foreach ($paragraphs as $paragraph)
			{
				$paragraph = $this->preprocessor->casefold($paragraph);
				
			}
		}
	}
}