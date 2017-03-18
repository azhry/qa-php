<?php 

require_once 'Preprocessor.php';
require_once 'QuestionAnalyzer.php';

class AnswerFinder
{
	private $directory;
	private $EAT;
	private $query;
	private $keywords;
	private $preprocessor;
	private $listdir;
	private $questionAnalyzer;

	public function __construct($query, $EAT, $keywords, $documents, $directory)
	{
		$this->preprocessor 	= new Preprocessor();
		$this->questionAnalyzer = new QuestionAnalyzer();
		$this->directory 		= $directory;
		$this->listdir 			= $documents;
		// $this->listdir 			= array_diff($this->listdir, ['.', '..']);
		$this->EAT 				= $EAT;
		$this->query 			= $this->preprocessor->casefold($query);
		$this->keywords 		= $this->questionAnalyzer->getKeywords($this->query);
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
		$clueWordBefore = [];
		$clueWordAfter = [];
		switch ($this->EAT)
		{
			case 'person':
				$clueWordBefore = ['dikenal', 'dinamakan', 'disebut', 'tersebut', 'bernama', 'bergelar', 'adalah', 'ialah', 'yaitu'];
				$clueWordAfter = ['adalah', 'ialah', 'yaitu', 'yakni', 'diartikan', 'berarti', 'merupakan', 'bernama'];
				break;
			case 'location':
				$clueWordBefore = ['di', 'ke', 'dari', 'menuju', 'asal', 'tujuan', 'sekitar', 'daerah', 'pada'];
				$clueWordAfter = ['adalah', 'ialah', 'yaitu', 'merupakan'];
				break;
			case 'time':
				$clueWordBefore = ['sejak', 'dari', 'pada', 'tanggal', 'abad', 'tahun', 'bulan', 'hari'];
				$clueWordAfter = ['adalah', 'ialah', 'merupakan', 'yaitu'];
				break;
			case 'organization':
				$clueWordBefore = ['dikenal', 'disebut', 'dinamakan', 'bernama', 'adalah', 'yaitu', 'ialah'];
				$clueWordAfter = ['adalah', 'ialah', 'merupakan', 'yaitu'];
				break;
			case 'quantity':
				$clueWordBefore = ['panjang', 'lebar', 'luas', 'jumlah', 'suhu'];
				$clueWordAfter = ['berjumlah', 'sebanyak', 'sepanjang', 'selebar', 'seberat', 'rata-rata', 'yakni', 'sebesar', 'antara', 'sekitar', 'berkisar'];
				break;
			case 'definition':
				$clueWordBefore = ['disebut', 'dikenal', 'dinamakan', 'mendefinisikan'];
				$clueWordAfter = ['yaitu', 'ialah', 'adalah', 'diartikan', 'merupakan'];
				break;
			case 'reason':
				$clueWordBefore = ['menyebabkan', 'karena itu', 'oleh sebab itu', 'jadi', 'itulah sebabnya', 'memungkinkan', 'adanya', 'karenanya', 'dengan', 'demikian', 'maka', 'dikatakan', 'tujuan', 'penyebab terjadinya', 'sehingga', 'mengapa', 'dengan', 'walau demikian', 'namun demikian'];
				$clueWordAfter = ['sebab', 'karena', 'disebabkan', 'bertujuan', 'terjadi', 'karena'];
				break;
			case 'method':
				$clueWordBefore = ['cara', 'untuk', 'proses'];
		}

		$pWeights = [];
		echo "retrieving paragraphs........\n";
		for ($i = 0; $i < count($this->listdir); $i++)
		{
			$content	= file_get_contents($this->directory . '/' . $this->listdir[$i]);
			$paragraphs = explode('.', $content);
			echo $this->listdir[$i] . "....\n";
			foreach ($paragraphs as $paragraph)
			{
				$pWeights[$paragraph] = 0;
				$temp = $paragraph;
				$paragraph = $this->preprocessor->casefold($paragraph);
				$paragraph = $this->preprocessor->stem($paragraph);
				$paragraph = $this->preprocessor->tokenize($paragraph);
				$paragraph = $this->preprocessor->stopword_removal($paragraph);
				foreach ($paragraph as $p)
				{
					if (in_array($p, $this->keywords))
						$pWeights[$temp]++;
				}
			}
		}

		arsort($pWeights);
		$candidate = [];
		echo "retrieving answers........\n";
		foreach ($pWeights as $paragraph => $weight)
		{
			foreach ($this->keywords as $keyword)
			{
				foreach ($clueWordBefore as $clueWord)
				{
					if (strpos($paragraph, $clueWord . ' ' . $keyword) !== FALSE)
						$candidate []= $paragraph;
				}

				foreach ($clueWordAfter as $clueWord)
				{
					if (strpos($paragraph, $keyword . ' ' . $clueWord) !== FALSE)
						$candidate []= $paragraph;
				}
			}
		}

		return $candidate;

		// $retrievedParagraphs = [];
		// if ($this->EAT === 'reason' or $this->EAT === 'method')
		// {
		// 	$i = 0;
		// 	foreach ($pWeights as $p => $w)
		// 	{
		// 		$retrievedParagraphs []= $p;
		// 		if (++$i === 50)
		// 			break;
		// 	}
		// }
		// else
		// {
		// 	$temp = array_filter($pWeights, function($v) {
		// 		return $v <= 0;
		// 	});
		// 	foreach ($temp as $p => $w)
		// 		$retrievedParagraphs []= $p;
		// }


		// $candidate = [];
		// foreach ($retrievedParagraphs as $p)
		// {
		// 	if ($this->EAT == 'person')
		// 	{
				
		// 	}
		// }
	}
}