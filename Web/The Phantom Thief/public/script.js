document.getElementById('feedbackForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    const feedbackText = document.getElementById('feedback').value;
    
    try {
        const response = await fetch('/feedback', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ feedback: feedbackText })
        });

        const result = await response.json();
        
        if (response.ok) {
            // Clear the input
            document.getElementById('feedback').value = '';
            
            // Show success message with admin hint
            showNotification('Feedback submitted successfully! An admin will review it shortly. 👀', 'success');
        } else {
            showNotification(result.error || 'Failed to submit feedback', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('An error occurred while submitting feedback', 'error');
    }
});

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove notification after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Theme Toggle
const themeToggle = document.getElementById('themeToggle');
themeToggle.addEventListener('click', () => {
    document.body.classList.toggle('light-mode');
    themeToggle.textContent = document.body.classList.contains('light-mode') ? '🌞 Light Mode' : '🌙 Dark Mode';
});