//Tilda Källström 2022
function hamburgermenu(m) {
  m.classList.toggle("change");
}


// menu
let navbar = document.querySelector(".navbar")
let ham = document.querySelector(".ham")

function toggleHamburger() {
  // open
  navbar.classList.toggle("showNav")
  // close
  ham.classList.toggle("showClose")
}
// eventlistener to click
ham.addEventListener("click", toggleHamburger)

