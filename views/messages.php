

<h2 class="text-center p-2 rounded bg-secondary text-white">ALl Messages</h2>
<?php if(isset($_SESSION['success'])) { ?>
    <div class="bg-success p-2 my-2 text-white">
        <?php echo $_SESSION['success'] ; ?>
    </div>
<?php unset($_SESSION['success']); } ?>

<table class="table table-striped">
  <tr>
    <th>#</th>
    <th>Name</th>
    <th>Email</th>
    <th>Subject</th>
    <th>Messages</th>
  </tr>
  <?php
  $iteration = 1;
   foreach ($data->messages as $message) : ?>

        <tr>
            <td><?php echo $iteration ; ?></td>
            <td><?php echo $message['name'] ; ?></td>
            <td><?php echo $message['email'] ; ?></td>
            <td><?php echo $message['subject'] ; ?></td>
            <td><?php echo $message['message'] ; ?></td>
        </tr>
  <?php
  $iteration ++;
   endforeach 
   ?>
</table>
