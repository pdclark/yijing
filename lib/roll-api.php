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

		$from_to = [
			'Fire'      => '<b><span>⽕🔥</span>Fire</b>',
			'fire'       => '<b><span>⽕🔥</span>fire</b>',
			'FIRE'       => '<b><span>⽕🔥</span> Fire</b>',
			'flame'      => '<b><span>⽕🔥</span>flame</b>',
			'FLAME'      => '<b><span>⽕🔥</span> Flame</b>',
			'River'      => '<b><span>河🦦</span>River</b>',
			'river'      => '<b><span>河🦦</span>river</b>',
			'RIVER'      => '<b><span>河🦦</span> River</b>',
			'Heaven'     => '<b><span>天🌌</span>Heaven</b>',
			'heaven'     => '<b><span>天🌌</span>heaven</b>',
			'HEAVEN'     => '<b><span>天🌌</span> Heaven</b>',
			' wind '       => ' <b><span>风💨</span>wind</b> ',
			' Wind '       => ' <b><span>风💨</span>Wind</b> ',
			'WIND'       => '<b><span>风💨</span> Wind</b>',
			'Mountains'   => '<b><span>山🏔</span>Mountains</b>',
			'Mountain'   => '<b><span>山🏔</span>mountain</b>',
			'mountain'   => '<b><span>山🏔</span>mountain</b>',
			'MOUNTAIN'   => '<b><span>山🏔</span> Mountain</b>',
			'Earth'      => '<b><span>地🌎</span>Earth</b>',
			'earth'      => '<b><span>地🌎</span>earth</b>',
			'EARTH'      => '<b><span>地🌎</span> Earth</b>',
			'Thunder'    => '<b><span>雷⚡️</span>Thunder</b>',
			'thunder'    => '<b><span>雷⚡️</span>thunder</b>',
			'THUNDER'    => '<b><span>雷⚡️</span> Thunder</b>',
			'SHOCK'    => '<b><span>休克⚡️</span> SHOCK</b>',
			' rain '    => ' <b><span>雨🌧</span>rain</b> ',
			'Rain'    => '<b><span>雨🌧</span>Rain</b>',
			'Lake'       => '<b><span>湖🏞</span>Lake</b>',
			'lake'       => '<b><span>湖🏞</span>lake</b>',
			'LAKE'       => '<b><span>湖🏞</span> Lake</b>',
			'Wood'       => '<b><span>木🌲</span>Wood</b>',
			'wood'       => '<b><span>木🌲</span>wood</b>',
			'WOOD'       => '<b><span>木🌲</span> Wood</b>',
			'Water'      => '<b><span>水🌊</span>Water</b>',
			'water'      => '<b><span>水🌊</span>water</b>',
			'WATER'      => '<b><span>水🌊</span> Water</b>',
			'goose'      => '<b><span>鹅🦆</span>goose</b>',
			'geese'      => '<b><span>鹅🦆</span>geese</b>',
			'Geese'      => '<b><span>鹅🦆</span>Geese</b>',
			'Goose'      => '<b><span>鹅🦆</span>Goose</b>',
			'Fox'      => '<b><span>狐🦊</span>Fox</b>',
			'fox'      => '<b><span>狐🦊</span>fox</b>',
			' man '      => ' <b><span>人🙇‍♂️</span>person</b> ',
			' woman '      => ' <b><span>⼥🙇‍♀️</span>person</b> ',
			'chile dally'      => 'child dally',
			' he imitates'      => ' they imitate',
			' he rids'      => ' they rid',
			' he has'      => ' they have',
			' he sees'      => ' they see',
			' he stands'      => ' they stand',
			' he does'      => ' they do',
			'He does'      => 'They do',
			' He '      => ' They ',
			' he '      => ' they ',
			' They goes '      => ' They go ',
			' He is '      => ' They are ',
			' he is '      => ' they are ',
			' his'      => ' their',
			' him'      => ' them',
			' men will '      => ' people will ',
			' kings'      => ' rulers',
			' king'      => ' ruler',
			'    above'      => 'above',
			'    below'      => 'below',
			'bring humiliation'      => 'brings humiliation',
			'Fellowship with Men'      => 'Fellowship with People',
			'FELLOWSHIP WITH MEN' => 'FELLOWSHIP WITH PEOPLE',
			'THE LINES' . PHP_EOL . PHP_EOL  => '⚬ ',
			'THE JUDGMENT' . PHP_EOL . PHP_EOL  => '',
			'THE IMAGE' . PHP_EOL . PHP_EOL  => '',
		];

		$this->values['number'] = Yijing::getNumber( $this->lines_to_binary( $this->values['roll'] ) );
		$this->values['title'] = Yijing::getName( $this->values['number'] );
		$this->values['text'] = str_replace( array_keys( $from_to ), array_values( $from_to ), $parsedown->text( $this->text[ $this->values['number'] ] ) );

		$this->values[ 'roll_changes_to' ] = $this->roll_changes_to();
		$this->values[ 'roll_changes_to_number' ] = Yijing::getNumber( $this->lines_to_binary( $this->values[ 'roll_changes_to' ] ) );
		$this->values['roll_changes_to_text'] = str_replace( array_keys( $from_to ), array_values( $from_to ), $parsedown->text( $this->text[ $this->values['roll_changes_to_number'] ] ) );

		// $this->values['unicode'] = Yijing::$unicode;
		// $this->values['number_to_roll'] = Yijing::$number_to_roll;

		$this->values['hexagrams'] = [];
		foreach( Yijing::$unicode as $key => $unicode ) {
			$this->values['hexagrams'][ $key ]['unicode'] = $unicode;
			$this->values['hexagrams'][ $key ]['binary'] = Yijing::$number_to_binary[ $key ];
			$this->values['hexagrams'][ $key ]['roll'] = str_replace(
				[ '0', '1' ],
				[ '8', '7' ],
				Yijing::$number_to_binary[ $key ]
			);
			if ( $key == $this->values[ 'number' ] ) {
				$this->values['text'] = '<h2>' . $unicode . '</h2>' . $this->values['text'];
			}
			if ( $key == $this->values[ 'roll_changes_to_number' ] ) {
				$this->values['roll_changes_to_text'] = '<h2>' . $unicode . '</h2>' . $this->values['roll_changes_to_text'];
			}
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
		return $bin;
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