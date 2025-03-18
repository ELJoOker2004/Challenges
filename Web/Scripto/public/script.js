// Simulate a backend that stores and displays comments
const comments = [];

document.getElementById('commentForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const commentText = document.getElementById('comment').value;
    comments.push(commentText);
    displayComments();
    document.getElementById('comment').value = ''; // Clear the input

    // Check if the comment contains an XSS payload
    if (commentText.includes('<script>') || commentText.includes('alert')) {
        // Fetch the flag from the backend
        fetch('/getFlag')
            .then(response => response.json())
            .then(data => {
                const secretDiv = document.createElement('div');
                secretDiv.className = 'secret-flag';
                secretDiv.innerHTML = `<p>🎉 Congratulations! You found the flag: <strong>${data.flag}</strong></p>`;
                document.querySelector('.container').appendChild(secretDiv);
            });
    }
});

function displayComments() {
    const commentsDiv = document.getElementById('comments');
    commentsDiv.innerHTML = ''; // Clear previous comments
    comments.forEach(comment => {
        commentsDiv.innerHTML += `<p>${comment}</p>`; // Vulnerable line
    });
}

// Theme Toggle
const themeToggle = document.getElementById('themeToggle');
themeToggle.addEventListener('click', () => {
    document.body.classList.toggle('light-mode');
    themeToggle.textContent = document.body.classList.contains('light-mode') ? '🌞 Light Mode' : '🌙 Dark Mode';
});

// Display any existing comments on page load
displayComments();