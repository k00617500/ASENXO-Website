
let lastScroll = 0;
const navbar = document.getElementById('navbar');

window.addEventListener('scroll', () => {
 
  if (window.scrollY > 50) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
});

 window.addEventListener("load", () => {
    const preloader = document.getElementById("preloader");

    setTimeout(() => {
      preloader.classList.add("hide");
    }, 1200); 
  });
