<?php

// login-system-main/app/Controllers/Admin/AuthController.php

namespace App\Controllers\Admin;

class AuthController
{
    public function showLoginForm()
    {
        include(__DIR__ . '/../../../resources/views/admin/auth/login.php');
    }

    public function login()
    {
        // Handle admin login logic

        // Validate credentials (replace with your authentication logic)
        $username = '111';
        $password = 'tk';

        if ($username === 'admin' && $password === 'adminpassword') {
            // Successful login, redirect to admin dashboard
         // Successful login, redirect to admin dashboard
header('Location: /login-system-main/admin/dashboard');
exit();

        } else {
            // Failed login, redirect back to the login form with an error message
            header('Location: login-system-main/admin/login?error=1');
            exit();
        }
    }

    public function logout()
    {
        // Handle admin logout logic

        // For simplicity, just redirect to the login form
        header('Location: /login-system-main/admin/login');
        exit();
    }
}
