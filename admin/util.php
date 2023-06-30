<?php

function author_name($author_id){
    include('config.php');
    $query = "SELECT * FROM author WHERE id=$author_id LIMIT 1";
    $query = mysqli_query($connection, $query); 
    $query = mysqli_fetch_array($query); 
    $fname = $query['firstname'];
    $lname = $query['lastname'];
    $result = $fname. " " .$lname;
    return $result;
}

function genre_name($genre_id){
    include('config.php');
    $query = "SELECT * FROM genre WHERE id=$genre_id LIMIT 1";
    $query = mysqli_query($connection, $query); 
    $query = mysqli_fetch_array($query); 
    return $query['genre'];
}

function get_user($user_id){
    include('config.php');
    $query = "SELECT * FROM users WHERE id=$user_id LIMIT 1";
    $query = mysqli_query($connection, $query); 
    $query = mysqli_fetch_array($query); 
    return $query['username'];
}

function author_has_books($author_id) {
    include('config.php');
    $query = "SELECT * FROM author INNER JOIN books ON $author_id = books.author_id";
    $query = mysqli_query($connection, $query);
    $query = mysqli_num_rows($query); 
    if ($query > 0) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function genre_has_books($genre_id) {
    include('config.php');
    $query = "SELECT * FROM genre INNER JOIN books ON $genre_id = books.genre_id";
    $query = mysqli_query($connection, $query);
    $query = mysqli_num_rows($query); 
    if ($query > 0) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}
?>