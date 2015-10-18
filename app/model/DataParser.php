<?php

namespace App\Utils;

/**
 * Converts data according to its format to uniform string. Data format needs to be specified before processing.
 * Uniform string format: "X1 Y1;X2 Y2;X3 Y3; ... ;Xn Yn"
 * Class DataParser
 * @package App\Utils
 */
class DataParser {

	protected $availableFormats = [];
	protected $format;

	public function __construct() {
		$this->setAvailableFormats();
	}

	public function setAvailableFormats() {
		/**
		 * Format 'a'
		 *
		 * #some comment - data separated by lines, coordinates by tabulator
		 * X1   Y1
		 * X2   Y2
		 * .....
		 * Xn   Yn
		 */
		array_push($this->availableFormats, 'a');
	}

	public function setFormat($formatId) {
		$this->format = $formatId;
	}

	public function parse($filePath, $format = 'a') {
		$this->format = $format;
		$buffer = "";
		$file = fopen('nette.safe://'.$filePath, 'r');

		switch ($this->format) {
			case 'a':
				while (!feof($file)) {
					$line = fgets($file);
					if (trim($line)[0] == '#') continue;

					$parts = preg_split('/\s+/', $line);
					$buffer .= $parts[0] . ' ' . $parts[1] . ';';
				}

				$buffer = substr_replace($buffer, "", -1); //delete last ;
				break;
		}

		return $buffer;
	}

}