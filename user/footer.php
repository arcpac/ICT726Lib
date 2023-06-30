<div class="wrapper flex-grow-1"></div>
<footer class="container my-md-5 pt-md-5 text-center mt-auto footer" style="border-top: #ff8e00 3px solid">
    <p class="mb-1">All Rights Reserved &copy; 2022 Helios Library</p>
    <p class="mb-1">Created by KC Marie Arce, Antonio Caballes, Diego Cardoso de Moura</p>
</footer>
<script>
$(document).ready(function() {
   // Executed when select is changed
    $("#select_book").on('change',function() {
        var x = this.selectedIndex;

        if (x == "") {
           $("#add_book_in_bc").hide();
        } else {
           $("#add_book_in_bc").show();
        }
    });
    
    // It must not be visible at first time
    $("#add_book_in_bc").css("display","none");
});

</script>

<script>
$(document).ready(function() {
   // Executed when select is changed
    $(".bookcases").on('change',function() {
        var x = this.selectedIndex;
        var book_id = this.id;
        console.log(book_id);

        if (x == "") {
           $("#assign-book-button"+book_id).hide();
        } else {
           $("#assign-book-button"+book_id).show();
        }
    });
    
    // It must not be visible at first time
    $(".bookcases_select").css("display","none");
});

</script>