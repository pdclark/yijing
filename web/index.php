<?php

require_once dirname(__DIR__) . '/lib/Yijing.php';

class api {
	
	public $values = [
		'roll' => '',
	];

	public $text;

	function __construct( $roll = false ) {
		$this->text = include dirname( __DIR__ ) . '/books/wilhelm.php';

		if ( false === $roll ) {
			$this->values['roll'] = [ $this->coin_toss(), $this->coin_toss(), $this->coin_toss(), $this->coin_toss(), $this->coin_toss(), $this->coin_toss() ];
		}else {
			$this->values['roll'] = $roll;
		}

		$this->values['roll_large_txt'] = $this->format_lines_txt( $this->values['roll'] );
		$this->values['roll_large_html'] = $this->format_lines_html( $this->values['roll'] );

		$this->values['number'] = Yijing::getNumber( $this->lines_to_binary( $this->values['roll'] ) );
		$this->values['title'] = Yijing::getName( $this->values['number'] );
		$this->values['text'] = $this->text[ $this->values['number'] ];

		$this->values[ 'roll_changes_to' ] = $this->roll_changes_to();
		$this->values[ 'roll_changes_to_number' ] = Yijing::getNumber( $this->lines_to_binary( $this->values[ 'roll_changes_to' ] ) );
		$this->values['roll_changes_to_text'] = $this->text[ $this->values['roll_changes_to_number'] ];

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

if ( isset( $_GET['roll'] ) ) {
	$api = new api( [
		$_GET['roll'][0],
		$_GET['roll'][1],
		$_GET['roll'][2],
		$_GET['roll'][3],
		$_GET['roll'][4],
		$_GET['roll'][5]
	] );	
	echo $api;
	exit;
}else {
	$api = new api();
}




?><!DOCTYPE html>
<html lang="en"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
	p[data-val="6"],
	p[data-val="7"],
	p[data-val="8"],
	p[data-val="9"] {
		height: 10px;
	}

	p[data-val="6"]:before { content: "————  ❌ ————"; }
	p[data-val="7"]:before { content: "—————————————"; }
	p[data-val="8"]:before { content: "————     ————"; }
	p[data-val="9"]:before { content: "——————⭕️—————"; }
</style>
</head>

<body>
<div id="app"></div>
<script   src="https://code.jquery.com/jquery-3.5.1.min.js"   integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="   crossorigin="anonymous"></script>
<script>
jQuery(document).ready( function($){

	window.data = <?php echo $api; ?>;

	window.$roll_changes_to_text = $('<pre>').html( data.roll_changes_to_text );
	window.$text = $('<pre>').html( data.text );
	window.$rollEl = $('<pre>' ).html( data.roll_large_html );

	$('#app').before( $rollEl ).before( $text ).before( $roll_changes_to_text );

	function updateData() {
		// console.log( window.data.roll_large_html );
		window.$text.html( window.data.text );
		if ( window.data.number == window.data.roll_changes_to_number ) {
			window.$roll_changes_to_text.html( '' );
		}else {
			window.$roll_changes_to_text.html( window.data.roll_changes_to_text );
		}
		// window.$rollEl.html( window.data.roll_large_html );
	}

	$rollEl.find('p').click( function(){

		switch ( '' + $(this).data('val') ) {
			case '6':  $(this).data( 'val', '7' ).attr( 'data-val', '7' ); break;
			case '7':  $(this).data( 'val', '8' ).attr( 'data-val', '8' ); break;
			case '8':  $(this).data( 'val', '9' ).attr( 'data-val', '9' ); break;
			case '9':  $(this).data( 'val', '6' ).attr( 'data-val', '6' ); break;
		}

		var roll = '' + $rollEl.find('p:eq(0)').data('val') +
			$rollEl.find('p:eq(1)').data('val') +
			$rollEl.find('p:eq(2)').data('val') +
			$rollEl.find('p:eq(3)').data('val') +
			$rollEl.find('p:eq(4)').data('val') +
			$rollEl.find('p:eq(5)').data('val');

		var url = './?roll=' + roll;

		$.get( url, {},
			function( data ) {
				console.log( data );
				window.data = JSON.parse( data );
				updateData();
			}
		);

	});

} );
</script>
</body>
</html>