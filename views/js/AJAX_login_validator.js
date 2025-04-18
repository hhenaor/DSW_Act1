const usernameInput = document.getElementById('username_form');
const usernameInfo = document.getElementById('username_info');

let timeout = null;

usernameInput.addEventListener('input', validateUsername );

function validateUsername() {
	usernameInput.addEventListener('input', function () {
		clearTimeout(timeout);

		timeout = setTimeout(() => {
			const username = this.value;

			if ((usernameInput.value).length < 1) {

				usernameInfo.textContent = 'Please enter an username.';
				usernameInfo.style.color = '#f44336';

			} else {

				fetch(`/controllers/input_controller.php?value=${encodeURIComponent(username)}&action=user&l=true`)
					.then(response => response.json())
					.then(data => {

						let message = data.message || data;
						usernameInfo.textContent = message;

						usernameInfo.style.color = '#f44336';
						if (message.includes("!") ) {
							usernameInfo.style.color = '#4CAF50';
						}
					})
					.catch(error => {
						usernameInfo.textContent = 'Error validating username.';
						usernameInfo.style.color = '#f44336';
						console.error('Error:', error);
					});

			}

		}, 500);
	});
}

