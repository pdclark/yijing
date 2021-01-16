	<?php

	require_once dirname( __DIR__ ) . '/lib/Yijing.php';
	require_once dirname( __DIR__ ) . '/lib/random-int.php';
	require_once dirname( __DIR__ ) . '/lib/roll-api.php';


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

		body{
			font-family: monospace;
		}

		h2.易經 {
			margin: 2vh 2vw 0 0;
		}
		#text h2, pre h2 {
			font-size: 20vh;
			margin:0;	
		}

		h3 {
			font-size: 18px;
		}

		pre,p {clear:left; white-space: pre-wrap; max-width: 75vw;
			 text-shadow: 0 0 1vw #fff;
		}

		/* Roll Lines */
		#roll {
			/*position: fixed; bottom: 11vh; left: 2vw;*/
			font-size: 2vw;
			position: relative; left: 2vw;
			display:none;
		}

		p[data-val="6"],
		p[data-val="7"],
		p[data-val="8"],
		p[data-val="9"] {
			height: 1vh;
			width: 200px;
		}

		p[data-val="6"]:before { content: "————  ❌ ————"; }
		p[data-val="7"]:before { content: "—————————————"; }
		p[data-val="8"]:before { content: "————     ————"; }
		p[data-val="9"]:before { content: "——————⭕️—————"; }
			
		.line-1,.line-2,.line-3,.line-4,.line-5,.line-6 {
			padding: 5px;
			background-color: rgba( 0,0,0,.5 );
			color: rgb( 255,255,255 );
			display:inline-block;
			float:left;
			clear:left;
			font-size: 2.5vh;
			position: relative;
			text-shadow: 0 0 1vw #000;
		}
		.line-1:before, .line-2:before, .line-3:before, .line-4:before, .line-5:before, .line-6:before {
			color: rgba( 180, 20, 20, .5 ); position: absolute; 
			top: -3vh; left: -2vh;
			font-size: 5vh;
			text-shadow:none;
		}
		.line-1:before { content: "1"; }
		.line-2:before { content: "2"; }
		.line-3:before { content: "3"; }
		.line-4:before { content: "4"; }
		.line-5:before { content: "5"; }
		.line-6:before { content: "6"; }

		/* Before, after, and hexagrams */
		nav {
			position: fixed; top: 0vh; left: 0;
			width: 100vw;
			background-color: rgba( 255,255,255,.8);
		}
		nav .ul-wrap { 
			width: 30vw; margin:0; padding:0; float:left; 
		}
		nav .ul-wrap ul {
			margin:0; padding: 0 0 0 5vw;
		}
		nav li { list-style-type: none; margin:0; padding:0; width: 2.5vw; display:inline-block; position: relative; }
		nav li:before {
			content: attr( data-number );
			position: absolute;
			top: -.85vh;
			left: .5vh;
			font-size: 1.5vh;
			color: rgba( 0,0,0, .3 );
		}
		.roll-from { color: green; }        .roll-from:before { color: green; }
		.roll-to { color: red; }        .roll-to:before { color: red; }
		.roll-single { color: blue; }        .roll-single:before { color: blue; }

		.roll-from, .roll-to, .roll-single {
			/*font-size: 12vh;*/
			font-size: 3vh;
			margin: -2vh -.5vh -3vh -.25vh;
			/*margin: -1vh -.5vh -1vh -.5vh;*/
			/*top: .5vh;*/
			/*left: .25vh;*/
			position: relative;
		}

		nav h3, nav h2 { float:left; }

		nav .before, nav .after {
			display: inline-block;
			float:left;
			/*border: 1px solid red;*/
			/*width: 20vw;*/
			/*height: 20vw;*/
			position: relative;
		}

		nav .before:after, nav .after:after {
			font-size: 20vw;
			/*top: -1vw;*/
			/*left: .5vw;*/
			/*position: absolute;*/
		}
		nav .before:before, nav .after:before {
			position: absolute;
			top: 1vh;
			left: 0;
		}


		nav .before:before { content: attr( data-number ); }
		nav .before:after { content: attr( data-unicode ); }
		nav .after:before { content: attr( data-number ); }
		nav .after:after { content: attr( data-unicode ); }

		nav li.roll-from:before, nav li.roll-to:before, nav li.roll-single:before {
		}

		nav li.active {
			background-color: #000;
			color: #fff;
		}
		/*nav li.active:before {
			content: attr( data-binary );
			color: green;
		}*/

		#roll-button {
			position: fixed; top: 0vh; right: 2vw;
			font-size: 8vh;
			z-index: 100;
			display:inline-block;
			float:right;
		}
		#roll-button a {
			text-decoration: none;

		}

		/* Emoji */
		b {
			font-weight: normal;
			position: relative;
			white-space: nowrap;
		}
		b:first-child span {
			/*top: -18vh;
			left: -30vh;*/
		}
		b span {
			font-size: 6vh;
			margin-right: .5vh;
			/*font-size: 26vh;*/
			/*position: absolute;
			top: 0vh;
			left: -50vw;*/
			z-index: -1;
			/*opacity: .2;*/
			/*width: 40vh;*/
			white-space: nowrap;
		}

		/*@media (orientation: landscape) {*/
			#roll-button {
				left: auto;
				right: 2vw;
				bottom: 13vh;
			}
			/*#roll {
				top: 80vw; left: 2vw; bottom: auto;
			}*/
		/*}*/

		body{ top:0; width: 80vw; font-size: 2vw; background: #fff ; font-size: 2.8vw; overflow-x:hidden; margin: 10vh auto 10vh auto; }
	</style>
	<script   src="https://code.jquery.com/jquery-3.5.1.min.js"   integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="   crossorigin="anonymous"></script>
	</head>

	<body>
	<div id="app"></div>

	<script>
	jQuery(document).ready( function($){

		window.data = <?php echo $api; ?>;
		window.trigrams = <?php echo json_encode( Yijing::$trigrams ); ?>;

		window.$roll_changes_to_text = $('<pre>').html( data.roll_changes_to_text );
		window.$text = $('<div id="text">').html( data.text );
		window.$rollEl = $('<pre>' ).attr('id', 'roll').html( data.roll_large_html );

		$('#app').before( $text ).before( $roll_changes_to_text );

		window.$nav = $('<nav><h2 class="易經">易經</h2><h3>From:</h3> <div class="before"></div> <div class="ul-wrap"><ul class="from-above"></ul><ul class="from-below"></ul></div> <h3>To:</h3> <div class="after"></div> <div class="ul-wrap"> <ul class="to-above"></ul><ul class="to-below"></ul> </div> <div id="roll-button"><a href="javascript:window.location.reload()">🔄</a></div> </nav>');

		$nav.append( $rollEl );

		$.each( window.trigrams, function( trigram, binary ) {
			$nav.find('ul.from-above').append( 
				$('<li>' )
					.attr('data-trigram', trigram )
					.attr('data-binary', binary )
					.text( trigram )
			);
			$nav.find('ul.from-below').append( 
				$('<li>' )
					.attr('data-trigram', trigram )
					.attr('data-binary', binary )
					.text( trigram )
			);
			$nav.find('ul.to-above').append( 
				$('<li>' )
					.attr('data-trigram', trigram )
					.attr('data-binary', binary )
					.text( trigram )
			);
			$nav.find('ul.to-below').append( 
				$('<li>' )
					.attr('data-trigram', trigram )
					.attr('data-binary', binary )
					.text( trigram )
			);
		});

		// It's backwards :'(
		var fromBelowRoll = window.data.hexagrams[ window.data.number ].roll.substr(0,3);
		var fromAboveRoll = window.data.hexagrams[ window.data.number ].roll.substr(3,3);

		$nav.find('ul.from-above li[data-binary=' + fromAboveRoll + ']' ).addClass( 'active' );
		$nav.find('ul.from-below li[data-binary=' + fromBelowRoll + ']' ).addClass( 'active' );

		var toBelowRoll = window.data.hexagrams[ window.data.roll_changes_to_number ].roll.substr(0,3);
		var toAboveRoll = window.data.hexagrams[ window.data.roll_changes_to_number ].roll.substr(3,3);

		$nav.find('ul.to-above li[data-binary=' + toAboveRoll + ']' ).addClass( 'active' );
		$nav.find('ul.to-below li[data-binary=' + toBelowRoll + ']' ).addClass( 'active' );

		$nav.find( 'li' ).click( function() {

			$(this).addClass('active').siblings().removeClass('active');

			var rollFrom = $('ul.from-below li.active').data('binary') + "" + $('ul.from-above li.active').data('binary');
			var rollTo = $('ul.to-below li.active').data('binary') + "" + $('ul.to-above li.active').data('binary');

			var r = [];
			var rollFromArray = rollFrom.split('');
			var rollToArray = rollTo.split('');
			$.each( rollFromArray, function( index, n ) {
				if ( 7 == n && 8 == rollToArray[ index ] ) {
					r.push( 9 );
					return;
				}
				if ( 8 == n && 7 == rollToArray[ index ] ) {
					r.push( 6 );
					return;
				}
				r.push( n );
			} );

			console.log( 'Send:', rollFrom, rollTo, r.join() );

			var roll = r.join( '' );

			var url = './?roll=' + roll;

			$.get( url, {},
				function( data ) {
					// console.log( data );
					window.data = JSON.parse( data );
					updateData();
				}
			);

		} );
		/*
		$nav.find('li').click(function(){
			var roll = '';
			for ( i=5; i>=0; i-- ) {
				var tmp_binary = $(this).attr('data-binary').substring( i, i+1 );
				var tmp_roll = '';
				switch( tmp_binary ) {
					case '0': roll += '8'; tmp_roll = '8'; break;
					case '1': roll += '7'; tmp_roll = '7'; break;
				}
				$rollEl.find('p:eq(' + (5-i) + ')').attr('data-val', tmp_roll );
			}

			// repeat of below when click line
			// var roll = '' + $rollEl.find('p:eq(0)').data('val') +
			// 	$rollEl.find('p:eq(1)').data('val') +
			// 	$rollEl.find('p:eq(2)').data('val') +
			// 	$rollEl.find('p:eq(3)').data('val') +
			// 	$rollEl.find('p:eq(4)').data('val') +
			// 	$rollEl.find('p:eq(5)').data('val');

			var url = './?roll=' + roll;

			$.get( url, {},
				function( data ) {
					// console.log( data );
					window.data = JSON.parse( data );
					updateData();
				}
			);

		});
		*/
		$('#app').after( $nav );

		function updateData() {
			// console.log( window.data.roll_large_html );
			// console.log( window.data );
			// console.log( 'Receive:', window.data.roll.join('') );
			// It's backwards :'(
			var fromBelowRoll = window.data.hexagrams[ window.data.number ].roll.substr(0,3);
			var fromAboveRoll = window.data.hexagrams[ window.data.number ].roll.substr(3,3);

			$nav.find('ul.from-above li[data-binary=' + fromAboveRoll + ']' ).addClass( 'active' ).siblings().removeClass( 'active' );
			$nav.find('ul.from-below li[data-binary=' + fromBelowRoll + ']' ).addClass( 'active' ).siblings().removeClass( 'active' );

			var toBelowRoll = window.data.hexagrams[ window.data.roll_changes_to_number ].roll.substr(0,3);
			var toAboveRoll = window.data.hexagrams[ window.data.roll_changes_to_number ].roll.substr(3,3);

			$nav.find('ul.to-above li[data-binary=' + toAboveRoll + ']' ).addClass( 'active' ).siblings().removeClass( 'active' );
			$nav.find('ul.to-below li[data-binary=' + toBelowRoll + ']' ).addClass( 'active' ).siblings().removeClass( 'active' );

			window.$text.html( window.data.text );
			if ( window.data.number == window.data.roll_changes_to_number ) {
				window.$roll_changes_to_text.html( '' );
			}else {
				window.$roll_changes_to_text.html( window.data.roll_changes_to_text );
			}

			$text.add( $roll_changes_to_text ).find('p').remove();


			// console.log( $nav );
			// console.log( data.number );

			$nav.find('li')
				.removeClass('roll-from')
				.removeClass('roll-single')
				.removeClass('roll-to')

			var unicode_before = $nav.find('li[data-number="' + data.number + '"]').data('unicode');

			if ( data.roll_changes_to_number == data.number ) {

				$nav
					.find('li[data-number="' + data.number + '"]')
					.addClass('roll-single');

				$nav.find('.before')
					.attr('data-number', data.number )
					.attr('data-unicode', unicode_before );

				$nav.find('.after')
					.attr('data-number', data.number )
					.attr('data-unicode', unicode_before );
			}else {
				$nav
					.find('li[data-number="' + data.number + '"]')
					.addClass('roll-from');
				$nav.find('.before')
					.attr('data-number', data.number )
					.attr('data-unicode', unicode_before );;

				var unicode_after = $nav.find('li[data-number="' + data.roll_changes_to_number + '"]').data('unicode');

				$nav
					.find('li[data-number="' + data.roll_changes_to_number + '"]')
					.addClass('roll-to');
				$nav.find('.after')
					.attr('data-number', data.roll_changes_to_number )
					.attr('data-unicode', unicode_after );
			}

			var $line1 = $text.add( $roll_changes_to_text ).find('pre:contains("⚬")');

			$line1
				.addClass('line-1')
				.next().addClass('line-2')
				.next().addClass('line-3')
				.next().addClass('line-4')
				.next().addClass('line-5')
				.next().addClass('line-6')

			$roll_changes_to_text.find( '.line-1,.line-2,.line-3,.line-4,.line-5,.line-6').remove();
			$text.find( '.line-1,.line-2,.line-3,.line-4,.line-5,.line-6').hide();

			$.each( window.data.roll, function( index, n ) {
				if ( 6 == n || 9 == n ) {
					$( '.line-' + ( index + 1 ) ).show();
				}
			} );

			/*
			$rollEl.find('p').each(function(){
				if ( '6' == $(this).data('val') || '9' == $(this).data('val') ) {
					switch ( $(this).index() ) {
						case 0: $('.line-6').show(); break;
						case 1: $('.line-5').show(); break;
						case 2: $('.line-4').show(); break;
						case 3: $('.line-3').show(); break;
						case 4: $('.line-2').show(); break;
						case 5: $('.line-1').show(); break;
					}
				}
			});
			*/

			if ( $('.roll-single' ).length ) {
				$text.find( '.line-1,.line-2,.line-3,.line-4,.line-5,.line-6').hide();
			}

			// window.$rollEl.html( window.data.roll_large_html );
		}
		updateData();

		// $nav.find('li').click( function(){
		// 	window.location.reload();
		// });

		$rollEl.find('p').add().click( function(){

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
					// console.log( data );
					window.data = JSON.parse( data );
					updateData();
				}
			);

		});

	} );
	</script>

	<pre></pre>
	</body>
	</html>