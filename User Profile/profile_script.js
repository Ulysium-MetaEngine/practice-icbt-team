// Toggles the visibility of the Edit Profile form
function toggleEditForm(event) {
    event.preventDefault();
    const form = document.getElementById('editForm');
    form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
}

// Adds event listeners to the Save and Cancel buttons
document.addEventListener('DOMContentLoaded', () => {
    const saveButton = document.querySelector('.save-btn');
    const cancelButton = document.querySelector('.cancel-btn');
    
     //saving profile details
    saveButton.addEventListener('click', () => {
        alert('Profile changes saved successfully!');
    });

    cancelButton.addEventListener('click', (event) => {
        toggleEditForm(event);
    });
});
