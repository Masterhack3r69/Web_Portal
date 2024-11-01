  <footer class="py-5" style="background-color: #21BF73;">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="#" class="nav-link px-2 text-light">Home</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-light">Programs</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-light">Departments</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-light">FAQs</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-light">About Us</a></li>
    </ul>
    <p class="text-center text-light">© 2024 Company, Inc</p>
  </footer>
    
    <script src="../assets/js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/main.js"></script>
    
    <script>
      window.addEventListener('scroll', function() {
        const header = document.querySelector('header');
        const scrollPosition = window.scrollY;
        if (scrollPosition > 50) { 
          header.classList.add('scrolled');
        } else {
          header.classList.remove('scrolled');
        }
      });

      document.getElementById("messageForm").addEventListener("submit", function (e) {
      e.preventDefault(); // Prevent the default form submission

      // Prepare form data
      const formData = new FormData(this);

      // Send the AJAX request
      fetch("submit_message.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.text())
        .then((data) => {
          // Show SweetAlert2 with the response message
          Swal.fire({
            title: "Success!",
            text: data,
            icon: "success",
            confirmButtonText: "OK",
          });
          document.getElementById("messageForm").reset(); // Reset the form fields
        })
        .catch((error) => {
          console.error("Error:", error);
          // Show SweetAlert2 for errors
          Swal.fire({
            title: "Error!",
            text: "An error occurred. Please try again.",
            icon: "error",
            confirmButtonText: "OK",
          });
        });
    });

    </script>

  </body>
</html>