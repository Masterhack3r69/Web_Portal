// fade-in section
const fadeInSection = document.querySelector('.fade-in-section');

const sectionOptions = {
  root: null,  
  rootMargin: '0px',
  threshold: 0.1 
};

const sectionObserver = new IntersectionObserver((entries, observer) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('in-view');
      observer.unobserve(entry.target); 
    }
  });
}, sectionOptions);
sectionObserver.observe(fadeInSection);


const fadeInElements = document.querySelectorAll('.fade-in-bottom');

const elementObserverOptions = {
  root: null,  
  rootMargin: '0px',
  threshold: 0.2  
};

const elementObserver = new IntersectionObserver((entries, observer) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('in-view');
      observer.unobserve(entry.target);  
    }
  });
}, elementObserverOptions);

fadeInElements.forEach(element => elementObserver.observe(element));


document.getElementById("messageForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const emailInput = document.getElementById('floatingInput');
  const emailError = document.getElementById('emailError');

  const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

  if (!emailPattern.test(emailInput.value)) {
      emailError.style.display = 'block'; 
      return; 
  }

  emailError.style.display = 'none'; 

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



function toggleMenu() {
  const menu = document.getElementById('responsiveMenu');
  menu.classList.toggle('show'); 
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



const logosWrapper = document.querySelector('.logos-wrapper');
const logoContainers = document.querySelectorAll('.logo-container');
let currentIndex = 0;
const totalLogos = logoContainers.length;

function getLogoWidth() {
  return document.querySelector('.logo-img').offsetWidth + 10; 
}

// Function to move to the next logo
function moveToNext() {
  if (currentIndex < totalLogos - 1) {
      currentIndex++;
      updateSliderPosition();
  }
}

function moveToPrev() {
  if (currentIndex > 0) {
      currentIndex--;
      updateSliderPosition();
  }
}


function updateSliderPosition() {
  const offset = -currentIndex * getLogoWidth();
  logosWrapper.style.transform = `translateX(${offset}px)`;
}

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


