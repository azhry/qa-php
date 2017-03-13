<?php 

require_once 'Preprocessor.php';

class DocumentRetriever
{
	private $directory;
	private $query;
	private $index;
	private $numIndexed;
	private $preprocessor;
	private $weightList;

	public function __construct($query, $directory)
	{
		$this->directory 	= $directory;
		$this->index 		= [];
		$this->weightList 	= [];
		$this->numIndexed 	= 0;
		$this->preprocessor = new Preprocessor();
		$this->query 		= $this->preprocessor->casefold($query);
		$this->query 		= $this->preprocessor->stem($this->query);
		$this->query 		= $this->preprocessor->tokenize($this->query);
		$this->query 		= $this->preprocessor->stopword_removal($this->query);
	}

	public function createIndex()
	{
		$listdir = scandir($this->directory);
		$listdir = array_diff($listdir, ['.', '..']);
		foreach ($listdir as $dir)
		{
			$txt = file_get_contents($this->directory . "/" . $dir);
			$txt = $this->preprocessor->casefold($txt);
			$txt = $this->preprocessor->stem($txt);
			$txt = $this->preprocessor->tokenize($txt);
			$txt = $this->preprocessor->stopword_removal($txt);

			if (count(array_intersect($this->query, $txt)) > 0)
			{
				$this->index[$dir] 		= $txt;
				$this->weightList[$dir]	= [];
				$this->numIndexed++;
			}
		}

		return $this->index;
	}

	private function termWeight()
	{
		foreach ($this->index as $id => $content)
		{
			foreach ($this->query as $q)
			{
				$this->weightList[$id][$q] = $this->tf($q, $content) * $this->idf($q, $this->index);
			}
		}

		return $this->weightList;
	}

	public function retrieve()
	{
		$queryWeightList = [];
		foreach ($this->query as $q)
			$queryWeightList[$q] = $this->tf($q, $this->query) * $this->idf($q, $this->query);
		
		$this->termWeight();

		$similarityList = [];
		foreach ($this->index as $id => $content)
		{
			$dotProducts = 0;
			foreach ($this->query as $q)
				$dotProducts += $queryWeightList[$q] * $this->weightList[$id][$q];

			$qDistance = 0;
			foreach ($this->query as $q)
				$qDistance += pow($queryWeightList[$q], 2);
			$qDistance = sqrt($qDistance);

			$docDistance = 0;
			foreach ($this->query as $q)
				$docDistance += pow($this->weightList[$id][$q], 2);
			$docDistance = sqrt($docDistance);

			$similarityList[$id] = $qDistance * $docDistance == 0 ? 0 : $dotProducts / ($qDistance * $docDistance);
		}

		arsort($similarityList);
		return $similarityList;
	}


	private function tf($term, $document)
	{
		$tf = [];
		foreach ($document as $word)
		{
			if (array_key_exists($word, $tf))
				$tf[$word]++;
			else
				$tf[$word] = 1;
		}

		if (array_key_exists($term, $tf))
			return $tf[$term] / count($document);
		return 0;
	}

	private function idf($term, $documents)
	{
		$df = 0;
		foreach ($documents as $document)
		{
			if (!is_array($document))
				$document = [$document];

			if (in_array($term, $document))
				$df++;
		}

		if ($df > 0)
			return 1.0 + log((float)$this->numIndexed/$df);
		return 1.0;
	}
}