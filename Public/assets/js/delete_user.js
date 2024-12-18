document.addEventListener("DOMContentLoaded", function() {
    window.deleteUser = function(userId, button) {
        if (confirm('Are you sure you want to delete this user?')) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'admin_user.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.status === 'success') {
                                document.getElementById('admin-row-' + userId).remove();
                                alert(response.message);
                            } else {
                                console.error(response.message);
                                alert('Error: ' + response.message);
                            }
                        } catch (e) {
                            console.error("Invalid JSON response:", xhr.responseText);
                            alert("An error occurred while processing the response.");
                        }
                    } else {
                        alert('Request failed with status ' + xhr.status);
                    }
                }
            };
            xhr.send('delete_user_id=' + userId);
        }
    };    
});
