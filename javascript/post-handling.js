
var loc = window.location.pathname;
var dir = loc.substring(0, loc.lastIndexOf('/'));


$(document).ready(function () {
    $("#selected-post").hide();
})

var posts = [];
var user;

//USER

var userID;

function loadProfile() {
    $.get("/php/get_profile.php", { type: 'username' },
        function (res) {
            res = JSON.parse(res);
            userID = res['id'];
            $("#userID").val(userID);
            var names = allUpper([res['first_name'], res['last_name']]);
            var name = names[0] + " " + names[1];

            $("#users-name").html(name);
            dir = dir.split("php")[0];
            $("#profile-picture").css("background-image", "url(/uploads/" + res['pfp_url'] + ")");

        })
};

function checkID(id) {
    if (id != userID) {
        $("#delete").hide();
    }
}

//-------------
//POST HANDLING
//-------------

//LOAD THE POSTS
function loadPosts(id) {
    var data;
    user = id;
    $.get('/php/loads/load_posts.php', { posts: id },
        function (res) {
            res = JSON.parse(res)
            data = res;
            data.forEach(post => {
                $.get('/php/get_profile.php', { user_id: post['user_id'], type: "null" },
                    function (res) {
                        res = JSON.parse(res);
                        var fName = res['first_name'];
                        var lName = res['last_name'];
                        var name = (fName.replace(fName[0], fName[0].toUpperCase())) + " " + (lName.replace(lName[0], lName[0].toUpperCase()))
                        post['username'] = name;
                    }
                )
            })
        }
    ).done(function () {
        getTags(data);
    });
}

//GET TAGS OF POSTS
function getTags(data) {
    var count = 0;
    data.forEach(x => {
        $.get('/php/loads/load_tags.php', { id: x['id'] },
            function (res) {
                var tagstring = "";
                res = JSON.parse(res);
                res.forEach(i => {
                    tagstring += (i['name'] + "_");
                });
                x['tags'] = tagstring;
                count++;
            }
        ).done(function () {
            if (count == data.length) {
                getImages(data);
            }
        })
    });
}

//GET IMAGES OF POSTS
function getImages(data) {
    var count = 0;
    data.forEach(x => {
        $.get('/php/loads/load_images.php', { id: x['id'] },
            function (res) {
                res = JSON.parse(res);
                if (res.length == 0) {
                    x['img_url'] = "";
                } else {
                    x['img_url'] = res[0]['upload_path'];
                }
                count++;
            }
        ).done(function () {
            if (count == data.length) {
                complete(data);
            }
        })
    });
}

//ATTACH TO CONTAINER
function complete(data) {
    data.reverse();
    var count = 0;
    data.forEach(x => {
        if (count < 6) {
            var exists = false;
            posts.forEach(i => {
                if (i['id'] == x['id']) {
                    exists = true;
                }
            })

            if (!exists) {
                $(".container").append(
                    "<div class='post-container'>" +
                    "<div class='post' id=" + x['id'] + " onclick='postClicked(" + x['id'] + ")'>" +
                    "<div class='overlay'>" +
                    "<h4>" + x['title'] + "</h4>" +
                    "<h5>" + x['content'] + "</h5>" +
                    "</div>" +
                    "</div>" +
                    "<div class='likes-comments'>" +
                    "<div class='likes'>" +
                    "<div class='like'></div>" +
                    "<h6>" + x['likes'] + "</h6>" +
                    "</div>" +
                    "<div class='comm-numb'>" +
                    "<div class='comm-icon'></div>" +
                    "<h6>" + x['comments'] + "</h6>" +
                    "</div>" +
                    "</div>" +
                    "</div>"
                );
                $("#" + x['id']).css("background-image", "url(/uploads/" + x['img_url'] + ")");

                count++;
                posts.push(x)
            }
        }
    });
}

