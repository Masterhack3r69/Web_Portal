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

    const logosWrapper = document.querySelector('.logos-wrapper');
const logoContainers = document.querySelectorAll('.logo-container');
let currentIndex = 0;
const totalLogos = logoContainers.length;

function getLogoWidth() {
    return document.querySelector('.logo-img').offsetWidth + 10; // Adjust based on logo width + margin
}

// Function to move to the next logo
function moveToNext() {
    if (currentIndex < totalLogos - 1) {
        currentIndex++;
        updateSliderPosition();
    }
}

// Function to move to the previous logo
function moveToPrev() {
    if (currentIndex > 0) {
        currentIndex--;
        updateSliderPosition();
    }
}

// Update the position of the slider
function updateSliderPosition() {
    const offset = -currentIndex * getLogoWidth();
    logosWrapper.style.transform = `translateX(${offset}px)`;
}

// Add event listeners for navigation
document.addEventListener('DOMContentLoaded', () => {
    const leftArrow = document.createElement('button');
    leftArrow.className = 'arrow arrow-left';
    leftArrow.innerHTML = '&lt;';
    leftArrow.onclick = moveToPrev;

    const rightArrow = document.createElement('button');
    rightArrow.className = 'arrow arrow-right';
    rightArrow.innerHTML = '&gt;';
    rightArrow.onclick = moveToNext;

    document.querySelector('.department-container').appendChild(leftArrow);
    document.querySelector('.department-container').appendChild(rightArrow);

    // Adjust slider position on window resize for responsiveness
    window.addEventListener('resize', updateSliderPosition);
});


    </script>
   <script>
    // JavaScript to Show the Spinner Overlay with a Delay
    const spinnerLinks = document.querySelectorAll('.sign-out, .profile-dropdown, .dropdown-item[href="./users/notifications.php"], a:not([href^="index.php#"])');

    spinnerLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            // Check if the link is a same-page link
            const isSamePageLink = link.getAttribute('href').startsWith('index.php#');

            // If it's not a same-page link, show the spinner
            if (!isSamePageLink) {
                event.preventDefault(); // Prevent default action
                document.body.classList.add('show-spinner');

                // Delay navigation to let the spinner show
                setTimeout(() => {
                    window.location.href = link.href; // Navigate after delay
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