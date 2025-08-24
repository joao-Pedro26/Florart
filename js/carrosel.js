const slides = document.querySelectorAll(".slide");
const prevBtn = document.querySelector(".prev");
const nextBtn = document.querySelector(".next");
let current = 0;

function updateSlides() {
  slides.forEach((slide, i) => {
    const offset = i - current;
    if (offset === 0) {
      // Slide central
      slide.style.transform = "translateX(0) scale(1) rotateY(0deg)";
      slide.style.opacity = "1";
      slide.style.filter = "blur(0px)";
      slide.style.zIndex = 10;
    } else if (offset === -1) {
      // Esquerda próxima
      slide.style.transform = "translateX(-30vw) scale(0.7) rotateY(30deg)";
      slide.style.opacity = "0.4";
      slide.style.filter = "blur(2px)";
      slide.style.zIndex = 5;
    } else if (offset === 1) {
      // Direita próxima
      slide.style.transform = "translateX(30vw) scale(0.7) rotateY(-30deg)";
      slide.style.opacity = "0.4";
      slide.style.filter = "blur(2px)";
      slide.style.zIndex = 5;
    } else {
      // Slides mais distantes
      slide.style.transform = `translateX(${offset * 50}vw) scale(0.5)`;
      slide.style.opacity = "0";
      slide.style.filter = "blur(4px)";
      slide.style.zIndex = 1;
    }
  });
}

function nextSlide() {
  current = (current + 1) % slides.length;
  updateSlides();
}

function prevSlide() {
  current = (current - 1 + slides.length) % slides.length;
  updateSlides();
}

prevBtn.addEventListener("click", prevSlide);
nextBtn.addEventListener("click", nextSlide);

setInterval(nextSlide, 2000); 

updateSlides();
