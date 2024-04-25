// XMLHttpRequest seem to cause a CORS issue when using Docker networking in compose.yaml
// var serverUrl = "http://backend/users.php"
var serverUrl = "http://127.0.0.1:30100/users.php"

// Fetch API request to create user on the backend
function addUser() {
    var username = document.getElementById("username").value;
    var email = document.getElementById("email").value;

    fetch(serverUrl, {
        method: "POST",
        headers: {
            // "Access-Control-Allow-Origin": "*",
            // "Access-Control-Allow-Methods": "POST, OPTIONS",
            // "Access-Control-Allow-Headers": "Access-Control-Allow-Headers,API-Key,Content-Type,If-Modified-Since,Cache-Control",
            // "Access-Control-Allow-Credentials": true,
            // "Access-Control-Expose-Headers": "Content-Type, Authorization",
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "username=" + username + "&email=" + email
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok : ' + response.status + ' - ' + response.statusText);
            }
            console.log('User added successfully');
            return getUsers();
        })
        .catch(error => {
            console.error('Error adding user: ', error);
        });
}

// Fetch API request to create user on the backend
function deleteUser(id) {
    fetch(serverUrl, {
        method: "DELETE",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id=" + id
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok' + response.status + ' - ' + response.statusText);
            }
            console.log('User deleted successfully: ' + id);
            return getUsers();
        })
        .catch(error => {
            console.error('Error deleting user: ', error);
        });
}

// Fetch API request to get users from the backend
function getUsers() {
    fetch(serverUrl, {
        method: "GET",
        headers: {
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok : ' + response.status + ' - ' + response.statusText);
            }
            console.log('Getting users successfully');
            return response.json();
        })
        .then(users => {
            // Update the user list on the frontend
            var userList = document.getElementById("userList");
            userList.innerHTML = "";
            users.forEach(function (user) {
                var listItem = document.createElement("li");
                listItem.innerHTML = user.username + " - " + user.email;

                var deleteButton = document.createElement("button");
                deleteButton.innerHTML = "Delete";
                deleteButton.addEventListener("click", function () {
                    deleteUser(user.id);
                });

                listItem.appendChild(deleteButton);
                userList.appendChild(listItem);
            });
        })
        .catch(error => {
            console.error('Error getting users:', error);
        });
}

// Initial load of users
getUsers();
