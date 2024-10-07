document.getElementById("login-form").addEventListener("submit", function(event) {
    const username = this.username.value;
    const password = this.password.value;

    // Basic front-end validation
    if (username === "" || password === "") {
        event.preventDefault();
        document.getElementById("login-message").textContent = "Please fill in all fields.";
    }
});

document.getElementById("contact-form").addEventListener("submit", function(event) {
    const fullname = this.fullname.value;
    const email = this.email.value;
    const message = this.message.value;

    // Basic front-end validation
    if (fullname === "" || email === "" || message === "") {
        event.preventDefault();
        document.getElementById("contact-message").textContent = "Please fill in all fields.";
    } else {
        // Reset the form on successful submission
        this.reset();
        document.getElementById("contact-message").textContent = "Message sent! Thank you for contacting us.";
    }
});
