const usernameInput = document.getElementById('username_form');
const usernameInfo = document.getElementById('username_info');

let timeout = null;

usernameInput.addEventListener('input', function() {
    clearTimeout(timeout);

    timeout = setTimeout(() => {
        const username = this.value;

        fetch(`/controllers/username_input_controller.php?username=${encodeURIComponent(username)}`)
            .then(response => response.json())
            .then(data => {

                let message = data.message || data;
                usernameInfo.textContent = message;

                if (message.includes('!')) {
                    usernameInfo.style.color = '#4CAF50';
                } else{
                    usernameInfo.style.color = '#f44336';
                }
            })
            .catch(error => {
                usernameInfo.textContent = 'Error validating username';
                usernameInfo.style.color = '#f44336';
                console.error('Error:', error);
            });

    }, 500);
});