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

function put_shelf($bookcase, $bookcase_book_count) {
    $shelves = $bookcase['no_of_shelf'];
    $shelves_capacity = $bookcase['shelf_capacity'];
    $next_slot = get_next_slot($shelves_capacity, $bookcase_book_count);
    $next_slot = intval(floor($next_slot));
    return $next_slot;
}

function bookcase_count($user_id) {
    include('config.php');
    $bookcase_user = mysqli_query($connection, "SELECT count(1) FROM bookcase WHERE user_id=$user_id and bookcase.archive=0");
    $bookcase_user_count = mysqli_fetch_array($bookcase_user);
    $bookcase_total = $bookcase_user_count[0];

    return $bookcase_total;

}

function book_count($user_id) {
    include('config.php');
    $bookcase_user = mysqli_query($connection, "SELECT count(*) FROM bookcase LEFT JOIN books on bookcase.id = books.bookcase_id WHERE bookcase.user_id=$user_id and bookcase.archive=0");
    $bookcase_user_count = mysqli_fetch_array($bookcase_user);
    $book_total = $bookcase_user_count[0];

    return $book_total;
}

function check_bookcase_capacity($bookcase_id){
    include('config.php');
    $bookcase = mysqli_query($connection, "SELECT * FROM bookcase WHERE id=$bookcase_id");
    $bookcase = mysqli_fetch_array($bookcase);
    $bookcase_book_count = mysqli_query($connection, "SELECT * FROM books WHERE bookcase_id=$bookcase_id");
    $bookcase_book_count = mysqli_num_rows($bookcase_book_count);
    if ($bookcase_book_count < $bookcase['capacity']) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function get_bookcase($connection, $bookcase_id) {
    $bookcase = mysqli_query($connection, "SELECT * FROM bookcase WHERE id=$bookcase_id");
    $bookcase = mysqli_fetch_array($bookcase);
    return $bookcase;
}

function get_next_slot($shelves_capacity, $bookcase_book_count)
{
    $bookcase_book_count = intval($bookcase_book_count);
    $shelves_capacity = intval($shelves_capacity);

    $next_slot = $bookcase_book_count / $shelves_capacity;
    $next_slot = $next_slot + 1;
    return $next_slot;
}

?>