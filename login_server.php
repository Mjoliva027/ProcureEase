<?php
include('./includes/db_connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists in the database
    $stmt = $conn->prepare("SELECT id, name, email, password, role, is_new FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Debugging: Check the values of the fetched user
        // error_log(print_r($user, true));

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role']; // Make sure you save role too

            // Debugging: Check session data
            // error_log('Session Data: ' . print_r($_SESSION, true));

            // Check if user is new
            if ($user['is_new'] == 1) {
                // Redirect to fill up form based on role
                if ($user['role'] == 'supplier') {
                    echo "redirect:supplier_form.php";
                } elseif ($user['role'] == 'government') {
                    echo "redirect:government_form.php";
                }
            } else {
                // Redirect to dashboard based on role
                if ($user['role'] == 'supplier') {
                    echo "redirect:./supplier/supplier_dashboard.php";
                } elseif ($user['role'] == 'government') {
                    echo "redirect:government_dashboard.php";
                } elseif ($user['role'] == 'admin') {
                    echo "redirect:./admin/admin_dashboard.php";
                }
            }
        } else {
            // Debugging: Log invalid password attempt
            echo "error:Invalid password";
             error_log('Invalid password for email: ' . $email);
        }
    } else {
        echo "error:User not found";
    }
}
?>
