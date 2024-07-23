<?php
session_start();
$conn = mysqli_connect("localhost", "Omar", "Ai@ktv7L9_Cj4re7", "task23adv");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = [];
$success = '';

if (isset($_POST["submit"])) {
    $first_name = $_POST["first_name"];
    $middle_name = $_POST["middle_name"];
    $last_name = $_POST["last_name"];
    $family_name = $_POST["family_name"];
    $email = $_POST["email"];
    $mobile = $_POST["mobile"];
    $password = $_POST["password"];
    $password2 = $_POST["password2"];
    $role = $_POST["role"];

   
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    } else {
        $duplicate = mysqli_query($conn, "SELECT * FROM users WHERE email ='$email'");
        if (mysqli_num_rows($duplicate) > 0) {
            $errors['email'] = 'This email is already used';
        }
    }


    if (!preg_match("/^\d{10}$/", $mobile)) {
        $errors['mobile'] = 'Mobile number must be 10 digits';
    }

    if (empty($first_name) || empty($middle_name) || empty($last_name) || empty($family_name)) {
        $errors['name'] = 'All name fields are required';
    }

   
    $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
    if (!preg_match($password_pattern, $password)) {
        $errors['password'] = 'Password must be at least 8 characters long, with uppercase, lowercase, number, and special character';
    } elseif ($password !== $password2) {
        $errors['password'] = 'Passwords do not match';
    }

    if (empty($errors)) {
        $full_name = "$first_name $middle_name $last_name $family_name";
        $stmt = $conn->prepare("INSERT INTO users (name, email, mobile, password, role) VALUES (?, ?, ?, ?, ?)");
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sssss", $full_name, $email, $mobile, $hashed_password, $role);
        if ($stmt->execute()) {
            $success = 'Sign up successful';
            header("Location: login.php");
            exit();
        } else {
            $errors['general'] = 'Something went wrong, please try again';
        }
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="login.styles.css">
  <title>Sign Up</title>
  <script>
    function validateForm() {
      let valid = true;


      document.querySelectorAll('.form-text.text-danger').forEach(el => el.textContent = '');

     
      ['first_name', 'middle_name', 'last_name', 'family_name'].forEach(id => {
        if (!document.getElementById(id).value.trim()) {
          document.getElementById(id).nextElementSibling.textContent = 'This field is required';
          valid = false;
        }
      });

 
      const email = document.getElementById('email').value;
      const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
      if (!emailPattern.test(email)) {
        document.getElementById('email').nextElementSibling.textContent = 'Invalid email format';
        valid = false;
      }

     
      const mobile = document.getElementById('mobile').value;
      const mobilePattern = /^\d{10}$/;
      if (!mobilePattern.test(mobile)) {
        document.getElementById('mobile').nextElementSibling.textContent = 'Mobile number must be 10 digits';
        valid = false;
      }

     
      const password = document.getElementById('password').value;
      const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
      if (!passwordPattern.test(password)) {
        document.getElementById('password').nextElementSibling.textContent = 'Password must be at least 8 characters long, with uppercase, lowercase, number, and special character';
        valid = false;
      }

      
      const password2 = document.getElementById('password2').value;
      if (password !== password2) {
        document.getElementById('password2').nextElementSibling.textContent = 'Passwords do not match';
        valid = false;
      }

      return valid;
    }
  </script>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card mt-5">
          <div class="card-body">
            <h3 class="card-title text-center">Sign Up</h3>
            <?php if (!empty($success)): ?>
              <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form action="" method="post" id="registerForm" onsubmit="return validateForm()">
              <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
                <small class="form-text text-danger"></small>
              </div>
              <div class="form-group">
                <label for="middle_name">Middle Name</label>
                <input type="text" class="form-control" id="middle_name" name="middle_name" required>
                <small class="form-text text-danger"></small>
              </div>
              <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
                <small class="form-text text-danger"></small>
              </div>
              <div class="form-group">
                <label for="family_name">Family Name</label>
                <input type="text" class="form-control" id="family_name" name="family_name" required>
                <small class="form-text text-danger"></small>
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <small class="form-text text-danger"></small>
              </div>
              <div class="form-group">
                <label for="mobile">Mobile</label>
                <input type="text" class="form-control" id="mobile" name="mobile" required>
                <small class="form-text text-danger"></small>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <small class="form-text text-danger"></small>
              </div>
              <div class="form-group">
                <label for="password2">Confirm Password</label>
                <input type="password" class="form-control" id="password2" name="password2" required>
                <small class="form-text text-danger"></small>
              </div>
              <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control" id="role" name="role" required>
                  <option value="">Select Role</option>
                  <option value="User">User</option>
                  <option value="Admin">Admin</option>
                </select>
              </div>
              <?php if (isset($errors['general'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($errors['general']); ?></div>
              <?php endif; ?>
              <button type="submit" class="btn btn-primary btn-block" name="submit">Sign Up</button>
              <a href="login.php" class="btn btn-secondary btn-block">Log In</a>
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
      