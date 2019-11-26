<?php


function login($username, $password)
// login only
{
    $result = db()->query("SELECT * FROM users WHERE username='$username' AND password='$password'");
    if ($result->rowCount()) {
        return 1; //if exists it'll return 1, else 0
    }
}

function check_user($username)
{
    $result = db()->query("SELECT * FROM users WHERE username='$username'");
    if ($result->rowCount()) {
        return $result->fetch(); //if exists it'll return 1, else 0
    }
}

function get_id($username){
    $res = db()->query("SELECT * FROM users WHERE username='$username'");
    return $res->fetch();
}

function get_profile($username)
// get profile
{
    $result = db()->query("SELECT user_profiles.* FROM users JOIN user_profiles ON user_profiles.id = users.id WHERE users.username = '$username'");
    return $result->fetch();
}

function get_posts($username){

    if($username == "all"){
        $res = db()->query("SELECT * FROM posts");
    }
    else{        
        $res = db()->query("SELECT 	posts.* FROM users JOIN posts ON posts.user_id = users.id WHERE users.username = '$username'");
    }

    

    $x = 0;
    while($x<$res->rowCount()){
        $x++;
        $data = $res->fetch();
        echo("<div><p> Title: ".$data['title']."</p><p>Content: ".$data['content']."</p><p> User: ".$data['user_id']."</p><div><br>");
    }

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
    echo ("QUERY SENT: " . "INSERT INTO $table ($set1)  VALUES ($set2)" . " //// ");

    //SEND AND RETURN REQUEST
    $result = db()->query("INSERT INTO $table ($set1)  VALUES ($set2)");
    return $result;
}
