
    document.addEventListener("DOMContentLoaded", function() {
      var signupForm = document.getElementById("signup-form");
      var successMessage = document.getElementById("success-message");

      signupForm.addEventListener("submit", function(e) {
        e.preventDefault(); // Prevent the form from submitting normally

        // Serialize form data to send via POST request
        var formData = new FormData(signupForm);

        // Send the form data via a POST request to your server
        fetch("your_server_endpoint.php", {
          method: "POST",
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          // Assuming the registration was successful
          // Display the success message
          successMessage.style.display = "block";

          // Hide the success message after 1 second (1000 milliseconds)
          setTimeout(function() {
            successMessage.style.display = "none";
          }, 1000);
        })
        .catch(error => {
          console.error("Error:", error);
          // Handle errors here
        });
      });
    });