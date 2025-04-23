// * overlay elements
const overlay = document.getElementById('overlay');

// * divs elements
const courseTabDiv = document.getElementById('course-tab');
const totalCoursesP = document.getElementById('total-courses');
const noteRuleTabDiv = document.getElementById('note-rule-tab');
const totalNoteRulesP = document.getElementById('total-note-rules');

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

		await showOverlay('Sending request...' );

		// send AJAX
		const response = await AJAXRequest('createCourse', {

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
			refreshDashboard();

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

		await showOverlay('Sending request...' );

		// send AJAX
		// * create note rule and link
		const response = await AJAXRequest('createNoteRule', {

			course_id: ruleData[0], // for note rule
			note_count: ruleData[1],
			max_value: ruleData[2],

		});

		// check if success
		if (response.success) {

			// show success message
			showModal("Success", response.title, response.message);

			refreshDashboard();

		} else {

			// show error message
			showModal("Error", response.title, response.message);

		}

	}


});

document.getElementById('add-notes-btn').addEventListener('click', async () => {

    const noteData = await showModalMultiInput(
        'Add Note',
        'Enter the details for the new note:',
        'Ensure the Note Rule ID exists and belongs to one of your courses.<br>The note value must be numeric and within the rule\'s limits.',
        3,
        ['Note Rule ID', 'Note Value (e.g., 4.5)', 'Comment (Optional)']
    );

    if (noteData) {
        if (!noteData[0] || noteData[0].trim() === '' || !noteData[1] || noteData[1].trim() === '') {
            showModal("Info", "Input Required", "Note Rule ID and Note Value are required.");
            return;
        }

        await showOverlay('Adding note...');

        const response = await AJAXRequest('createNotes', {
            note_rule_id: noteData[0].trim(),
            note_value: noteData[1].trim(),
            comment: noteData[2].trim()
        });

        if (response.success) {

            showModal("Success", response.title, response.message);
            refreshDashboard();
        } else {
            showModal("Error", response.title, response.message);
        }

    }
});


// > FETCHS AND AJAX FUNCTIONS < //

// - initial fetch: fetch account data and update the dashboard on load
document.addEventListener('DOMContentLoaded', () => {

	// refresh on load
	refreshDashboard();

});

// - function to handle AJAX requests
async function AJAXRequest(method, requestData) {

	try {

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

        return responseData;

	} catch (error) {

        return {

            success: false,
            title: 'Request Failed.',
            error: error.message

        };

	}

}


// - function to reload account data and update dashboard
async function refreshDashboard() {

	showOverlay('Refreshing dashboard...');

	courseTabDiv.innerHTML = '<p>Loading courses...</p>';
	totalCoursesP.textContent = 'Linked to 0 courses.';
	if (noteRuleTabDiv) noteRuleTabDiv.innerHTML = '<p>Loading note rules...</p>';
	if (totalNoteRulesP) totalNoteRulesP.textContent = 'Found 0 note rules.';

	const response = await AJAXRequest('refreshDashboard');

    courseTabDiv.innerHTML = '';
    if (noteRuleTabDiv) noteRuleTabDiv.innerHTML = '';


	if ( response.success && response.data ) {

		const courses = response.data.courses;
		if ( Array.isArray(courses) ) {
			totalCoursesP.textContent = `Linked to ${courses.length} courses.`;
			if (courses.length > 0) {
				renderCoursesTable(courses);
			} else {
				courseTabDiv.innerHTML = '<p>No courses found.</p>';
			}
		} else {
			totalCoursesP.textContent = 'Error loading courses.';
			courseTabDiv.innerHTML = `<p style="color: red;">Could not load course data.</p>`;
			console.error("Course data is not an array:", courses);
		}

        if (noteRuleTabDiv && totalNoteRulesP) {
            const noteRules = response.data.note_rules;
            if ( Array.isArray(noteRules) ) {
                totalNoteRulesP.textContent = `Found ${noteRules.length} note rules.`;
                if (noteRules.length > 0) {
                    renderNoteRulesTable(noteRules);
                } else {
                    noteRuleTabDiv.innerHTML = '<p>No note rules found.</p>';
                }
            } else {
                totalNoteRulesP.textContent = 'Error loading note rules.';
                noteRuleTabDiv.innerHTML = `<p style="color: red;">Could not load note rule data.</p>`;
                console.error("Note rule data is not an array:", noteRules);
            }
        } else {
             console.warn("Note rule display elements not found in the DOM.");
        }


	} else {
		const errorMessage = response.message || "Could not retrieve data from server.";
		const errorTitle = response.title || "Error loading dashboard";

		courseTabDiv.innerHTML = `<p style="color: red;">Error: ${errorMessage}</p>`;
        if (noteRuleTabDiv) noteRuleTabDiv.innerHTML = `<p style="color: red;">Error: ${errorMessage}</p>`;

		showModal("Error", errorTitle, errorMessage);
	}

	hideOverlay();

}

