<?php
//database connection setup
include('db.php');

$message = "";

//check if the form has been submitted
if (isset($_POST['submit'])) {
    //input values from form
    $fullname = $_POST['fullName'];
    $idnumber = $_POST['idnumber'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // Handle file upload
    $idpictureName = $_FILES['idpicture']['name'];
    $idpictureTmpName = $_FILES['idpicture']['tmp_name'];
    $idpictureDestination = 'idPic_admin/' . basename($idpictureName); //create a folder named 'idPic_admin' so that images uploaded would be stored inside the folder

    if ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
    } else {
        // Check for duplicate email or ID number
        $checkDuplicate = $conn->prepare("SELECT * FROM register_adminstaff WHERE a_adzuemail = ? OR a_idnumber = ?");
        $checkDuplicate->bind_param("ss", $email, $idnumber);
        $checkDuplicate->execute();
        $result = $checkDuplicate->get_result();

        if ($result->num_rows > 0) {
            $message = "An account with this email or ID number already exists.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            if (move_uploaded_file($idpictureTmpName, $idpictureDestination)) {

                //prepare sql statement
                $sql = $conn->prepare("INSERT INTO register_adminstaff (a_fullname, a_idnumber, a_adzuemail, a_password, a_idpicture, a_role) 
                                       VALUES (?, ?, ?, ?, ?, 'admin')");

                //bind parameters 
                $sql->bind_param("sssss", $fullname, $idnumber, $email, $hashedPassword, $idpictureName);

                if ($sql->execute()) {
                    $message = "Registration Complete. Proceed to Log In.";
                } else {
                    $message = "Error in registration. Please try again!";
                }

                $sql->close();
            } else {
                $message = "Failed to upload ID picture.";
            }
        }

        $checkDuplicate->close();
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            background-image: url('assets/bcbuilding_login.png');
            background-size:cover; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .registration-container{
            padding:30px;
            width:100%;
            background-color:white;
            border-radius:8px;
            max-width:500px;
        }
        h2{
            text-align:center;
            color:rgb(9, 29, 143);
            margin-bottom:20px;
            margin-top:0;
            font-size:28px;
        }
        .form-group{
            margin-bottom:5px;
        }

        label {
            display:block;
            margin-bottom:7px;
            color:#666;
            font-size: 13px;
            font-weight: bold;
        }

        input, select {
            display: block;
            width: 100%;
            padding:8px;
            margin:0 auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 12px;
            transition: 0.3s ease;
        }

        input:hover{
            border-color: #007BFF; 
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
        }

        .submit-btn {
            background-color: #007BFF;
            color: #fff;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 12px;
            font-weight: bold;
            width: 100%;
            cursor: pointer;
            margin-top: 10px;
        }
        .submit-btn:hover {
            background-color: green;
            transform: scale(1.05);
        }
        .login-here, a {
            text-align: center;
            margin-top: 5px;
            font-size:13px;
            text-decoration: none;
        }
        a{
            font-weight:600;
        }
        .message {
            text-align: center;
            margin-bottom: 5px;
            color: green;
        }
    </style>
</head>
<body>
    <div class = "registration-container"> 
        <h2> Create an Admin Account </h2> 
        
    <?php if (!empty($message)): ?>
        <div class="message">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="fullName">Full Name</label>
            <input type="text" id="fullName" name="fullName" required>
        </div>

        <div class="form-group">
            <label for="idnumber">ID Number</label>
            <input type="text" id="idnumber" name="idnumber" required>
        </div>

        <div class="form-group">
                <label for="email">ADZU Email</label>
                <input type="email" id="email" name="email" required>
        </div>
    
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required>
        </div>
        <div class="form-group">
            <label for="idpicture">ID Picture</label>
            <input type="file" id="idpicture" name="idpicture" accept="image/*" required>
        </div>
            <button type="submit" name="submit" class="submit-btn">Register</button>
    </form>
        <div class="login-here"> 
          <p> Have an account? </p> <a href = "login.php"> Click here to Login </a> 
        </div>
    </div>

</body>
</html>