<?php

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== 'Admin') {
    header("Location: login.php");
    exit();
}
if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "Omar", "Ai@ktv7L9_Cj4re7", "task23adv");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = [];
$success = '';

if (isset($_POST["add_user"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $mobile = $_POST["mobile"];
    $password = $_POST["password"];
    $role = $_POST["role"];
    $image = $_FILES["file"];

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors['email'] = 'This email is already in use';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Handle image upload
        $image_path = null;
        if ($image["error"] == 0) {
            if (!file_exists('uploads')) {
                mkdir('uploads', 0777, true);
            }
            $image_name = time() . "_" . basename($image["name"]);
            $image_path = "uploads/" . $image_name;
            move_uploaded_file($image["tmp_name"], $image_path);
        }

        $stmt = $conn->prepare("INSERT INTO users (name, email, mobile, password, role, image_path) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $mobile, $hashed_password, $role, $image_path);
        if ($stmt->execute()) {
            $success = 'User added successfully';
        } else {
            $errors['general'] = 'Error occurred while adding the user';
        }
        $stmt->close();
    }
}

// Handle Edit User
if (isset($_POST["edit_user"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $mobile = $_POST["mobile"];
    $role = $_POST["role"];
    $image = $_FILES["file"];

    // Handle image upload
    $image_path = isset($_POST["existing_image"]) ? $_POST["existing_image"] : null; // Keep existing image if no new one is uploaded
    if ($image["error"] == 0) {
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }
        $image_name = time() . "_" . basename($image["name"]);
        $image_path = "uploads/" . $image_name;
        move_uploaded_file($image["tmp_name"], $image_path);
    }

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, mobile = ?, role = ?, image_path = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $name, $email, $mobile, $role, $image_path, $id);
    if ($stmt->execute()) {
        $success = 'User updated successfully';
    } else {
        $errors['general'] = 'Error occurred while updating the user';
    }
    $stmt->close();
}

if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $success = 'User deleted successfully';
    } else {
        $errors['general'] = 'Error occurred while deleting the user';
    }
    $stmt->close();
}

$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <title>Admin Dashboard</title>
</head>
<body>
<form method="post" action="">
    <button type="submit" name="logout" class="btn btn-danger mt-3">Log Out</button>
</form>
<div class="container mt-5">
    <h1 class="text-center">Admin Dashboard</h1>

    <!-- Add User Form -->
    <div class="card mt-3">
      <div class="card-body">
        <h5>Add New User</h5>
        <?php if (!empty($errors['email'])): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($errors['email']); ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
          <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="form-group">
            <label for="mobile">Mobile Number</label>
            <input type="text" class="form-control" id="mobile" name="mobile" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <div class="form-group">
            <label for="file">Profile Image</label>
            <input type="file" class="form-control" id="file" name="file" required>
          </div>
          <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" id="role" name="role" required>
              <option value="User">User</option>
              <option value="Admin">Admin</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary" name="add_user">Add User</button>
        </form>
      </div>
    </div>

    <div class="card mt-3">
      <div class="card-body">
        <h5>All Users</h5>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>User Image</th>
              <th>Name</th>
              <th>Email</th>
              <th>Date Created</th>
              <th>Mobile Number</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td>
                  <?php if (!empty($row['image_path'])): ?>
                    <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="User Image" width="50">
                  <?php else: ?>
                    <img src="path_to_default_image" alt="Default Image" width="50">
                  <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                <td>
                  <a href="admin_dashboard.php?edit=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                  <a href="admin_dashboard.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <?php
    if (isset($_GET["edit"])) {
        $id = $_GET["edit"];
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
    ?>

      <div class="card mt-3">
        <div class="card-body">
          <h5>Edit User</h5>
          <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
              <label for="mobile">Mobile Number</label>
              <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo htmlspecialchars($user['mobile']); ?>" required>
            </div>
            <div class="form-group">
              <label for="role">Role</label>
              <select class="form-control" id="role" name="role" required>
                <option value="User" <?php echo ($user['role'] === 'User') ? 'selected' : ''; ?>>User</option>
                <option value="Admin" <?php echo ($user['role'] === 'Admin') ? 'selected' : ''; ?>>Admin</option>
              </select>
            </div>
            <div class="form-group">
              <label for="file">Profile Image</label>
              <input type="file" class="form-control-file" id="file" name="file">
              <?php if (!empty($user['image_path'])): ?>
                <img src="<?php echo htmlspecialchars($user['image_path']); ?>" alt="User Image" width="100">
                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($user['image_path']); ?>">
              <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary" name="edit_user">Update User</button>
          </form>
        </div>
      </div>
    <?php } ?>

</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
