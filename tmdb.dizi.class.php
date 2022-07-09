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
            $apikey = "bb78e4cf3442e302d928f2c5edcdbee1";
                $kurl = parse_url($url);
                $id = preg_split('#([0-9]+)#', $url, null, PREG_SPLIT_DELIM_CAPTURE)[1];


$cm = curl_init();
curl_setopt($cm, CURLOPT_URL, "http://api.themoviedb.org/3/tv/".$id."?language=tr-TR&append_to_response=videos,credits,content_ratings&api_key=" . $apikey);
curl_setopt($cm, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($cm, CURLOPT_HEADER, FALSE);
curl_setopt($cm, CURLOPT_HTTPHEADER, array("Accept: application/json"));
$response7 = curl_exec($cm);
curl_close($cm);
$moviedata = json_decode($response7,true);
$runtimem     = $moviedata['episode_run_time'][0]. " dk.";
if (isset($moviedata['vote_average'])) {
         $ret['rating'] = $moviedata['vote_average'] == 0 ? '' : $moviedata['vote_average'];
     }
            $film = array();
            $film['name'] = $moviedata['name'];
            $film['namelocal'] = $moviedata['original_name'];
            $film['img'] = 'http://image.tmdb.org/t/p/w500' . $moviedata['poster_path'];
            $film['locations'] = 'http://image.tmdb.org/t/p/original' . $moviedata['backdrop_path'];
            $film['url'] = $url;
            $film['year'] = substr($moviedata['first_air_date'], 0, 4);
            $film['datelocal']    = date("d.m.Y",strtotime($moviedata['first_air_date']));
            $film['runtime'] = $runtimem;
            $film['tagline'] = $moviedata['tagline'];

            // TMDB vote average
            $film['ratinga'] = implode(', ', $ret);

            // TMDB vote count
            $film['ratingc'] = (int) $moviedata['vote_count'];

            // IMDB Rating (from www.omdbapi.com)
            $film['episodes'] = number_format($moviedata['number_of_episodes']);
            $film['seasons'] = number_format($moviedata['number_of_seasons']);

            for ($i = 0; $i < count($moviedata['production_countries']); $i++) {
                $coun_array[$i] = $moviedata['production_countries'][$i]['name'];
            }
            $film['country'] = implode(', ', $coun_array);;
         
            for ($i = 0; $i < count($moviedata['production_companies']); $i++) {
                $compan_array[$i] = $moviedata['production_companies'][$i]['name'];
            }
            $film['productionfirm'] = implode(', ', $compan_array);
           
            for ($i = 0; $i < count($moviedata['genres']); $i++) {
                $genre_array[$i] = $moviedata['genres'][$i]['name'];
            }
            $film['genres'] = implode(', ', $genre_array);

            for ($i = 0; $i < count($moviedata['spoken_languages']); $i++) {
                $lang_array[$i] = $moviedata['spoken_languages'][$i]['name'];
            }
            $film['language'] = implode(', ', $lang_array);

            for ($i = 0; $i < count($moviedata['created_by']); $i++) {
                $direc_array[$i] = $moviedata['created_by'][$i]['name'];
            }
            $film['director'] = implode(', ', $direc_array);
            
            for ($i = 0; $i < count($moviedata['networks']); $i++) {
                $networ_array[$i] = $moviedata['networks'][$i]['name'];
            }
            $film['productionfirm'] = implode(', ', $networ_array);

            // Actors (max 8)
            $cast = $moviedata['credits']['cast'];
            $actors = array();
            $count = 0;
            foreach ($cast as $cast_member) {
                $actors[] = $cast_member['name'];
                $count++;
                if ($count == 8)
                    break;
            }
            $film['actors'] = implode(", ", $actors);

            // Description
            $film['story'] = $moviedata['overview'];

            // Age Ratings
            $mpaa_rating = '';
            $age_rating = '';
            $releases = $moviedata['content_ratings']['results'];
            foreach ($releases as $release_item) {
                if ($release_item['iso_3166_1'] === 'US')
                    $mpaa_rating = $release_item['rating'];
                if ($release_item['iso_3166_1'] === 'DE')
                    $age_rating = $release_item['rating'];
            }
            $film['age'] = $age_rating . '+';
            $film['ratingb'] = $mpaa_rating;
        return $film;
	}

}




?>