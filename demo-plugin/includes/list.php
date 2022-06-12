<?php 
    global $wpdb;
    $tblname = 'gkb_users';
    $wp_track_table = $wpdb->prefix . "$tblname";
    $rows =  $wpdb->get_results( 'SELECT * FROM '.$wp_track_table, ARRAY_A);
?>
<div class="users_lists">
<table id="users_list" class="display">
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Hobbies</th>
            <th>Gender</th>
            <th>Profile</th>
        </tr>
    </thead>
    <tbody>
        <?php $i=1; foreach($rows as $col => $val){?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $val['f_name']; ?></td>
            <td><?php echo $val['l_name']; ?></td>
            <td><?php echo $val['email']; ?></td>
            <td><?php echo $val['hobbies']; ?></td>
            <td><?php echo $val['gender']; ?></td>
            <td><?php if($val['prop_pic'] == 'Upload' || $val['prop_pic'] == '') { echo "No Profile"; }else{ ?><img width="50" height="50" src="<?php echo $val['prop_pic']; ?>"><?php }?></td>
        </tr>
        <?php $i++; }?>
    </tbody>
</table> 
</div>