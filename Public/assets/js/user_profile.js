document.getElementById('logout-btn').addEventListener('click', function() {
    if (confirm('Are you sure you want to log out?')) {
        // Send AJAX request to account_action.php with action=logout
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../pages/account_action.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert('Logged out successfully');
                window.location.href = 'login.php'; // Redirect to login page
            }
        };
        xhr.send('action=logout');
    }
});

document.getElementById('deactivate-btn').addEventListener('click', function() {
    if (confirm('Are you sure you want to deactivate your account? This action cannot be undone.')) {
        // Send AJAX request to account_action.php with action=deactivate
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../pages/account_action.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert('Account deactivated successfully');
                window.location.href = 'login.php'; // Redirect to login page
            }
        };
        xhr.send('action=deactivate');
    }
});
