<?php

include dirname(__DIR__) . '\database_link.php';
include dirname(__DIR__) . '\database_queries.php';

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
    echo(json_encode($results));
    //GET POSTS

}

?>