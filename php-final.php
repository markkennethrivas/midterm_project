<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration_tbl"; 

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["user_name"];
    $firstName = $_POST["first_name"];
    $middleName = $_POST["middle_name"];
    $lastName = $_POST["last_name"];
    $gender = $_POST["gender"];
    $birthdate = $_POST["birthdate"];
    $email = $_POST["email_address"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    
    $username = htmlspecialchars($username);
    $firstName = htmlspecialchars($firstName);
    $middleName = htmlspecialchars($middleName);
    $lastName = htmlspecialchars($lastName);
    $gender = htmlspecialchars($gender);
    $birthdate = htmlspecialchars($birthdate);
    $email = htmlspecialchars($email);

    
    $checkUserQuery = "SELECT * FROM mark_tbl WHERE email_address = :email";
    $stmt1 = $conn->prepare($checkUserQuery);
    $stmt1->bindParam(':email', $email);

    try {
        $stmt1->execute();
        if ($stmt1->rowCount() > 0) {
            echo "User already exists!";
        } else {
            
            if (strlen($password) < 8 || !preg_match('@[A-Z]@', $password) || !preg_match('@[a-z]@', $password) || !preg_match('@[0-9]@', $password) || !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
                echo "Password does not meet the complexity requirements.";
            } elseif ($password !== $confirmPassword) {
                echo "Passwords do not match.";
            } else {
                
                $birthdateObj = new DateTime($birthdate);
                $today = new DateTime('now');
                $age = $birthdateObj->diff($today)->y;

                
                $insertQuery = "INSERT INTO mark_tbl (user_name, first_name, middle_name, last_name, gender, birthdate, age, email_address, password) VALUES (:username, :firstName, :middleName, :lastName, :gender, :birthdate, :age, :email, :password)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':firstName', $firstName);
                $stmt->bindParam(':middleName', $middleName);
                $stmt->bindParam(':lastName', $lastName);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':birthdate', $birthdate);
                $stmt->bindParam(':age', $age);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);

                
               

                try {
                    $stmt->execute();
                    echo "Registration successful!";
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>