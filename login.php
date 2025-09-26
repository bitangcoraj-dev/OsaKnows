<?php
session_start(); // start session

include('db.php');

$message = "";

// login form submission
if (isset($_POST['login'])) {
    $emailOrId = $_POST['email']; 
    $passwordInput = $_POST['password']; 

    $user = null;
    $role = null;

    // First: check in admins table (by email or ID)
    $sql = $conn->prepare("SELECT * FROM register_adminstaff WHERE a_adzuemail = ? OR a_idnumber = ?");
    $sql->bind_param("ss", $emailOrId, $emailOrId);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $role = 'admin';
    } else {
        // Else: check in students table
        $sql = $conn->prepare("SELECT * FROM register_students WHERE adzu_email = ? OR id_number = ?");
        $sql->bind_param("ss", $emailOrId, $emailOrId);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Determine role based on course
            if (isset($user['u_course']) && strtoupper($user['u_course']) === 'NA') {
                $role = 'faculty/staff';
            } else {
                $role = 'student';
            }
        }
    }

    // password verification
    if ($user) {
        $hashedPassword = ($role === 'admin') ? $user['a_password'] : $user['u_password'];

        if (password_verify($passwordInput, $hashedPassword)) {
            session_regenerate_id(true); // secure session

            if ($role === 'admin') {
                $_SESSION['name'] = $user['a_fullname'];
                $_SESSION['idNumber'] = $user['a_idnumber'];
                $_SESSION['email'] = $user['a_adzuemail'];
                $_SESSION['admin_id'] = $user['admin_id'];
            } else {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['name'] = $user['u_fullname'];
                $_SESSION['idNumber'] = $user['id_number'];
                $_SESSION['email'] = $user['adzu_email'];
                $_SESSION['course'] = $user['u_course'];

            }

            $_SESSION['role'] = $role; // admin, student, or faculty/staff

            header("Location: index.php");
            exit();
        } else {
            $message = "Invalid email or password.";
        }
    } else {
        $message = "Account not found. Please register.";
    }

    $sql->close(); //close sql
}

$conn->close(); //close connection
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/your-path-to-uicons/css/uicons-[your-style].css" rel="stylesheet">
    <title>OSAKnows Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('assets/bcbuilding_login.png');
            background-size:cover; 
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin-top:20px;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        h2 {
            color: white;
            margin-bottom: 20px;
            font-size: 65px;
            text-shadow: 0 0 3px #111111, 0 0 5px #111111;
        }

        img {
            height: 270px;
            width: 270px;
            margin-bottom: 20px;
            filter: drop-shadow(5px 5px 5px rgb(24, 24, 24));
        }

        .form-group {
            margin-bottom: 15px;
        }
        input, button {
            transition: all 0.3s ease;
        }

        input {
            display: block;
            width: 80%;
            padding: 15px;
            margin:0 auto;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-sizing: border-box;
            font-size: 14px;
        }

        input:hover{
            border-color: #007BFF; /* Or your theme color */
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
            transform:scale(1.05);
        }

        input::placeholder {
            font-weight:bold;
            color: grey;
        }

        .submit-button {
            width: 80%;
            padding: 15px;
            background-color: #EED921;
            color: black;
            border: 1px solid white;
            border-radius: 10px;
            font-size: 14px;
            font-weight:bold;
            cursor: pointer;
        }
        .submit-button:hover {
            background-color:#fff;
            transform:scale(1.05);
        }
        .register-here, a {
            color:white;
            font-size: 14px;
            display:block;
            margin-top: 5px;
            text-decoration: none;
        }
        a{
            font-weight:600;
        }
        .message {
            text-align: center;
            margin-bottom: 5px;
            color: white;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>OSAKnows</h2>
        <img src="assets/osa.logo.png" alt="Logo">
        
        <?php if (!empty($message)) : ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <input type="text" id="email" name="email" required placeholder="ID Number/Email"> 
            </div> 
            <div class="form-group">
                <input type="password" id="password" name="password" required placeholder="Password">
            </div>
            <button type="submit" name="login" class="submit-button">Login</button> 
        </form>
        <div class="register-here"> 
            <p> Don't have an account? </p> 
            <a href = "registration_nonAdmin.php"> Register as a Student/Faculty </a>
            <a href = "registration_admin.php"> Register as an Admin </a>  
        </div>
    </div>
</body>
</html>
