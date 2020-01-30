<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//function for preparing the obj for execution
function qString($res, $names, $values)
{
    if (gettype($values) == "array") {
        foreach ($values as $i => $val) {
            $res->bindParam($names[$i], $val);
        }
    } else {
        $res->bindParam($names, $values);
    }
    return $res;
}

function login($username, $password)
{
    $password = hash('sha256', $password);
    $sql = "SELECT * FROM users WHERE username=:username AND password=:password";
    $res = db()->prepare($sql);
    $res = qString($res, [':username', ':password'], [$username, $password]);
    $res = $res->execute();

    var_dump($res);

    if ($res) {
        return 1; //if exists it'll return 1, else 0
    }
}

function check_user($username)
{
    $sql = "SELECT * FROM users WHERE username=:username";
    $res = db()->prepare($sql);
    $res = qString($res, ':username', $username);
    $res->execute();

    if ($res->rowCount()) {
        return $res->fetch(); //if exists it'll return 1, else 0
    }
}

function check_tag($tag)
{
    $sql = "SELECT * FROM tags WHERE name=:name";
    $res = db()->prepare($sql);
    $res = qString($res, ':name', $tag);
    $res->execute();
    if ($res->rowCount()) {
        return true;
    }
}

// get profile
function get_profile($id)
{
    $sql = "SELECT user_profiles.* FROM users JOIN user_profiles ON user_profiles.id = users.id WHERE users.id =:id";
    $res = db()->prepare($sql);
    $res = qString($res, ':id', $id);
    $res->execute();
    return $res->fetch();
}

function get_posts($username)
{

    if ($username == "all") {
        $res = db()->query("SELECT * FROM posts");
        $sql = "SELECT * FROM posts";
    } else {
        $sql = "SELECT 	posts.* FROM users JOIN posts WHERE posts.user_id =:username";
    }
    $res = db()->prepare($sql);
    $res = qString($res, ':username', $username);
    $res->execute();
    echo (json_encode($res->fetchAll()));
}

function load_tags($postID)
{
    $sql = "SELECT tags.name FROM posts JOIN post_tags ON post_tags.post_id = posts.id JOIN tags ON post_tags.tag_id = tags.id WHERE posts.id =:postID";
    $res = db()->prepare($sql);
    $res = qString($res, ':postID', $postID);
    $res->execute();
    echo (json_encode($res->fetchAll()));
}

function load_images($postID)
{
    $sql = "SELECT images.upload_path FROM post_images JOIN images	ON images.id = post_images.image_id WHERE post_images.post_id =:postID";
    $res = db()->prepare($sql);
    $res = qString($res, ':postID', $postID);
    $res->execute();
    echo (json_encode($res->fetchAll()));
}

function post_from_tag($tag){
    $res = db()->query("SELECT posts.* FROM tags JOIN post_tags ON post_tags.tag_id = tags.id JOIN posts ON post_tags.post_id = posts.id WHERE tags.name = '$tag'");
    return $res->fetchAll();
}

//GET ANYTHING
function get($get, $table, $data)
{
    $find = $data[0];
    $val = $data[1];
    $sql = "SELECT $get FROM $table WHERE $find = :val";
    $res = db()->prepare($sql);
    $res = qString($res, ':val', $val);
    $res->execute();
    return $res->fetchAll();
}


function get_all($table)
{
    $sql = "SELECT * FROM $table";
    $res = db()->query($sql);
    return $res->fetchAll();
}

function get_id($table, $value, $input)
{
    var_dump($table, $value, $input);
    $sql = "SELECT id FROM $table WHERE :value=':input'";
    $res = db()->prepare($sql);
    $res = qString($res, [':value', ':input'], [$value, $input]);
    $res->execute();
    return $res->fetch();
}

//UPDATE ANYTHING
function update($table, $values, $where)
{
    $res = db()->query("UPDATE $table SET $values WHERE $where");
    return $res;
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
    //SEND AND RETURN REQUEST
    $res = db()->query("INSERT INTO $table ($set1)  VALUES ($set2)");
    return $res->rowCount();
}

//DELETE ANYTHING
function remove($table, $column, $value)
{
    $res = db()->query("DELETE FROM $table WHERE $column = $value");
}
