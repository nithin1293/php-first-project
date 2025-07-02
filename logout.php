<?php
// Start the session. This is crucial to access and manipulate session variables.
session_start();

// Unset all of the session variables.
// This removes all data stored in the $_SESSION superglobal for the current session.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
// This removes the session file from the server.
session_destroy();

// Redirect the user to the login page after logging out.
header('Location: login.php');
exit(); // It's important to call exit() after a header redirect to prevent further script execution.
?>
