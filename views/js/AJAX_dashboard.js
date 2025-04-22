// * overlay elements
const overlay = document.getElementById('overlay');
const overlayText = document.getElementById('text');

// * divs elements


// * event listeners
document.getElementById('add-course-btn').addEventListener('click', async () => {

	// set values
	const placeholderValues = [
		'Name (Course College ID)',
		'Fullname (How do you call it?)',
		'Description',
		'Knowledge Area',
		'Career',
		'Credits (Number)',
		'Thematic Content',
		'Semester',
		'Professor'
	]

	// show modal with inputs to create course
	const courseData = await showModalMultiInput(

		'Add Course',
		'Enter course full info',
		'<span style="color: red;"> You can\'t edit this later!</span><br>'+
		'<span style="color: red;">Use your college course ID for correct course validation.</span><br>'+
		'<span style="color: darkblue;"> Credits must be a number.</span>',
		9,
		placeholderValues

	);

	// check if courseData is not null (cancelled)
    if (courseData) {

		// send AJAX
		const response = await AJAXRequest("Course work...", 'createCourse', {

			name: courseData[0],
			full_name: courseData[1],
			description: courseData[2],
			knowledge_area: courseData[3],
			career: courseData[4],
			credits: courseData[5],
			thematic_content: courseData[6],
			semester: courseData[7],
			professor: courseData[8]

		});

		// check if success
		if (response.success) {

			// show success message
			showModal("Success", response.title, response.message);

		} else {

			// show error message

			showModal("Error", response.title, response.message);
		}

    }

});

document.getElementById('add-note_rule-btn').addEventListener('click', async () => {

	// show modal with inputs to create note rules
	const ruleData = await showModalMultiInput(

		'Add Note Rule',

		'Enter note rule info<br>What is a note rule?<br><br>'+
		'These are rules linked to your courses made to know:<br>'+
		'- The minimum possible grade (Starting from 0.00)<br>'+
		'- Maximum possible grade (Up to 999.99)<br>'+
		'- How many grades your course allows (Maximum of 100)<br>',

		'<span style="color: red;"> You can\'t edit this later!</span><br>'+
		'<span style="color: darkblue;"> Note rule will be linked to the created course.</span>',
		3,
		['Your Course ID', 'Number of grades (Max 100)', 'Maximum grade (999.99)']

	);

	if (ruleData) {

		// show success message// send AJAX
		// * create course, note rule and link
		const response = await AJAXRequest("Note rule work...", 'createNoteRule', {

			course_id: ruleData[0], // for note rule
			note_count: ruleData[1],
			max_value: ruleData[2],

		});

		// check if success
		if (response.success) {

			// show success message
			showModal("Success", response.title, response.message);

			// refreshDashboard();

		} else {

			// show error message
			showModal("Error", response.title, response.message);

		}

	}


});

// > FETCHS AND AJAX FUNCTIONS < //

// - initial fetch: fetch account data and update the dashboard on load
document.addEventListener('DOMContentLoaded', () => {

	showOverlay('Fetching account data...');

	// fetch('Controllers/dashboard_controller.php', {
	// 	headers: { 'X-Requested-With': 'XMLHttpRequest' } })

	// 	.then(response => {
	// 		if (!response.ok) {
	// 			throw new Error('Network response was not ok');
	// 		}
	// 		return response.json();
	// 	})
	// 	.then(data => {
	// 		const accountData = data.accountData;
	// 		const dashboard = document.getElementById('dashboard');

	// 		if (dashboard) {
	// 			dashboard.innerHTML = ''; // Clear existing content

	// 			accountData.forEach(account => {
	// 				const accountDiv = document.createElement('div');
	// 				accountDiv.className = 'account';
	// 				accountDiv.innerHTML = `
	// 					<h3>${account.name}</h3>
	// 					<p>Balance: ${account.balance}</p>
	// 					<p>Transactions: ${account.transactions}</p>
	// 				`;
	// 				dashboard.appendChild(accountDiv);
	// 			});
	// 		}
	// 	})
	// 	.catch(error => {
	// 		showModal('Error', 'Failed to fetch account data. Please try again later.', error.message);
	// 		showModalInput('Test', 'This is a test message', 'No error');
	// 	})
		// .finally(() =>
		// 	hideOverlay()
		// );

	hideOverlay(); // Remove this line when the fetch is implemented

});

