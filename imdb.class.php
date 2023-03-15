<?php
/*
=====================================================
 Author : Mehmet Hanoğlu <dle.net.tr>
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
		'location' 			=> "tr-TR", // Portugal: (pt-PT, pt), English-USA: (en-US), English-UK: (en-GB), Turkey: (tr-TR, tr), France (fr-FR, fr), Germany (de-DE), Russia: (ru-RU),
		'actor_count' 			=> 10,
		'runtime_replace' 		=> true,
		'runtime_replace_map' 	=> ["h" => " saat", "min" => " dakika"],  // if you dont want, clear the array. Example: [];
	];
        
        

	public function get( $url ) {
		
                
		
                $kurl = parse_url($url);
                $tag = str_replace('/title/', '', $kurl['path']);
                $tag = str_replace('/', '', $tag);
                $html = $this->getURLContent( $tag );
		
		$film = array(
			'img' => $html['Poster'],
                        'namelong' => $html['Title'],
                        'name' => $html['Title'],
                        'year' => $html['Year'],
                        'url' => $url,
                        'type' => $html['Type'],
                        'crating' => $html['Rated'],
                        'soundtracks' => $html['Title'],
                        'runtime' => $html['Runtime'],
                        'genres' => $html['Genre'],
                        'ratinga' => $html['imdbRating'],
                        'ratingb' => $html['Title'],
                        'ratingc' => $html['imdbVotes'],
                        'story' =>  $html['Plot'],
                        'country' => $html['Country'],
                        'locations' => $html['Country'],
                        'language' => $html['Language'],
                        'productionfirm' => $html['Production'],
                        'datelocal' => $html['Released'],
                        'namelocal' => $html['Title'],
                        'color' => $html['Awards'],
                        'sound' => $html['Title'],
                        'budget' => $html['BoxOffice'],
                        'aratio' => $html['DVD'],
                        'tagline' => $html['Title'],
                        'orgimg' => $html['Poster'],
                        'writers' => $html['Writer'],
                        'actors' => $html['Actors'],
                        'director' => $html['Director'],
		);
	

		return $film;
	}

	private function cleanWords( $text ) {
		$id = str_replace('-', '', preg_split('#([0-9]+[-]{1})#', $url, null, PREG_SPLIT_DELIM_CAPTURE)[1]);
		return $text;
	}

	//requests the show with $id from omdb api
	private function getURLContent($tag){
		//the url to request from
		$nurl = "http://www.omdbapi.com/?apikey=a7ec2b75&i=$tag&plot=short&r=json";

		//set up curl to make the HTTP request
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $nurl);
		$result = curl_exec($ch);
		curl_close($ch); //close connection

		//decode the result from JSON to an array
		$result = json_decode($result, true);

		//return the result or an empty array
		if(count($result)>0) return $result;
		else return array();
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
    