//SHOW FULL POST
function postClicked(id) {
    var post;
    posts.forEach(x => {
        if (x['id'] == id) {
            post = x;
        }
    })

    var deleteString = '"POST:' + post['id'] + '"';
    if (post['tags'] != "") {
        var temp = post['tags'].split("_");
        var tags = "";
        temp.forEach(function (t, i) {
            var loc
            tags = tags + "<p><a href='/php/search.php?search=" + t + "'>" + t + "</a></p>";
        })
    }
    //ADD THE POST CONTENT
    $("#selected-post").fadeIn();
    $("#selected-post").empty();
    $("#selected-post").append(
        "<div class='post' id=" + post['id'] + " onclick='clicked(" + post['id'] + ")'>" +
        "<div id='title'>" +
        "<h3>" + post['title'] + "</h3>" +
        "</div>" +
        "<div id='this-image'></div>" +
        "<div id='desc'>" +
        "<p>" + post['content'] + "</p>" +
        "<h5>" + post['dT'].split(' ')[0] + "</h5>" +
        "<div class='likes-comments'>" +
        "<div class='likes'>" +
        "<div class='like' onclick='likePost(" + post['id'] + "," + post['likes'] + ")'></div>" +
        "<p>" + post['likes'] + "</p>" +
        "</div>" +
        "<div class='comm-numb'>" +
        "<div class='comm-icon'></div>" +
        "<p>" + post['comments'] + "</p>" +
        "</div>" +
        "</div>" +
        "<p>" + tags + "</p>" +
        "</div>" +
        "<div id='recipe'>" +
        "<h4>Recipe</h4>" +
        "<div id='r-steps'></div>" +
        "</div>" +
        "<div id='ingredients'>" +
        "<h4>Ingredients</h4>" +
        "<div id='r-ing'></div>" +
        "</div>" +
        "<div id='comments'>" +
        "<div id='selected-post-comments'></div>" +
        "<input type='text' id='comment'>" +
        "<button type='button' onclick='sendComment(" + (id) + ")'>Comment</button>" +
        "</div>" +
        "</div>" +
        "<div id='close'>" +
        "<button type='button' onclick='closeClickedPost()'>Close</button>" +
        "<button type='button' onclick='remove(" + deleteString + ")' id='delete'>Delete</button>" +
        "<button type='button' onclick='edit(" + post['id'] + ")' id='edit'>Edit</button>" +
        "</div>"
    );

    if (post['steps'] !== null) {
        var steps = post['steps'].split("/");
        steps.forEach(function (step, index) {
            $("#r-steps").append(
                "<div class='item'>" +
                "<h4>" + (index + 1) + "</h4>" +
                "<h5>" + step + "</h5>" +
                "</div>"
            )
        })
    }
    if (post['ingredients'] !== null) {
        var ing = post['ingredients'].split("/");
        ing.forEach(function (i, index) {
            $("#r-ing").append(
                "<div class='item'>" +
                "<h4>" + (index + 1) + "</h4>" +
                "<h5>" + i + "</h5>" +
                "</div>");
        })
    }
    console.log(post['img_url']);

    $("#this-image").css("background-image", "url(/uploads/" + post['img_url'] + ")");

    checkID(post['user_id']);

    getComments(id);

}

function closeClickedPost() {
    $("#selected-post").fadeOut("fast");
}

//SHOW COMMENTS
function getComments(id) {

    $("#selected-post-comments").empty();
    //ADD COMMENTS FOR THIS POST
    comments = [];
    $.get('/php/loads/load_comments.php', { postID: id },
        function (res) {
            res = JSON.parse(res);
            res.forEach(x => {
                comments.push(x);
            })
        }
    ).done(function () {
        //ADD COMMENTS TO SCREEN 
        if (comments.length == 0) {
            $("#selected-post-comments").append("No comments to show");
        } else {
            comments.reverse();
            comments.forEach(x => {
                $("#selected-post-comments").append(
                    "<div class='comm'>" +
                    "<h5>" + x['comment'] + "</h5>" +
                    "<div class='comm-info'>" +
                    "<h5 class='username'><a href='./profile.php?id=" + x['user_id'] + "'>" + x['username'] + "</a></h5>" +
                    "<h6>" + x['dT'].split(' ')[0] + "</h6>" +
                    "<div class='like' onclick='likeComment(" + x['id'] + "," + x['likes'] + "," + id + ")'></div>" +
                    "<h6>" + x['likes'] + "</h6>" +
                    "</div>" +
                    "</div>"
                );
            })
        }
    });

}

//ADD COMMENTS
function sendComment(id) {

    if ($("#comment").val() != "") {

        $.get('/php/adds/add_comment.php',
            {
                postID: id,
                comment: $("#comment").val()
            },
            function () {
            }
        ).done(function () {
            posts.forEach(x => {
                if (x['id'] == id) {
                    x['comments'] = parseInt(x['comments']) + 1;
                }
            })
            postClicked(id);
        });
    }
}

//LIKE COMMENT/POST
function likeComment(id, currentLikes, postID) {
    $.get('/php/adds/add_like.php',
        {
            type: "COMMENT",
            id: id,
            current: currentLikes
        },
        function () {
        }
    ).done(function () {
        getComments(postID);
    });
}

function likePost(id, currentLikes) {
    $.get('/php/adds/add_like.php',
        {
            type: "POST",
            id: id,
            current: currentLikes
        },
        function () {
        }
    ).done(function () {
        posts.forEach(x => {
            if (x['id'] == id) {
                x['likes'] = parseInt(x['likes']) + 1;
            }
        })
        postClicked(id);
    });
}


function checkID(id) {
    if (id != userID) {
        $("#delete").hide();
        $("#edit").hide();
    }
}

function del(string) {
    var str = string.split(":");

    var table;
    var column;
    var value;

    if (str[0] == "POST") {
        table = "posts";
        column = "id";
        value = str[1];
    }
    else if (str[0] == "USER") {
        table = "users";
        column = "id";
        value = str[1];
    }

    $.get('/php/delete.php', { table: table, column: column, value: value },
        function (res) {
            console.log(res);
        }
    ).done(function () {
    });
}

//GENERAL FUNCTIONS
function allUpper(array) {
    if (typeof array == 'string') {
        //one word
        array = array.replace(array[0], array[0].toUpperCase());
        return array;
    }
    else {
        //multiple words
        var newArray = [];
        array.forEach(word => {
            word = word.replace(word[0], word[0].toUpperCase());
            newArray.push(word);
        });
        return newArray;
    }
}