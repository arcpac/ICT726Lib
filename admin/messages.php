<?php
// SUCCESS NOTIFS
if (count($messages) > 0) {
    foreach ($messages as $message) { ?>
        <div class="alert alert-info" role="alert">
            <?php echo $message ?>
        </div>
<?php
    }
}
?>