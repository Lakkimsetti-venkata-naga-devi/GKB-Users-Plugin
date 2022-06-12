<div class="container ">
    <div class="users_import">
        <h3>Please Upload or drag and drop the CSV file to import the users</h3>
        <?php $totalInserted = 0; if($totalInserted){?>
        <h3 style='color: green;'>Total record Inserted : <?php echo $totalInserted ? $totalInserted : '';?></h3>
        <?php }?>
        <form method='post' action='<?= $_SERVER['REQUEST_URI']; ?>' enctype='multipart/form-data'>
            <input type="file" name="import_file" >
            <input type="submit" name="butimport" value="Import">
        </form>
    </div>
</div>