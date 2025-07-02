<?php
// Start the session to access user data.
session_start();

// Initialize an empty array to store validation errors.
$errors = [];

// Check if the form has been submitted using the POST method.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- 1. Input Cleaning and Sanitization ---
    $username = htmlspecialchars(trim($_POST['username'] ?? ''));
    $password = htmlspecialchars(trim($_POST['password'] ?? ''));

    // --- 2. Input Validation ---

    // Check if username is empty.
    if (empty($username)) {
        $errors['username'] = 'Username is required.';
    }

    // Check if password is empty.
    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    }

    // --- 3. Authentication Check ---
    // Only proceed to check credentials if there are no input errors.
    if (empty($errors)) {
        // Check if user data exists in the session (meaning someone registered).
        if (isset($_SESSION['user'])) {
            $stored_username = $_SESSION['user']['username'];
            $stored_password = $_SESSION['user']['password']; // WARNING: In a real app, this would be a HASHED password.

            // Compare submitted credentials with stored session data.
            if ($username === $stored_username && $password === $stored_password) {
                // Set a session variable to indicate the user is logged in.
                $_SESSION['logged_in'] = true;
                // Redirect to the dashboard page.
                header('Location: dashboard.php');
                exit(); // Always exit after a header redirect.
            } else {
                $errors['login'] = 'Invalid username or password.';
            }
        } else {
            $errors['login'] = 'No registered user found. Please register first.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PHP Session System</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles for Inter font and rounded corners */
        body {
            font-family: 'Inter', sans-serif;
        }
        .rounded-lg {
            border-radius: 0.5rem;
        }
        .rounded-md {
            border-radius: 0.375rem;
        }
        .rounded-xl {
            border-radius: 0.75rem;
        }
    </style>
</head>
<body class="bg-gradient-to-r from-blue-500 to-teal-600 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md transform transition-all duration-300 hover:scale-[1.01]">
        <h2 class="text-3xl font-extrabold text-gray-900 mb-6 text-center">User Login</h2>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <ul class="mt-2 list-disc list-inside">
                    <?php foreach ($errors as $field => $message): ?>
                        <li><?php echo $message; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm <?php echo isset($errors['username']) ? 'border-red-500' : ''; ?>">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm <?php echo isset($errors['password']) ? 'border-red-500' : ''; ?>">
            </div>

            <div>
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    Log In
                </button>
            </div>
        </form>

        <p class="mt-8 text-center text-gray-600 text-sm">
            Don't have an account? <a href="index.php" class="font-medium text-blue-600 hover:text-blue-500">Register here</a>.
        </p>
    </div>
</body>
</html>