// - function to handle AJAX requests
async function AJAXRequest(title, method, requestData) {

	try {

		showOverlay('Sending request...', title);

		// set headers and data
		const options = {

			method: 'POST',
			headers: {

				'X-Requested-With': 'XMLHttpRequest',
				'Content-Type': 'application/json'

			},
			body: JSON.stringify(requestData)

		};

		// send request and wait
		const response = await fetch( `Controllers/dashboard_controller.php?action=${encodeURIComponent(method)}`, options );
		const responseData = await response.json();

		hideOverlay();
        return responseData;

	} catch (error) {
        hideOverlay();
        return {
            success: false,
            message: 'Request failed',
            error: error.message
        };
	}

}

// - function to reload account data and update dashboard
async function refreshDashboard() {
	showOverlay('Refreshing dashboard...');

	// send request and wait
	const response = await AJAXRequest('refreshDashboard', {});

	// check if success
	if (response.success) {

		// show success message
		showModal("Success", response.title, response.message);

	} else {
		// show error message
		showModal("Error", response.title, response.message);
	}

	hideOverlay();
}

// > OVERLAY RELATED FUNCTIONS < //

// function to show overlay with a message + loader icon
function showOverlay(text, info) {


	overlay.innerHTML = `
		<img src="views/svg/loader.svg" alt="" srcset="">
		<h1>${text}</h1>
		<p>${info}</p>
	`;

	if (overlay && overlayText) {
		overlayText.textContent = text;
		overlay.style.display = 'flex';
	}

}

// function to hide overlay
function hideOverlay() {

	overlay.innerHTML = '';

	if (overlay) {
		overlay.style.display = 'none';
	}

}

// function to show modal with error message
function showModal(title, message, info) {

	const modalDiv = document.createElement('div');
	modalDiv.className = 'col';
	modalDiv.innerHTML = `
		<h2>${title}</h2>
		<p>
			${message}
			<br>
			<small>${info}</small>
		</p>
		<button class="btn-man" onclick="hideOverlay()">OK!</button>
	`;

	overlay.innerHTML = '';
	overlay.appendChild(modalDiv);
	overlay.style.display = 'flex';

}

// function to show modal with input cancel and ok button + small text
function showModalInput(title, message, info, functionArg) {

	const modalDiv = document.createElement('div');

	modalDiv.className = 'col';
	modalDiv.innerHTML = `
		<h2>${title}</h2>
		<p>
			${message}
			<br>
			<input type="text" id="inputField" placeholder="Enter value">
			<br>
			<small>${info}</small>
		</p>

		<button id="modal_ok" class="btn-man" onclick="hideOverlay()">OK!</button>
		<button class="btn-sec" onclick="hideOverlay()">Cancel</button>
	`;

	overlay.innerHTML = '';
	overlay.appendChild(modalDiv);
	overlay.style.display = 'flex';

	// focus on input field
	const inputField = modalDiv.getElementById("inputField");

	// run function arg on ok click
	const okButton = modalDiv.getElementById("modal_ok");
	okButton.onclick = () => {

		const value = inputField.value;
		if (functionArg) functionArg(value);
		hideOverlay();

	};


}

// function to show modal with multiple inputs
async function showModalMultiInput(title, message, info, inputCount, inputsText = []) {

	return new Promise((resolve) => {

		const modalDiv = document.createElement('div');

		// inputs constructor
		let inputsHTML = '<div class="modal-inputs">';
		for (let i = 1; i <= inputCount; i++) {
			const placeholder = inputsText[i - 1] || `Enter value ${i}`;
			inputsHTML += `<input type="text" id="input-${i}" placeholder="${placeholder}">`;
		}
		inputsHTML += '</div>';

		modalDiv.className = 'col';
		modalDiv.innerHTML = `
			<h2>${title}</h2>
			<p>
				${message}
				<br>
				${inputsHTML}
			<small>${info}</small>
			</p>
			<button id="modal_ok" class="btn-man" onclick="hideOverlay()">OK!</button>
			<button id="modal_cancel" class="btn-sec" onclick="hideOverlay()">Cancel</button>
		`;

		overlay.innerHTML = '';
		overlay.appendChild(modalDiv);
		overlay.style.display = 'flex';

		// focus on first input field
		document.getElementById('input-1')?.focus();

		const okButton = document.getElementById("modal_ok");
		okButton.onclick = () => {

			// create array and save inputs values
			const values = [];
			for (let i = 1; i <= inputCount; i++) {
				values.push(document.getElementById(`input-${i}`).value);
			}
			hideOverlay();
			resolve(values); // resolve the promise with the values array

		};

		const calcellButton = document.getElementById("modal_cancel");
		calcellButton.onclick = () => {

			hideOverlay();
			resolve(null); // resolve with null if cancelled

		};

	})

}
