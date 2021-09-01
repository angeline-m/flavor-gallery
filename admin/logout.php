<?php
    session_start();
    include("../includes/header.php");
    session_destroy();
?>

<h1 class="text-dark">You have been logged out.</h1>
<p>Thank you for visiting the Image Gallery. If you wish to return, <a href="login.php">click here to login</a>.</p>

<?php
    include("../includes/footer.php");
?>