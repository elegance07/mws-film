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

	
	public function get( $url ) {
		
		$apikey = "23e89da030a0ee8b25aaed20950a0c25";
                $id = str_replace('-', '', preg_split('#([0-9]+[-]{1})#', $url, null, PREG_SPLIT_DELIM_CAPTURE)[1]); // this REGEX takes in case the '-' after the number so it can be better to check with this one if the URL might contain multiple numbers at different positions
$cti = curl_init();
curl_setopt($cti, CURLOPT_URL, "http://api.themoviedb.org/3/movie/".$id."?api_key=" . $apikey);
curl_setopt($cti, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($cti, CURLOPT_HEADER, FALSE);
curl_setopt($cti, CURLOPT_HTTPHEADER, array("Accept: application/json"));
$response14 = curl_exec($cti);
curl_close($cti);
$movie = json_decode($response14,true);

$cm = curl_init();
curl_setopt($cm, CURLOPT_URL, "http://api.themoviedb.org/3/movie/".$id."?language=tr-TR&append_to_response=videos,credits,releases&api_key=" . $apikey);
curl_setopt($cm, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($cm, CURLOPT_HEADER, FALSE);
curl_setopt($cm, CURLOPT_HTTPHEADER, array("Accept: application/json"));
$response7 = curl_exec($cm);
curl_close($cm);
$data = json_decode($response7,true);
if (isset($data['vote_average'])) {
         $ret['rating'] = $data['vote_average'] == 0 ? '' : $data['vote_average'];
     }

        $backdrop    = "http://image.tmdb.org/t/p/original" . $data[backdrop_path];
        $poster      = "http://image.tmdb.org/t/p/w500" . $data['poster_path'];
        $imdb_url    = "http://www.imdb.com/title/" . $data['imdb_id'];
        $year        = substr($data['release_date'], 0, 4);
        
        $tmdbid      = $data['id'];
        $title       = $data['original_title'];
        $description = $data['overview'];
        $status      = $data['status'];
        $homepage    = $data['homepage'];
        $releasen    = date("d.m.Y",strtotime($data['release_date']));
        $runtime     = $movie['runtime'] . " dk.";
        $ltitle      = $data['title'];
        $vote        = implode(', ', $ret);
        $tagline     = $data['tagline'];
        $status      = $data['status'];
        $budget      = number_format($data['budget']) . " \$";
        $revenue     = number_format($data['revenue']) . " \$";
        if ($data['poster_path']!=null){
                $images_small = 'http://image.tmdb.org/t/p/w185' . $data['poster_path'];
        } elseif ($data['backdrop_path']!=null){
                $images_small = 'http://image.tmdb.org/t/p/w185' . $data['poster_path'];
        } else {
                $images_small = '/img/no-backdrop.png';
        }

        if ($data['backdrop_path']!=null){
                $big_images = 'http://image.tmdb.org/t/p/original' . $data['backdrop_path'];
        } elseif ($data['backdrop_path']!=null){
                $big_images = 'http://image.tmdb.org/t/p/original' . $data['backdrop_path'];
        } else {
                $big_images = '/img/no-backdrop.png';
        }
        
        if (is_array($data['genres'])){
                foreach($data['genres'] as $result) {$genre .= $result['name']. ', ';}
        }
        if (is_array($data['spoken_languages'])){
                foreach($data['spoken_languages'] as $result) {$languages .= $result['name'].' ';}
        }
        if (is_array($data['production_companies'])){
                foreach($data['production_companies'] as $result) {$companies .= $result['name'].', ';}
        }
        if (is_array($data['production_countries'])){
                foreach($data['production_countries'] as $result) {$country .= $result['name'].', ';}
        }
        if (is_array($data['videos']['results'])){
                foreach($data['videos']['results'] as $result) {$youtube = "https://www.youtube.com/embed/".$result['key'];}
        }

        $cast = $data['credits']['cast'];
            $actors = array();
            $count = 0;
            foreach ($cast as $cast_member) {
                $actors[] = $cast_member['name'];
                $count++;
                if ($count == 8)
                    break;
            }
            $actors = implode(", ", $actors);

           foreach ($data['credits']['crew'] as $crew) {
  if ($crew['job'] == 'Screenplay') {
    $writer = $crew['name'];
  }
}
        
        if(is_array($data['credits']['crew'])) {
                    foreach($data['credits']['crew'] as $crew) {
                        if ($crew['job'] == 'Director'){

                        $crewMember = $crew['name'];
                    }
                }
              
	      }
            $mpaa_rating = '';
            $age_rating = '';
            $releases = $data['releases']['countries'];
            foreach ($releases as $release_item) {
                if ($release_item['iso_3166_1'] === 'US')
                    $mpaa_rating = $release_item['certification'];
                if ($release_item['iso_3166_1'] === 'DE')
                    $age_rating = $release_item['certification'];
            }
		$film = array(
		        'img'			=> $images_small,
			'namelong'		=> $title,
			'name'			=> $ltitle,
			'year'			=> $year,
			'url'			=> $url,
		        'type'			=> $youtube,
			'sound'		        => $data['status'],
                        'genres'		=> $genre,
                        'runtime'	        => $runtime,
                        'ratinga'               => $vote,
                        'ratingb'               => $mpaa_rating,
                        'ratingc'               => $data['vote_count'],
                        'actors'		=> $actors,
                        'writers'		=> $writer,
                        'director'		=> $crewMember,
                        'story'		        => $description,
                        'country'	        => $country,
                        'language'		=> $languages,
                        'datelocal'		=> $releasen,
                        'color'		        => $revenue,
                        'budget'		=> $budget,
                        'locations'		=> $big_images,
                        'namelocal'		=> $title,
                        'tagline'		=> $tagline,
                        'productionfirm'        => $companies,
                     );
		return $film;
			} 
}



/*		

$f = new FilmReader();
echo "<pre>";
print_r( $f->get( "https://www.themoviedb.org/movie/336843-the-maze-runner-the-death-cure" ) );
print_r( $f->get( "https://www.themoviedb.org/movie/346672-underworld-blood-wars" ) );
print_r( $f->get( "https://www.themoviedb.org/movie/168259-furious-7" ) );
print_r( $f->get( "https://www.themoviedb.org/movie/107846-escape-plan" ) );
print_r( $f->get( "https://www.themoviedb.org/movie/238-the-godfather" ) );
print_r( $f->get( "https://www.themoviedb.org/movie/278-the-shawshank-redemption" ) );
echo "</pre>";
*/


?>
