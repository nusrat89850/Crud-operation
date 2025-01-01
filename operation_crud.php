<?php
  $conn = mysqli_connect("localhost","root","","cmbd263");
  if(isset($_POST["add_user"])){
    $name=$_POST['name'];
    $email=$_POST["email"];
    $gender=$_POST["gender"] ?? null;
    $password=password_hash($_POST["password"],PASSWORD_DEFAULT);

    $insertQuery = "INSERT INTO `users` (`name`,`email`,`gender`,`password`) 
    VALUES('$name','$email','$gender','$password')";
    $insertQueryResult = mysqli_query($conn,$insertQuery);

    if($insertQueryResult){
      echo "User added successfully";
    }
    else {
      echo "Failed to add user";
    }
  }
  $selectQuery = "SELECT * FROM `users`";
  $selectQueryResult = mysqli_query($conn,$selectQuery);
  $users = mysqli_fetch_all($selectQueryResult,MYSQLI_ASSOC);
?>
<?php if (!isset($_GET['eid']) && !isset($_GET['did'])){ ?>
<h2>Add User</h2>
<form action="" method="POST">
  <input type="text" placeholder="Your name" name="name" required>
  <br><br>
  <input type="email" placeholder="Your email" name="email" required>
  <br><br>
  <input type="password" placeholder="Your password" name="password" required>
  <br><br>
  Gender:
  <input type="radio" name="gender" value="Male" required>Male
  <input type="radio" name="gender" value="Female" required>Female
  <br><br>
  <input type="submit" value="Add User" name="add_user">
</form>
<table border="1" cellspacing="0" cellpadding="10">
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Gender</th>
    <th>Actions</th>
  </tr>
  <?php foreach($users as $user): ?>
    <tr>
      <td><?php echo $user['name']; ?></td>
      <td><?php echo $user['email']; ?></td>
      <td><?php echo $user['gender']; ?></td>
      <td>
        <a href="./crud.php?eid=<?= $user['id'] ?>">Edit</a>
        <a href="./crud.php?did=<?= $user['id'] ?>">Delete</a>
      </td>
    </tr>
    <?php endforeach; }?>
</table>
<?php if(isset($_GET['eid'])){ ?>
  <?php 
     $id = $_GET['eid']; 
     $selectQuery = "SELECT * FROM `users` WHERE `id` = $id";
     $selectQueryResult = mysqli_query($conn,$selectQuery);
     $user = mysqli_fetch_assoc($selectQueryResult);

     if(isset($_POST["update_user"])){
      $name=$_POST['name'];
      $email=$_POST["email"];
      $gender=$_POST["gender"] ?? null;
      $password=$_POST["password"];

      if(password_verify($password, $user['password'])){
        $updateQuery = "UPDATE `users` SET `name` = '$name', `email` = '$email', `gender` = '$gender' WHERE `id` = $id";
        $updateQueryResult = mysqli_query($conn, $updateQuery);

        if($updateQueryResult){
            echo "User updated successfully";
            echo "<a href='./crud_operation.php'>Back</a>";
        }else{
            echo "Failed to update user";
        }
    }else{
        echo "Password does not match";
    }
}  
  ?>
  <h2>Edit User</h2>
<form action="" method="POST">
  <input type="text" placeholder="Your name" name="name" required>
  <br><br>
  <input type="email" placeholder="Your email" name="email" required>
  <br><br>
  <input type="password" placeholder="Your password" name="password" required>
  <br><br>
  Gender:
  <input type="radio" name="gender" value="Male" <?php echo ($user['gender'] == 'Male') ? 'checked' : ''; ?> /> Male
  <input type="radio" name="gender" value="Female" <?php echo ($user['gender'] == 'Female') ? 'checked' : ''; ?> /> Female
  <br><br>
  <input type="submit" value="Update User" name="update_user">
</form>
<?php } ?>

<?php if(isset($_GET['did'])){ ?>
    <?php
        $id = $_GET['did'];
        $deleteQuery = "DELETE FROM `users` WHERE `id` = $id";
        $deleteQueryResult = mysqli_query($conn, $deleteQuery);

        if($deleteQueryResult){
            echo "User deleted successfully";
            echo "<a href='./crud.php'>Back</a>";
        }else{
            echo "Failed to delete user";
        }
    ?>
<?php } ?>
