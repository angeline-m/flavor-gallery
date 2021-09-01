<?php
    include("includes/header.php");
?>

<div class="p-3 mb-3 bg-light">
    <h1 class="text-dark">Flavor Gallery</h1>
    <p>To insert, update, or delete images, please login. Login credentials in Github repository.</p>
    <br>
    <div class="row">
            <?php
                $result = mysqli_query($con, "SELECT * FROM ama_gallery ORDER BY id") or die(mysqli_error($con));
                while($row = mysqli_fetch_array($result)){
                    echo "<div class=\"col-sm-6 col-md-4 col-lg-3 mb-3\">";
                    echo "<a href=\"";
                    echo BASE_URL;
                    echo "display.php?id=";
                    echo $row['id'];
                    echo "\">";
                    echo "<img src=\"";
                    echo "img/thumbs/" . $row['ama_filename'];
                    echo "\" alt=\"";
                    echo $row['title'];
                    echo "\">";
                    echo "<p>" . $row['ama_title'] . "</p>";
                    echo "</a>";
                    echo "</div>";
                }
            ?>
    </div>
</div>

<?php
    include("includes/footer.php");
?>