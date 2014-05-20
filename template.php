<?php

$search = ! empty( $_GET['gifs'] ) ? $_GET['gifs'] : '';
$search_url = $search ? '='. $search : '';

// Get Parbs' gifs
$request = wp_remote_retrieve_body( wp_remote_get( 'http://gifsb.in/gifs.json' ) );
$parbs_gifs = array();
if ( $request ) {
	$parbs_gifs = json_decode( $request );
	$parbs_gifs = isset( $parbs_gifs->data ) ? $parbs_gifs->data : array();
}

// Get Greg's gifs
$request = wp_remote_retrieve_body( wp_remote_get( "http://gregrickaby.com/?gifs$search_url&json" ) );
$gregs_gifs = array();
if ( $request ) {
	$gregs_gifs = json_decode( $request );
	$gregs_gifs = isset( $gregs_gifs->data ) ? $gregs_gifs->data : array();
}

global $jtGiffy, $wp_scripts;

// Halt here for json
if ( isset( $_GET['json'] ) ) {

	$my_gifs = $jtGiffy->gif_urls( $jtGiffy->gif_paths() );
	$my_gifs = $my_gifs ? $my_gifs : array();

	$gifs = array_merge( (array) $my_gifs, (array) $parbs_gifs, (array) $gregs_gifs );

	wp_send_json_success( $gifs );

}

$my_gifs = $jtGiffy->get_gifs();
$gifs = array_merge( (array) $my_gifs, (array) $parbs_gifs, (array) $gregs_gifs );

// bail if no gifs.
if ( ! $gifs )
	return;

ksort( $gifs );

$replace = site_url( '/?gifs=' );

$bgs = array(
	'cube.gif',
	'trippy-triangular-bg.gif',
	'gyroscopic-bg.gif',
	'rock-transform-bg.gif',
	'giffy.gif',
);
$bg = $bgs[ array_rand( $bgs ) ];
$bg = jtGiffySite::$plugin_url .'bgs/'. $bg;

$spin = includes_url( '/images/spinner-2x.gif' );
?>
<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">
	<title>WDS Gifs</title>
	<script src="<?php echo site_url( $wp_scripts->registered['jquery-core']->src ); ?>" type="text/javascript"></script>
	<link href="<?php echo jtGiffySite::$plugin_url .'mclaren/stylesheet.css'; ?>" rel="stylesheet" type="text/css">
	<meta name="description" content="all the gifs">
	<meta name="author" content="Jtsternberg">
	<!-- Sets initial viewport load  -->
	<meta name="viewport" content="initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes">

	<style type="text/css" media="screen">

	* {
		-moz-box-sizing: border-box;
		-webkit-box-sizing: border-box;
		box-sizing: border-box;
	}
	html {
		background: url( '<?php echo $bg; ?>' );
	}
	body {
		font-family: 'mclarenregular', sans-serif !important;
		margin: 0;
	}
	body:before {
		content: '';
		display: block;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(255,255,255,.9);
		z-index: 1;
	}
	.gifs {
		position: relative;
		padding: 1em;
		color:black;
		font-size: 16px;
		z-index: 2;
	}
	.gifs input, .gifs button {
		border-radius: .5em;
		border: 6px solid #00b9f1;
		-moz-box-shadow:    0px 8px 13px -6px #333;
		-webkit-box-shadow: 0px 8px 13px -6px #333;
		box-shadow:         0px 8px 13px -6px #333;
		font-family: 'mclarenregular', sans-serif !important;

	}
	.gifs input {
		font-weight: bold;
		display: block;
		width: 100%;
		padding: .8em;
		color:black;
		font-size: 16px;
		margin: 0 auto;
	}
	.gifs button {
		background: #00b9f1;
		text-transform: lowercase;
		font-size: 1.3em;
		color: #fff;
		-webkit-appearance: none;
		-moz-appearance:    none;
		appearance:         none;
		margin-bottom: .5em;
	}
	::-webkit-input-placeholder {
		color: #00b9f1;
	}
	:-moz-placeholder { /* Firefox 18- */
		color: #00b9f1;
	}
	::-moz-placeholder {  /* Firefox 19+ */
		color: #00b9f1;
	}
	:-ms-input-placeholder {
		color: #00b9f1;
	}
	.gifs a {
		color: #00b9f1;
	}
	.gifs ul {
		clear: both;
		padding: .5em 0;
		margin-top: 10em;
	}
	.gifs li {
		color:black;
		padding:6px;
		display: none;
		list-style-type: none;
	}
	.gifs li a {
		color: #00b9f1;
		/*text-shadow: 1px 1px 0px rgba(0,0,0,.2);*/
		font-weight: bold;
		text-decoration: none;
	}
	#search {
	}
	.gifs .hide {
		display: none;
	}
	.gifs img {
		max-width: 100%;
		max-height: 100%;
		cursor: pointer;
	}
	.not-mobile .gifs li:hover span {
		display: none;
	}
	.not-mobile .gifs li:hover .hide {
		display: inline !important;
	}
	#preview {
		margin-top: .5em;
		position: fixed;
		right: 1em;
		top: 15em;
		max-width: 60%;
		height: auto;
	}
	#all {
		float: left;
	}
	#share {
		float: right;
	}
	#top{
		/*position: fixed;*/
		/*width: 100%;*/
		padding: 1em 1em;
		background: rgba(255,255,255,.8);
		top: 0;
		left: 0;
		position: relative;
		width: auto;
		overflow: hidden;
	}
	#centered {
		text-align: center;
		position: relative;
		top: 0;
		right: 0;
	}
	.gifs ul {
		margin-top: 0em;
	}
	@media screen and (max-width: 400px) {
		#all, #share {
			float: none;
			display: block;
			clear: both;
			margin-left: auto;
			margin-right: auto;
			width: 100%;
		}
		#all.hide, #share.hide {
			display: none;
		}
		#centered {
			text-align: center;
			position: relative;
			top: 0;
			right: 0;
		}
		#preview {
			position: static;
			right: auto;
			top: auto;
			max-width: 100%;
			min-height: 80px;
			min-width: 80px;
			margin: 1em auto 0;
		}
		.gifs li {
			text-align: center;
		}
		.gifs li a {
			display: block;
			text-align: left;
		}

	}
	</style>

