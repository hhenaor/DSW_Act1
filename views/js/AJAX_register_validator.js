const usernameInput = document.getElementById('username_form');
const usernameInfo = document.getElementById('username_info');

const emailInput = document.getElementById('email_form');
const emailInfo = document.getElementById('email_info');

const passwordInput = document.getElementById('password_form');
const passwordCheckInput = document.getElementById('password_check_form');
const passwordInfo = document.getElementById('password_info');

let timeout = null;

usernameInput.addEventListener('input', validateUsername );

emailInput.addEventListener('input', validateEmail );

passwordInput.addEventListener('input', validatePasswords);
passwordCheckInput.addEventListener('input', validatePasswords);

function validateUsername() {
	usernameInput.addEventListener('input', function () {
		clearTimeout(timeout);

		timeout = setTimeout(() => {
			const username = this.value;

			fetch(`/controllers/input_controller.php?action=userRegister&value1=${encodeURIComponent(username)}`, {
				headers: { 'X-Requested-With': 'XMLHttpRequest' } })

				.then(response => response.json())
				.then(data => {

					let message = data.message || data;
					usernameInfo.textContent = message;

					usernameInfo.style.color = '#f44336';
					if (message.includes('!')) {
						usernameInfo.style.color = '#4CAF50';
					}
				})
				.catch(error => {
					usernameInfo.textContent = 'Error validating username.';
					usernameInfo.style.color = '#f44336';
					console.error('Error:', error);
				});

		}, 500);
	});
}

function validateEmail() {
    clearTimeout(timeout);

    timeout = setTimeout(() => {
        const email = this.value;

		if ( (emailInput.value).length < 1 ) {

			emailInfo.textContent = 'Please enter an email.';
			emailInfo.style.color = '#f44336';

		} else {

			fetch(`/controllers/input_controller.php?action=email&value1=${encodeURIComponent(email)}`, {
				headers: { 'X-Requested-With': 'XMLHttpRequest' } })
				.then(response => response.json())
				.then(data => {

					let message = data.message || data;
					emailInfo.textContent = message;

					emailInfo.style.color = '#f44336';
					if (message.includes('!')) {
						emailInfo.style.color = '#4CAF50';
					}
				})
				.catch(error => {
					emailInfo.textContent = 'Error validating email.';
					emailInfo.style.color = '#f44336';
					console.error('Error:', error);
				});

		}

    }, 500);
}

function validatePasswords() {
	clearTimeout(timeout);

	timeout = setTimeout(() => {
		const password1 = passwordInput.value;
		const password2 = passwordCheckInput.value;

		if (password1.length < 1 || password2.length < 1) {
			passwordInfo.textContent = 'A password input is missing.';
			passwordInfo.style.color = '#f44336';
		} else {
			fetch(`/controllers/input_controller.php?action=pass&value1=${encodeURIComponent(password1)}&value2=${encodeURIComponent(password2)}`, {
				headers: { 'X-Requested-With': 'XMLHttpRequest' } })
				.then(response => response.json())
				.then(data => {
					let message = data.message || data;
					passwordInfo.textContent = message;

					passwordInfo.style.color = '#f44336';
					if (message.includes('!')) {
						passwordInfo.style.color = '#4CAF50';
					}
				})
				.catch(error => {
					passwordInfo.textContent = 'Error validating password.';
					passwordInfo.style.color = '#f44336';
					console.error('Error:', error);
				});
		}
	}, 500);
}
