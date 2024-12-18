document.getElementById('logout-btn').addEventListener('click', function() {
    if (confirm('Are you sure you want to log out?')) {
        // Redirect to user_profile.php with action=logout
        window.location.href = 'user_profile.php?action=logout';
    }
});

document.getElementById('deactivate-btn').addEventListener('click', function() {
    if (confirm('Are you sure you want to deactivate your account? This action cannot be undone.')) {
        // Redirect to user_profile.php with action=deactivate
        window.location.href = 'user_profile.php?action=deactivate';
    }
});
