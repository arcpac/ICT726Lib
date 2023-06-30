<div class="wrapper flex-grow-1"></div>
<footer class="container-fluid my-md-5 pt-md-5 text-center mt-auto footer border-top" style="color: white; background-color: #5f6d79!important;">
    <p class="mb-1">All Rights Reserved &copy; 2022 Helios Library</p>
    <p class="mb-1">Created by KC Marie Arce, Antonio Caballes, Diego Cardoso de Moura</p>
   <br/><br/>
</footer>

<!-- <script src="../assets/js/jquery.js"></script> -->
<script src="../assets/js/sweetalert2.all.min.js"></script>
<?php if (isset($_GET['d'])) : ?>
    <div class="flash-data" data-flashdata="<?= $_GET['d']; ?>"></div>
<?php endif ?>
<script>
    $(document).ready(function() {
        $(".alert").fadeOut(3000);
    });

    $('.del_btn').on('click', function(e) {
        e.preventDefault();
        const href = $(this).attr('href')

        Swal.fire({
            title: 'Do you want to delete?',
            showCancelButton: true,
            confirmButtonText: 'Delete',
        }).then((result) => {
            if (result.value) {
                document.location.href = href;
            }
        })
    })
    // FLASH DATA
    const flashdata = $('.flash-data').data('flashdata')
    if (flashdata) {
        Swal.fire(
            'Record deleted'
        )
    }
</script>