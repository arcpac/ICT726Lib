<!-- DISPLAY DATA -->
<?php include '../header.php'; ?>
<form id="update-book-form" class="book-form" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
    <h3>Update Book</h3>
    <label for="title">Book Title</label>
    <input type="text" id="title" name="title" placeholder="Enter book title" value="<?php echo $title; ?>">

    <label for="author">Book Author</label>
    <input type="text" id="author" name="author" placeholder="Enter book author"value="<?php echo $author; ?>">

    <label for="genre">Choose a genre:</label>
    <select name="genre" id="genre">
    <option value="arts">Arts</option>
        <option value="business">Business</option>
        <option value="education">Education</option>
        <option value="general">General</option>
        <option value="health">Health & Medicine</option>
        <option value="history">History & Political Science</option>
        <option value="law">Law</option>
        <option value="literature">Literature & Language</option>
        <option value="religion">Religion & Philosophy</option>
        <option value="social">Social Science</option>
        <option value="science">Science & Technology</option>
    </select>

    <label for="isbn">ISBN</label>
    <input type="text" id="isbn" name="isbn" placeholder="Enter book ISBN"value="<?php echo $isbn; ?>">

    <label for="price">Price</label>
    <input type="text" id="price" name="price" placeholder="Enter book price"value="<?php echo $price; ?>">

    <label for="bookcase">Assign bookcase</label>
    <input type="text" id="bookcase" name="bookcase" placeholder="Enter book case"value="<?php echo $bookcase; ?>">

    <label for="heap">Place book in shelf?</label>
    <select name="heap" id="heap">
        <option value="0">No</option>
        <option value="1">Yes</option>
    </select>
    <input type="submit" value="Add book" id="create-btn" name="save-book">
</form>
<?php include '../footer.php'; ?>