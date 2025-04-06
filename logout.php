<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Destroy the session
session_destroy();

// Clear any remaining session data
unset($_SESSION);

// Redirect to login page with a small delay
echo '<script>
    alert("Logging out...");
    window.location.href = "login.php";
</script>';
exit();
?> 