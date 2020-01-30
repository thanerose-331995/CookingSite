<?php

$cd = $_SERVER["DOCUMENT_ROOT"];

include $cd.'/php/database_link.php';
include $cd.'/php/database_queries.php';

if($_GET){

    $search = $_GET['search'];

    $post_titles = [];
    $post_content = [];
    $profiles = [];
    $results = [];

    //GET PROFILES 
    $profiles = get_all("user_profiles");

    foreach($profiles as $user){
        $names = explode(" ", $search);
        foreach($names as $name){
            if((strpos($user['first_name'], $name) !== false) || (strpos($user['last_name'], $name) !== false)){
                $user['type'] = "PROFILE";
                array_push($results, $user);
            }
        }
    }

    //GET POSTS
    $posts = get_all("posts");
    
    foreach($posts as $post){

        $title = $post['title'];
        if(strpos($title, $search) !== false){
            $post['type'] = "POST-TITLE";
            array_push($results, $post);
        }

        $content = $post['content'];
        if((strpos($content, $search) !== false) && (in_array($post, $post_titles) === false)){
            $post['type'] = "POST-CONTENT";
            array_push($results, $post);
        }
    }

    //TAGS
    $tags = get_all("tags");

    foreach($tags as $tag){
        $name = $tag['name'];
        if(strpos($name, $search) !== false){
            $res = post_from_tag($tag['name']);
            foreach($res as $r){
                $r['type'] = "POST-CONTENT";
                array_push($results, $r);
            }
        }
    }


    echo(json_encode($results));
}

?>