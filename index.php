<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];


    if ($username == 'admin' && $password == 'admin123') {
        echo "
        <script>
            alert('Login Successfully!');
            window.location.href = 'http://localhost/employee-management-system/employee.php/';
        </script>
        "; 
    } else {
        echo "
        <script>
            alert('Login Failed, Incorrect Username or Password!');
            window.location.href = 'http://localhost/employee-management-system/';
        </script>
        ";
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Log In</title>

    <style>
        * {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }
        body {
            background-image: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            width: 350px;
            padding: 40px;
            background-color:rgb(255, 255, 255);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .login-box h1 {
            font-weight: bold;
            margin-bottom: 20px;
        }

        .login-box form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: black;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

    </style>
</head>
<body>
    <div class="login-box">
        <h1>Log In</h1>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit">Log In</button>
        </form>
    </div>
</body>
</html>
