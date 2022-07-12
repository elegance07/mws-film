<?php
/*
=====================================================
 Author : Mehmet Hano&#287;lu <dle.net.tr>
-----------------------------------------------------
 License : MIT License
-----------------------------------------------------
 Copyright (c)
-----------------------------------------------------
 Date : 28.09.2018 [2.5]
=====================================================
*/

if ( ! defined( 'E_DEPRECATED' ) ) {
	@error_reporting( E_ALL ^ E_WARNING ^ E_NOTICE );
	@ini_set( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );
} else {
	@error_reporting( E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE );
	@ini_set( 'error_reporting', E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE );
}

class FilmReader {

	public $config = [
		'location' 				=> "tr-TR", // Portugal: (pt-PT, pt), English-USA: (en-US), English-UK: (en-GB), Turkey: (tr-TR, tr), France (fr-FR, fr), Germany (de-DE), Russia: (ru-RU),
		'actor_count' 			=> 10,
		'runtime_replace' 		=> true,
		'runtime_replace_map' 	=> ["h" => " saat", "min" => " dakika"],  // if you dont want, clear the array. Example: [];
	];

	public function get( $url ) {
		$html = $this->getURLContent( $url );
                
		$apikey = "bb78e4cf3442e302d928f2c5edcdbee1";
                $kurl = parse_url($url);
                $tag = str_replace('/title/', '', $kurl['path']);
                $tag = str_replace('/', '', $tag);
$movie = file_get_contents('http://www.omdbapi.com/?apikey=a17e5208&i='.urlencode($tag));
$movie = json_decode($movie, true);
$cm = curl_init();
curl_setopt($cm, CURLOPT_URL, "http://api.themoviedb.org/3/movie/".$tag."?language=tr-TR&api_key=" . $apikey);
curl_setopt($cm, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($cm, CURLOPT_HEADER, FALSE);
curl_setopt($cm, CURLOPT_HTTPHEADER, array("Accept: application/json"));
$response7 = curl_exec($cm);
curl_close($cm);
$output = json_decode($response7);
$imgurl_2 = "http://image.tmdb.org/t/p/original'$output->backdrop_path'";


		
		
		$film = array(
			'img'			=> $movie['Poster'],
			'namelong'		=> $movie['Title'],
			'name'			=> $movie['Title'],
			'year'			=> $movie['Year'],
			'url'			=> $movie['imdbID'],
			'type'			=> $movie['Type'],
			'crating'		=> $movie['Rated'],
                        'genres'		=> $movie['Genre'],
                        'runtime'		=> $movie['Runtime'],
                        'ratinga'               => $movie['imdbRating'],
                        'ratingb'               => 10,
                        'ratingc'               => $movie['imdbVotes'],
                        'actors'		=> $movie['Actors'],
                        'writers'		=> $movie['Writer'],
                        'director'		=> $movie['Director'],
                        'story'		        => $output->overview,
                        'country'	        => $movie['Country'],
                        'language'		=> $movie['Language'],
                        'datelocal'		=> $movie['Released'],
                        'crating'		=> $movie['Rated'],
                        'color'		        => $movie['Rated'],
                        'budget'		=> $movie['BoxOffice'],
                        'aratio'		=> "$imgurl_2",
                        'localname'		=>  $output->title,
                        'tagline'		=>  $output->tagline,
                     );
		return $film;
			} 
        
	private function getURLContent($url) {
		$ch = curl_init( $url );
		$header[] = "Accept-Language:" . $this->config['location'] . ";q=0.5";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_ENCODING, "utf-8");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
		$output  = curl_exec( $ch );
		curl_close( $ch );
		return $output;
	}
}


/*
@header( "Content-type: text/html; charset=utf-8" );
$f = new FilmReader();
echo "<pre>";
print_r( $f->get("https://www.imdb.com/title/tt0133093/") );
print_r( $f->get("https://www.imdb.com/title/tt0111161/") );
print_r( $f->get("https://www.imdb.com/title/tt0455944/") );
print_r( $f->get("http://www.imdb.com/title/tt0111161/") );
print_r( $f->get("http://www.imdb.com/title/tt2820852/") );
echo "</pre>";
*/
?>