</head>
<body class="<?php echo wp_is_mobile() ? 'mobile' : 'not-mobile'; ?>">
	<div class="gifs">
		<div id="top">
			<p>psssst... <a href="https://github.com/jtsternberg/Alfred-Gets-Giffy" target="_blank">Get the alfredapp workflow</a>.</p>
			<form>
				<input autofocus="autofocus" data-search="<?php echo esc_attr( $search ); ?>" type="text" id="search" placeholder="GIF SEARCH - Start typing and hit enter" value=""/>
				<input type="submit" class="hide" value="search">
			</form>
			<!-- <input type="text" class="hide" id="copy" readonly="readonly" value=""/> -->
			<p><button id="all" >Show All</button><button class="hide" id="share" >Share Search</button></p>
		</div>
		<p id="centered"><img id="preview" class="hide" src="" alt=""/></p>
		<ul>
		<?php
		foreach ( $gifs as $filename => $gif ) {
			$name = $gif->name;
			echo '<li data-name="'. $name .'"><a href="'. $gif->src .'" target="_blank"><span>'. $gif->name .'</span><span class="hide">'. $gif->src .'</span></a></li>';
		}
		?>
		</ul>
	</div>

	<script type="text/javascript">
	jQuery(document).ready(function($){

		var $gifs     = $('.gifs');
		var $preview  = $gifs.find( '#preview' );
		var $search   = $gifs.find( '#search' );
		var $centered = $gifs.find( '#centered' );
		var $share    = $gifs.find( '#share' );
		var $lis      = $gifs.find( 'li' );
		var topH      = Math.round( $gifs.find( '#top' ).height() + 50 );
		var doSubmit  = false;
		var isMobile  = $('.gifs').hasClass( 'mobile' );
		var doFocus   = true;
		var $item     = null;

		var doSearch = function( val ) {
			if ( val ) {
				doSubmit = true;
				$search.val( val );
			}

			if ( doFocus ) {
				$search.focus().select()
					.get( 0 ).setSelectionRange(0, 9999);
			}
			doFocus = true;
		}

		var doPreview = function( src, clicked_preview ) {
			$preview
				.attr( 'src', '<?php echo $spin; ?>' )
				.attr( 'src', src )
				.fadeIn()
				.css({ 'max-height': Math.round( $(window).height() - topH ) });

			if ( isMobile && ( $item || clicked_preview ) ) {
				if ( true === clicked_preview ) {
					$centered.append( $preview );
				} else {
					$item.append( $preview );
					$item = null;
				}

			}

		}

		var triggerURL = function( url, clicked_preview ) {

			doSearch( url );
			doPreview( url, clicked_preview );
		}

		setTimeout( function() {
			if ( $search.data( 'search' ) ) {
				$search.val( $search.data( 'search' ) ).trigger('keyup');
				$share.show();
			}
		}, 50 );

		$gifs
			.on( 'keyup', '#search', function() {
				if ( doSubmit ) {
					doSubmit = false;
					return;
				}
				$gifs.css({ 'background': '#fff' });
				var search = $(this).val().toUpperCase();
				var first  = true;
				$search.data( 'cache', search );

				$preview.hide();

				if ( ! search )
					return;

				$share.show();
				$lis.hide().each( function() {
					var $self = $(this);

					if ( $self.data( 'name' ).toUpperCase().indexOf( search ) != -1 ) {
						$self.show();
						if ( first ) {
							doPreview( $self.find( 'a' ).attr('href') );
						}
						first = false;
					}

				});
			}).on( 'submit', 'form', function( evt ) {
				evt.preventDefault();
				$first = $gifs.find('li:visible:first a');
				if ( $first.length ) {
					doSearch( $first.attr('href'), true );
				}
			}).on( 'click', '#all', function( evt ) {
				evt.preventDefault();
				$search.val('');
				$preview.hide();
				$gifs.find( 'li' ).show();
			}).on( 'click mouseenter', 'li a', function( evt ) {
				evt.preventDefault();
				$preview.hide();
				if ( isMobile ) {
					doFocus = false;
					$item = $(this).parents('li');
				}
				triggerURL( $(this).attr('href') );
				doPreview( $(this).attr('href') );
			}).on( 'click', '#share', function( evt ) {
				evt.preventDefault();
				var url = '<?php echo $replace; ?>'+ encodeURIComponent( $search.data( 'cache' ).toLowerCase() );
				$lis.hide();
				doSearch( url );
			})
			.on( 'click', '#preview', function( evt ) {
				evt.preventDefault();
				// $gifs.find( 'li' ).hide();
				triggerURL( $(this).attr('src'), true );
			})
			.on( 'focus', '#search', function( evt ) {
				// $self = $(this);
				// setTimeout( function(){
				// 	$self.select();
				// }, 20 );
			});

			if(window.location.hash){
				doSearch( window.location.hash.substring(1) );
			}
	});
	</script>
	<?php wp_footer(); ?>
</body>
</html>
<?php
exit;
