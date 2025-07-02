<?php

session_start();


$errors = [];

$success = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $first_name = (trim($_POST['first_name'] ?? ''));
    $last_name = (trim($_POST['last_name'] ?? ''));
    $username = (trim($_POST['username'] ?? ''));
    $password = (trim($_POST['password'] ?? ''));
    $confirm_password = (trim($_POST['confirm_password'] ?? ''));
    $age = (trim($_POST['age'] ?? ''));
    $phone_number = (trim($_POST['phone_number'] ?? ''));
    $role = (trim($_POST['role'] ?? ''));
    $education = (trim($_POST['education'] ?? ''));

    
    $courses = [];
    if (isset($_POST['courses']) && is_array($_POST['courses'])) {
        foreach ($_POST['courses'] as $course) {
            $courses[] = htmlspecialchars(trim($course));
        }
    }

   
    if (empty($first_name)) {
        $errors['first_name'] = 'First Name is required.';
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $first_name)) {
        $errors['first_name'] = 'Only letters and white space allowed for First Name.';
    }

    
    if (empty($last_name)) {
        $errors['last_name'] = 'Last Name is required.';
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $last_name)) {
        $errors['last_name'] = 'Only letters and white space allowed for Last Name.';
    }

    
    if (empty($username)) {
        $errors['username'] = 'Username is required.';
    } elseif (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
        $errors['username'] = 'Username can only contain letters, numbers, and underscores.';
    } elseif (strlen($username) < 3) {
        $errors['username'] = 'Username must be at least 3 characters long.';
    }
    
    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters long.';
    }

   
    if (empty($confirm_password)) {
        $errors['confirm_password'] = 'Please confirm your password.';
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    
    if (empty($age)) {
        $errors['age'] = 'Age is required.';
    } elseif (!is_numeric($age) || $age <= 0) {
        $errors['age'] = 'Age must be a positive number.';
    }

    
    if (empty($phone_number)) {
        $errors['phone_number'] = 'Phone Number is required.';
    } elseif (!preg_match("/^[0-9]{10}$/", $phone_number)) {
        $errors['phone_number'] = 'Phone Number must be 10 digits.';
    }

    
    if (empty($role)) {
        $errors['role'] = 'Role is required.';
    } elseif (!in_array($role, ['Admin', 'User'])) {
        $errors['role'] = 'Invalid role selected.';
    }

    
    if (empty($education)) {
        $errors['education'] = 'Education is required.';
    } elseif (!in_array($education, ['B.Tech', 'M.Tech', 'PhD'])) {
        $errors['education'] = 'Invalid education selected.';
    }

    
    $allowed_courses = ['PHP', 'Angular', 'Python', 'React'];
    foreach ($courses as $course) {
        if (!in_array($course, $allowed_courses)) {
            $errors['courses'] = 'Invalid course selected.';
            break; 
        }
    }

   
    $profile_picture_path = ''; 

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['profile_picture']['name'];
        $file_tmp_name = $_FILES['profile_picture']['tmp_name'];
        $file_size = $_FILES['profile_picture']['size'];
        $file_type = $_FILES['profile_picture']['type'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $max_file_size = 2 * 1024 * 1024; 

        if (!in_array($file_ext, $allowed_extensions)) {
            $errors['profile_picture'] = 'Only JPG, JPEG, PNG, and GIF files are allowed.';
        } elseif ($file_size > $max_file_size) {
            $errors['profile_picture'] = 'File size must be less than 2MB.';
        } else {
            
            $new_file_name = uniqid('profile_', true) . '.' . $file_ext;
            $upload_directory = 'uploads/';
            $profile_picture_path = $upload_directory . $new_file_name;

            
            if (!move_uploaded_file($file_tmp_name, $profile_picture_path)) {
                $errors['profile_picture'] = 'Failed to upload profile picture.';
            }
        }
    } else if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
        
        $errors['profile_picture'] = 'An upload error occurred: ' . $_FILES['profile_picture']['error'];
    }

    
    if (empty($errors)) {
        
        $_SESSION['user'] = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'username' => $username,
            'password' => $password, 
            'courses' => $courses,
            'education' => $education,
            'role' => $role,
            'age' => $age,
            'phone_number' => $phone_number,
            'profile_picture' => $profile_picture_path,
        ];
        $success['registration'] = 'Registration successful! You can now log in.';

        
        $_POST = [];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PHP Session System</title>
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
<body class="bg-gradient-to-r from-purple-500 to-indigo-600 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 hover:scale-[1.01]">
        <h2 class="text-3xl font-extrabold text-gray-900 mb-6 text-center">User Registration</h2>

        <?php if (!empty($success)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline"><?php echo $success['registration']; ?></span>
            </div>
        <?php endif; ?>

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

        <form action="index.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm <?php echo isset($errors['first_name']) ? 'border-red-500' : ''; ?>">
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm <?php echo isset($errors['last_name']) ? 'border-red-500' : ''; ?>">
                </div>
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm <?php echo isset($errors['username']) ? 'border-red-500' : ''; ?>">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm <?php echo isset($errors['password']) ? 'border-red-500' : ''; ?>">
                </div>
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm <?php echo isset($errors['confirm_password']) ? 'border-red-500' : ''; ?>">
                </div>
            </div>

            <div>
                <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($_POST['age'] ?? ''); ?>"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm <?php echo isset($errors['age']) ? 'border-red-500' : ''; ?>">
            </div>

            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($_POST['phone_number'] ?? ''); ?>"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm <?php echo isset($errors['phone_number']) ? 'border-red-500' : ''; ?>">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Courses</label>
                <div class="mt-2 grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <?php
                    $course_options = ['PHP', 'Angular', 'Python', 'React'];
                    foreach ($course_options as $option):
                        $checked = in_array($option, ($_POST['courses'] ?? [])) ? 'checked' : '';
                    ?>
                        <div class="flex items-center">
                            <input id="course_<?php echo strtolower($option); ?>" name="courses[]" type="checkbox" value="<?php echo $option; ?>" <?php echo $checked; ?>
                                   class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="course_<?php echo strtolower($option); ?>" class="ml-2 block text-sm text-gray-900"><?php echo $option; ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (isset($errors['courses'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo $errors['courses']; ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Education</label>
                <div class="mt-2 flex flex-wrap gap-4">
                    <?php
                    $education_options = ['B.Tech', 'M.Tech', 'PhD'];
                    foreach ($education_options as $option):
                        $checked = (($_POST['education'] ?? '') === $option) ? 'checked' : '';
                    ?>
                        <div class="flex items-center">
                            <input id="education_<?php echo strtolower($option); ?>" name="education" type="radio" value="<?php echo $option; ?>" <?php echo $checked; ?>
                                   class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <label for="education_<?php echo strtolower($option); ?>" class="ml-2 block text-sm text-gray-900"><?php echo $option; ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (isset($errors['education'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo $errors['education']; ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select id="role" name="role"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm <?php echo isset($errors['role']) ? 'border-red-500' : ''; ?>">
                    <option value="">Select a role</option>
                    <option value="Admin" <?php echo (($_POST['role'] ?? '') === 'Admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="User" <?php echo (($_POST['role'] ?? '') === 'User') ? 'selected' : ''; ?>>User</option>
                </select>
            </div>

            <div>
                <label for="profile_picture" class="block text-sm font-medium text-gray-700">Profile Picture</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/jpeg, image/png, image/gif"
                       class="mt-1 block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 <?php echo isset($errors['profile_picture']) ? 'border-red-500' : ''; ?>">
                <?php if (isset($errors['profile_picture'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo $errors['profile_picture']; ?></p>
                <?php endif; ?>
            </div>

            <div>
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    Register
                </button>
            </div>
        </form>

        <p class="mt-8 text-center text-gray-600 text-sm">
            Already have an account? <a href="login.php" class="font-medium text-indigo-600 hover:text-indigo-500">Log in here</a>.
        </p>
    </div>
</body>
</html>
