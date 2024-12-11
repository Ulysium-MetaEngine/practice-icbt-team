// JavaScript for handling cancel button functionality

document.addEventListener("DOMContentLoaded", () => {
    const cancelButton = document.querySelector(".cancel-btn");

    cancelButton.addEventListener("click", () => {
        if (confirm("Are you sure you want to cancel? Any unsaved changes will be lost.")) {
            window.location.href = "index.php"; // Redirect to the home page or another appropriate page
        }
    });
});
