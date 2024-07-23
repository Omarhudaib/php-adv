<?php
session_start();
$conn = mysqli_connect("localhost", "Omar", "Ai@ktv7L9_Cj4re7", "task23adv");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = [];

if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $email, $hashed_password, $role);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["user_name"] = $name;
            $_SESSION["user_email"] = $email;
            $_SESSION["user_role"] = $role;

   
            if ($role === 'Admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: welcom.php");
            }
            exit();
        } else {
            $errors['password'] = 'Incorrect password';
        }
    } else {
        $errors['email'] = 'Invalid email';
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="login.styles.css">
  <title>Log In</title>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card mt-5">
          <div class="card-body">
            <h3 class="card-title text-center">Log In</h3>
            <form action="" method="post" id="loginForm">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <?php if (isset($errors['email'])): ?>
                  <small class="form-text text-danger"><?php echo htmlspecialchars($errors['email']); ?></small>
                <?php endif; ?>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <?php if (isset($errors['password'])): ?>
                  <small class="form-text text-danger"><?php echo htmlspecialchars($errors['password']); ?></small>
                <?php endif; ?>
              </div>
              <button type="submit" class="btn btn-primary btn-block" name="submit">Log In</button>
              <a href="singup.php" class="btn btn-secondary btn-block">Sign Up</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
