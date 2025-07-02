<?php
// Start the session to access user data.
session_start();

// Check if the user is NOT logged in or if user data is not set in the session.
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user'])) {
    // If not logged in, redirect them to the login page.
    header('Location: login.php');
    exit(); // Always exit after a header redirect.
}

// Get the user data from the session.
$user = $_SESSION['user'];

// Set default profile picture if none uploaded or path is empty/invalid.
$profile_picture_src = 'https://placehold.co/150x150/e0e0e0/555555?text=No+Image';
if (!empty($user['profile_picture']) && file_exists($user['profile_picture'])) {
    $profile_picture_src = $user['profile_picture'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PHP Session System</title>
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
<body class="bg-gradient-to-r from-green-500 to-lime-600 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 hover:scale-[1.01]">
        <h2 class="text-3xl font-extrabold text-gray-900 mb-8 text-center">User Dashboard</h2>

        <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
            <div class="flex-shrink-0">
                <img src="<?php echo htmlspecialchars($profile_picture_src); ?>" alt="Profile Picture"
                     class="w-36 h-36 rounded-full object-cover shadow-lg border-4 border-indigo-200">
            </div>
            <div class="flex-grow text-center md:text-left">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">
                    <?php echo htmlspecialchars($user['first_name'] ?? 'N/A') . ' ' . htmlspecialchars($user['last_name'] ?? 'N/A'); ?>
                </h3>
                <p class="text-gray-700 text-lg mb-4">@<?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?></p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-800">
                    <p><strong class="font-semibold">Age:</strong> <?php echo htmlspecialchars($user['age'] ?? 'N/A'); ?></p>
                    <p><strong class="font-semibold">Phone:</strong> <?php echo htmlspecialchars($user['phone_number'] ?? 'N/A'); ?></p>
                    <p><strong class="font-semibold">Role:</strong> <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo ($user['role'] ?? '') === 'Admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'; ?>"><?php echo htmlspecialchars($user['role'] ?? 'N/A'); ?></span></p>
                    <p><strong class="font-semibold">Education:</strong> <?php echo htmlspecialchars($user['education'] ?? 'N/A'); ?></p>
                    <p class="col-span-1 sm:col-span-2"><strong class="font-semibold">Courses:</strong>
                        <?php
                        if (!empty($user['courses'])) {
                            echo implode(', ', array_map('htmlspecialchars', $user['courses']));
                        } else {
                            echo 'None selected';
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="logout.php"
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                Log Out
            </a>
        </div>
    </div>
</body>
</html>
