<footer class="py-5 " style="background-color: #21BF73;">
  <nav aria-label="Footer Navigation">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="index.php#" class="nav-link px-2 text-light">Home</a></li>
      <li class="nav-item"><a href="program.php" class="nav-link px-2 text-light">Programs</a></li>
      <li class="nav-item"><a href="depsrtment.php" class="nav-link px-2 text-light">Departments</a></li>
      <li class="nav-item"><a href="news_update.php" class="nav-link px-2 text-light">News & Updates</a></li>
      <li class="nav-item"><a href="index.php#about" class="nav-link px-2 text-light">About Us</a></li>
    </ul>
  </nav>
  <p class="text-center text-light">© 2024 Company, Inc</p>
</footer>

    <script src="../assets/js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/main.js"></script>
    
    <script>
      function toggleMenu() {
          const menu = document.getElementById('responsiveMenu');
          menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'block' : 'none';
        }

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
      e.preventDefault(); 

      const formData = new FormData(this);
      fetch("submit_message.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.text())
        .then((data) => {
          Swal.fire({
            title: "Success!",
            text: data,
            icon: "success",
            confirmButtonText: "OK",
          });
          document.getElementById("messageForm").reset();
        })
        .catch((error) => {
          console.error("Error:", error);
          Swal.fire({
            title: "Error!",
            text: "An error occurred. Please try again.",
            icon: "error",
            confirmButtonText: "OK",
          });
        });
    });

    </script>
    <script>
    // JavaScript to Show the Spinner Overlay with a Delay
    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function(event) {
            // Check if the link is an internal link (same page)
            const isSamePageLink = link.getAttribute('href').startsWith('index.php#');

            // If it's not a same-page link, show the spinner
            if (!isSamePageLink) {
                event.preventDefault();
                document.body.classList.add('show-spinner');

                // Delay navigation to let the spinner show
                setTimeout(() => {
                    window.location.href = link.href;
                }, 300); // 300ms delay to match transition
            }
        });
    });

    // Hide the spinner overlay after page load with a slight delay
    window.addEventListener('load', () => {
        setTimeout(() => {
            document.body.classList.remove('show-spinner');
        }, 300); // 300ms delay to let the fade-out transition finish
    });
</script>

    
  </body>
</html>