function renderCoursesTable(courses) {
	const table = document.createElement('table');
	table.className = 'courses-table';

	const thead = table.createTHead();
	const headerRow = thead.insertRow();
	const headers = ['ID', 'Name', 'Full Name', 'Credits', 'Professor', 'Actions'];
	headers.forEach(text => {
		const th = document.createElement('th');
		th.textContent = text;
		headerRow.appendChild(th);
	});

	const tbody = table.createTBody();
	courses.forEach(course => {
		const row = tbody.insertRow();

		row.insertCell().textContent = course.course_id;
		row.insertCell().textContent = course.name;
		row.insertCell().textContent = course.full_name;
		row.insertCell().textContent = course.credits;
		row.insertCell().textContent = course.professor;

		const cellActions = row.insertCell();
		cellActions.innerHTML = `
			<button class="btn-gry btn-small" onclick="deleteCourse(${course.course_id})">Delete</button>
            <!-- <button class="btn-sec btn-small" onclick="viewCourseDetails(${course.course_id})">View</button> -->
		`;
	});

	courseTabDiv.innerHTML = '';
	courseTabDiv.appendChild(table);
}

function renderNoteRulesTable(noteRules) {
    if (!noteRuleTabDiv) return;

	const table = document.createElement('table');
	table.className = 'note-rules-table';

	const thead = table.createTHead();
	const headerRow = thead.insertRow();

	const headers = ['Rule ID', 'Course ID', 'Note Count', 'Max Value', 'Actions'];
	headers.forEach(text => {
		const th = document.createElement('th');
		th.textContent = text;
		headerRow.appendChild(th);
	});

	const tbody = table.createTBody();
	noteRules.forEach(rule => {
		const row = tbody.insertRow();

		row.insertCell().textContent = rule.note_rule_id;
		row.insertCell().textContent = rule.course_id;
		row.insertCell().textContent = rule.note_count;
		row.insertCell().textContent = rule.max_value;

		const cellActions = row.insertCell();
		cellActions.innerHTML = `
			<button class="btn-gry btn-small" onclick="deleteNoteRule(${rule.note_rule_id})">Delete</button>
		`;
	});

	noteRuleTabDiv.innerHTML = '';
	noteRuleTabDiv.appendChild(table);
}


// > OVERLAY RELATED FUNCTIONS < //

// function to show overlay with a message + loader icon
async function showOverlay(text, info) {

	overlay.innerHTML = `

		<img src="views/svg/loader.svg" alt="">
		<h1>${text}</h1>
		<p>${info}</p>

	`;
	if (overlay) {
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
	modalDiv.className = 'modal-content col';

	modalDiv.innerHTML = `

		<h2>${title}</h2>
		<p>
			${message}
			${info ? `<br><small>${info}</small>` : ''} <!-- Mostrar info solo si existe -->
		</p>
		<button class="btn-man" onclick="hideOverlay()">OK!</button>

	`;

	overlay.innerHTML = ''; // Limpiar overlay antes de añadir el modal
	overlay.appendChild(modalDiv);
	overlay.style.display = 'flex'; // Asegurarse que el overlay es visible

}

// function to show modal with input cancel and ok button + small text
function showModalInput(title, message, info, functionArg) {

	return new Promise((resolve) => {

		const modalDiv = document.createElement('div');

		modalDiv.className = 'modal-content col';
		modalDiv.innerHTML = `
			<h2>${title}</h2>
			<p>
				${message}
				<br>
				<input type="text" id="modalInputField" placeholder="Enter value">
				<br>
				<small>${info}</small>
			</p>

			<button id="modal_ok" class="btn-man">OK!</button>
			<button id="modal_cancel" class="btn-sec">Cancel</button>
		`;

		overlay.innerHTML = '';
		overlay.appendChild(modalDiv);
		overlay.style.display = 'flex';

		const inputField = document.getElementById("modalInputField");
		inputField?.focus();

		const okButton = document.getElementById("modal_ok");
		okButton.onclick = () => {

			const value = inputField.value;
			hideOverlay();
			resolve(value);
			if (functionArg) functionArg(value);

		};

		const cancelButton = document.getElementById("modal_cancel");
		cancelButton.onclick = () => {
			hideOverlay();
			resolve(null);
		};

	});

}


// function to show modal with multiple inputs
async function showModalMultiInput(title, message, info, inputCount, inputsText = []) {

	return new Promise((resolve) => {

		const modalDiv = document.createElement('div');

		// inputs constructor
		let inputsHTML = '<div class="modal-inputs">';
		for (let i = 1; i <= inputCount; i++) {
			const placeholder = inputsText[i - 1] || `Enter value ${i}`;
			inputsHTML += `
				<input type="text" id="input-${i}" placeholder="${placeholder}">
			`;
		}
		inputsHTML += '</div>';

		modalDiv.className = 'modal-content col';
		modalDiv.innerHTML = `
			<h2>${title}</h2>
			<p>${message}</p>
			${inputsHTML}
			<small>${info}</small>
			<button id="modal_ok" class="btn-man">OK!</button>
			<button id="modal_cancel" class="btn-sec">Cancel</button>
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

		const cancelButton = document.getElementById("modal_cancel"); // Corregido 'calcellButton'
		cancelButton.onclick = () => {

			hideOverlay();
			resolve(null); // resolve with null if cancelled

		};

	})

}
