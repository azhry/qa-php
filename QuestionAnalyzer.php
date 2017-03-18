<?php 

require_once "Preprocessor.php";

class QuestionAnalyzer
{
	private $questionWords = ["siapa", "siapakah", "dimana", "dimanakah", "kemana", "mana", "kemanakah", "darimana", "darimanakah", "kapan", "kapankah", "berapa", "berapakah", "berapakah", "apa", "apakah", "mengapa", "kenapa", "bagaimana", "bagaimanakah"];

	private $questionTypes = [
		'factoid' => [
			'person'		=> ["adalah", "ialah", "yaitu", "nama", "bernama"],
			'location'		=> ["di", "ke", "dari", "tempat"],
			'time'			=> ["pada", "hari", "minggu", "tanggal", "bulan", "tahun", "abad", "jam", "menit", "detik", "januari", "februari", "maret", "april", "mei", "juni", "juli", "agustus", "september", "oktober", "november", "desember", "lama"],
			'organization'	=> ["organisasi", "perusahaan", "badan", "institusi", "lembaga", "partai", "komisi", "sekolah", "komite", "universitas"],
            'quantity'		=> ["jumlah", "banyak", "kilo", "gram", "meter"]
		],
		
		'non_factoid' => [
			'definition'	=> ["merupakan", "definisi", "dimaksud", "pengertian", "arti", "disebut", "dikenal", "dinamakan", "mendefinisikan", "adalah", "yaitu", "ialah", "merujuk", "diartikan"],
			'reason'		=> ["demikian", "jadi", "sebabnya", "karenanya", "maka", "menyebabkan", "dikatakan", "tujuan", "terjadinya", "sehingga", "sebab", "penyebab", "disebabkan", "menyebabkan", "karena", "bertujuan", "terjadi"],
			'method'		=> ["cara", "untuk", "proses", "langkah", "tahap", "tahapan"]
		]
	];

	private $preprocessor;

	public function __construct()
	{
		$this->preprocessor = new Preprocessor();
	}

	// EAT = Expected Answer Type
	public function getEAT($query)
	{
		$words = $this->preprocessor->casefold($query);
		$words = $this->preprocessor->tokenize($words);

		$EAT = '';

		foreach ($words as $word)
		{
			if (in_array($word, $this->questionWords))
			{
				if ($word === 'siapa' or $word === 'siapakah')
					$EAT = 'person';
				else if ($word === 'dimana' or $word === 'dimanakah' or $word === 'kemana' or $word === 'kemanakah' or $word === 'darimana' or $word === 'darimanakah' or $word === 'mana')
					$EAT = 'location';
				else if ($word === 'kapan' or $word === 'kapankah')
					$EAT = 'time';
				else if ($word === 'berapa' or $word === 'berapakah')
				{
					foreach ($words as $w)
					{
						if (in_array($w, $this->questionTypes['factoid']['time']))
							$EAT = 'time';
						else if (in_array($w, $this->questionTypes['factoid']['quantity']))
							$EAT = 'quantity';
						else
							$EAT = 'quantity';
					}
				}
				else if ($word === 'apa' or $word === 'apakah')
				{
					foreach ($words as $w)
					{
						if (in_array($w, $this->questionTypes['non_factoid']['definition']))
							$EAT = 'definition';
						else if (in_array($w, $this->questionTypes['non_factoid']['reason']))
							$EAT = 'reason';
						else if (in_array($w, $this->questionTypes['factoid']['organization']))
							$EAT = 'organization';
						else
							$EAT = 'definition';
					}
				}
				else if ($word === 'mengapa' or $word === 'kenapa')
					$EAT = 'reason';
				else if ($word === 'bagaimana' or $word === 'bagaimanakah')
					$EAT = 'method';
			}
			else
			{
				if(in_array($word, $this->questionTypes['non_factoid']['definition']))
	                $EAT = "definition";
	            else if(in_array($word, $this->questionTypes['factoid']['location']))
	                $EAT = "location";
	            else if(in_array($word, $this->questionTypes['non_factoid']['method']))
	                $EAT = "method";
	            else if(in_array($word, $this->questionTypes['factoid']['organization']))
	                $EAT = "organization";
	            else if(in_array($word, $this->questionTypes['factoid']['person']))
	                $EAT = "person";
	            else if(in_array($word, $this->questionTypes['factoid']['quantity']))
	                $EAT = "quantity";
	            else if(in_array($word, $this->questionTypes['non_factoid']['reason']))
	                $EAT = "reason";
	            else if(in_array($word, $this->questionTypes['factoid']['time']))
	                $EAT = "time";
	            
			}
		}

		return $EAT;
	}

	public function getKeywords($query)
	{
		$words = $this->preprocessor->casefold($query);
		$words = $this->preprocessor->stem($words);
		$words = $this->preprocessor->tokenize($words);
		$words = $this->preprocessor->stopword_removal($words);
		$keywords = array_diff($words, $this->questionWords);
		return $keywords;
	}
}