document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("loginForm");
    const emailInput = document.getElementById("emailInput");
    const passwordInput = document.getElementById("passwordInput");
    const errorContainer = document.getElementById("errorContainer");

    form.addEventListener("submit", (event) => {
        // Clear previous errors
        errorContainer.innerHTML = "";

        let errors = [];

        // Email validation
        if (!validateEmail(emailInput.value)) {
            errors.push("Please enter a valid email address.");
        }

        // Password validation
        if (passwordInput.value.trim() === "") {
            errors.push("Password cannot be empty.");
        }

        // Display errors if any
        if (errors.length > 0) {
            event.preventDefault(); // Prevent form submission
            errorContainer.style.display = "block";
            errors.forEach((error) => {
                const errorItem = document.createElement("p");
                errorItem.classList.add("error");
                errorItem.textContent = error;
                errorContainer.appendChild(errorItem);
            });
        }
    });

    // Email format validation
    function validateEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
});
