<?php

class api {
	
	public $values = [
		'roll' => '',
	];

	public $text;

	function __construct( $roll = false ) {
		$this->text = include dirname( __DIR__ ) . '/books/wilhelm.php';

		include dirname( __DIR__ ) . '/lib/parsedown/Parsedown.php';
		$parsedown = new Parsedown();
		$parsedown->setBreaksEnabled(true);

		if ( false === $roll ) {
			$this->values['roll'] = [ $this->coin_toss(), $this->coin_toss(), $this->coin_toss(), $this->coin_toss(), $this->coin_toss(), $this->coin_toss() ];
		}else {
			$this->values['roll'] = $roll;
		}

		$this->values['roll_large_txt'] = $this->format_lines_txt( $this->values['roll'] );
		$this->values['roll_large_html'] = $this->format_lines_html( $this->values['roll'] );

		$from = ['FIRE', 'RIVER', 'HEAVEN', 'WIND', 'WATER', 'MOUNTAIN', 'EARTH', 'THUNDER', 'LAKE', 'WOOD', 'FLAME' ];
		$to = ['<b><span>🔥</span> Fire</b>', '<b><span>🦦</span> River</b>', '<b><span>🌌</span> Heaven</b>', '<b><span>💨</span> Wind</b>', '<b><span>🌊</span> Water</b>', '<b><span>🏔</span> Mountain</b>', '<b><span>🌎</span> Earth</b>', '<b><span>⛈</span> Thunder</b>', '<b><span>🏞</span> Lake</b>', '<b><span>🌲</span> Wood</b>', '<b><span>🔥</span> Flame</b>' ];

		$this->values['number'] = Yijing::getNumber( $this->lines_to_binary( $this->values['roll'] ) );
		$this->values['title'] = Yijing::getName( $this->values['number'] );
		$this->values['text'] = str_replace( $from, $to, $parsedown->text( $this->text[ $this->values['number'] ] ) );

		$this->values[ 'roll_changes_to' ] = $this->roll_changes_to();
		$this->values[ 'roll_changes_to_number' ] = Yijing::getNumber( $this->lines_to_binary( $this->values[ 'roll_changes_to' ] ) );
		$this->values['roll_changes_to_text'] = str_replace( $from, $to, $parsedown->text( $this->text[ $this->values['roll_changes_to_number'] ] ) );

		// $this->values['unicode'] = Yijing::$unicode;
		// $this->values['number_to_roll'] = Yijing::$number_to_roll;

		$this->values['hexagrams'] = [];
		foreach( Yijing::$unicode as $key => $unicode ) {
			$this->values['hexagrams'][ $key ]['unicode'] = $unicode;
			$this->values['hexagrams'][ $key ]['binary'] = Yijing::$number_to_binary[ $key ];

		}

	}

	function __toString(){
		return json_encode( $this->values );
	}

	function coin_toss() {
		$coins = [
			random_int(0,63) % 2,
			random_int(0,63) % 2,
			random_int(0,63) % 2,
		];
		return array_reduce($coins, function($carry, $item) {
			return $carry + ($item ? 3 : 2);
		});
	}

	function lines_to_binary( $lines ) {
		$bin = '';
		foreach ($lines as $line) {
			switch ($line) {
				case 6:
				case 8:
					$bin .= '0';
					break;
				case 7:
				case 9:
					$bin .= '1';
					break;
			}
		}
		return base_convert(strrev($bin), 2, 10);
	}

	function roll_changes_to() {
		$changesTo = [];
		foreach ($this->values['roll'] as $index => $line) {
			switch ($line) {
				case 6:
					$changesTo[$index] = 7;
					break;
				case 9:
					$changesTo[$index] = 8;
					break;
				default:
					$changesTo[$index] = $line;
					break;
			}
		}

		return $changesTo;
	}

	function format_line($val) {
		switch ($val) {
			case 6:
				return '————  ❌ ————';
			case 7:
				return '—————————————';
			case 8:
				return '————     ————';
			case 9:
				return '——————⭕️—————';
		}
	}

	function format_lines_txt( $lines ) {
		foreach ($lines as $index => $line) {
			$output .= '    ' . $this->format_line( $line ) . ' ' . (6 - $index) . "\n";
		}
		
		return $output;
	}
	function format_lines_html( $lines ) {
		foreach ($lines as $index => $line) {
			$output .= '<p data-val="' . $line . '"></p>';
		}
		
		return $output;
	}

	// function formatQuickLine( $val ) {
	// 	switch ($val) {
	// 		case 6:
	// 		case 8:
	// 			return '¦';
	// 		case 7:
	// 		case 9:
	// 			return '|';
	// 	}
	// }

	// function slug($question) {
	// 	return preg_replace(
	// 		'/-{2,}/g', '-', 
	// 		preg_replace('/[^a-zA-Z0-9]/', '-', $question)
	// 	);
	// }
}