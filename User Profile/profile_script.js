// Toggles the visibility of the Edit Profile form
function toggleEditForm(event) {
    event.preventDefault(); // Prevent the default behavior of the button or link
    const form = document.getElementById('editForm');
    const isVisible = form.style.display === 'block';

    // Toggle the display state
    form.style.display = isVisible ? 'none' : 'block';

    // Optionally reset form fields if needed
    if (isVisible) {
        form.reset(); // Reset the form when closing
    }
}

// Validates the form fields
function validateForm() {
    const fullName = document.getElementById('fullName').value.trim();
    const email = document.getElementById('email').value.trim();

    let errorMessage = '';

    if (!fullName) {
        errorMessage += 'Full Name is required.\n';
    }

    if (!email) {
        errorMessage += 'Email is required.\n';
    } else if (!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
        errorMessage += 'Please enter a valid email address.\n';
    }

    if (errorMessage) {
        alert(errorMessage); // Show error message
        return false; // Validation failed
    }

    return true; // Validation passed
}

// Adds event listeners to the Save and Cancel buttons
document.addEventListener('DOMContentLoaded', () => {
    const saveButton = document.querySelector('.save-btn');
    const cancelButton = document.querySelector('.cancel-btn');

    // Save button logic
    saveButton.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent form submission
        if (validateForm()) {
            alert('Profile changes saved successfully!');
            toggleEditForm(event); // Close the form after saving
        }
    });

    //  close the edit form
    cancelButton.addEventListener('click', (event) => {
        toggleEditForm(event);
    });
});
