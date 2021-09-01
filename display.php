<?php
    include("includes/header.php");
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    if (!isset($id))
    {
        $result = mysqli_query($con, "SELECT * FROM ama_gallery ORDER BY id DESC LIMIT 1") or die(mysqli_error($con));
        while($row = mysqli_fetch_array($result)){
            $id = $row['id'];
            $title = $row['ama_title'];
            $description = $row['ama_description'];
            $filename = $row['ama_filename'];
        }
    }
    else 
    {
        $result = mysqli_query($con, "SELECT * FROM ama_gallery where id = '$id'") or die(mysqli_error($con));
        while($row = mysqli_fetch_array($result)){
            $title = $row['ama_title'];
            $description = $row['ama_description'];
            $filename = $row['ama_filename'];
        }
    }
?>

<div class="p-3 mb-3">
    <h1 class="text-dark"><?php echo $title ?></h1>
    <img src="<?php echo BASE_URL ?>img/display/<?php echo $filename ?>" alt="<?php echo $title ?>">
    <p><?php echo $description ?></p>
    <a href="<?php echo BASE_URL ?>admin/edit.php?id=<?php echo $id ?>"><button type="button" class="btn btn-primary">Edit Image</button></a>
</div>

<?php
    include("includes/footer.php");
?>