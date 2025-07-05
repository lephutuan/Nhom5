function toggleCart() {
  const dropdown = document.getElementById("cartDropdown");
  dropdown.classList.toggle("active");
}

document.addEventListener("click", function (event) {
  const cart = document.querySelector(".cart");
  const dropdown = document.getElementById("cartDropdown");
  if (!cart.contains(event.target)) {
    dropdown.classList.remove("active");
  }
});
