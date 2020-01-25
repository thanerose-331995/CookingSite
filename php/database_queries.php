<?php

function login($username, $password){
    $password = hash('sha256', $password);
    $result = db()->query("SELECT * FROM users WHERE username='$username' AND password='$password'");
    if ($result->rowCount()) {
        return 1; //if exists it'll return 1, else 0
    }
}

function check_user($username){
    $result = db()->query("SELECT * FROM users WHERE username='$username'");
    if ($result->rowCount()) {
        return $result->fetch(); //if exists it'll return 1, else 0
    }
}

function check_tag($tag){
    $res = db()->query("SELECT * FROM tags WHERE name='$tag'");
    if($res->rowCount()){
        return true;
    }
}

// get profile
function get_profile($id){
    $res = db()->query("SELECT user_profiles.* FROM users JOIN user_profiles ON user_profiles.id = users.id WHERE users.id = '$id'");
    return $res->fetch();
}

function get_posts($username){

    if($username == "all"){
        $res = db()->query("SELECT * FROM posts");
    }
    else{        
        $res = db()->query("SELECT 	posts.* FROM users JOIN posts WHERE posts.user_id = '$username'");
    }
    echo(json_encode($res->fetchAll()));
}

function load_tags($postID){
    $res = db()->query("SELECT tags.name FROM posts JOIN post_tags ON post_tags.post_id = posts.id JOIN tags ON post_tags.tag_id = tags.id WHERE posts.id = '$postID'");
    echo(json_encode($res->fetchAll()));
}

function load_images($postID){
    $res = db()->query("SELECT	images.upload_path FROM post_images JOIN images	ON images.id = post_images.image_id WHERE post_images.post_id = '$postID'");
    echo(json_encode($res->fetchAll()));
}



//GET ANYTHING
function get($get, $table, $data){
    $res = db()->query("SELECT $get FROM $table WHERE $data");
    return $res->fetchAll();
}

function get_all($table){
    $res = db()->query("SELECT * FROM $table");
    return $res->fetchAll();
}

function get_id($table, $value, $input){
    $res = db()->query("SELECT id FROM $table WHERE $value='$input'");
    return $res->fetch();
}

//UPDATE ANYTHING
function update($table, $values, $where){
    $result = db()->query("UPDATE $table SET $values WHERE $where");
    return $result;
}

//ADD ANYTHING
function add($table, $parameters, $values)
{
    //HASHING
    foreach ($parameters as $x => $value) {
        //SCRAMBLE A PASSWORD
        if (!is_bool(strpos($value, "password"))) {
            $values[$x] = hash('sha256', $values[array_search('password', $parameters)]);
        }
    }

    //FORMATTING READY FOR SENDOFF
    foreach ($values as $x => $value) {
        if (is_bool(strpos($values[$x], "PASSWORD"))) {
            $values[$x] = "'" . $values[$x] . "'";
        }
    }

    //IMPLODE ARRAY TO REQUEST STRING
    $set1 = implode(", ", $parameters);
    $set2 = implode(", ", $values);

    //DEBUG
    //echo ("QUERY SENT: " . "INSERT INTO $table ($set1)  VALUES ($set2)" . " //// ");

    //SEND AND RETURN REQUEST
    $result = db()->query("INSERT INTO $table ($set1)  VALUES ($set2)");
    return $result;
}

//DELETE ANYTHING
function remove($table, $column, $value){
    $res = db()->query("DELETE FROM $table WHERE $column = $value");
}
