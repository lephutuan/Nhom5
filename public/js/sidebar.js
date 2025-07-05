function setActive(element) {
  document.querySelectorAll(".sidebar a").forEach((link) => {
    link.classList.remove("active");
  });
  element.classList.add("active");
}

function toggleSidebar() {
  const sidebar = document.querySelector(".sidebar");
  sidebar.classList.toggle("active");
}

// Highlight active sidebar link based on current page
document.addEventListener("DOMContentLoaded", function () {
  const currentPath = window.location.pathname.split("/").pop() || "home.html";
  document.querySelectorAll(".sidebar a").forEach((link) => {
    const linkPath = link.getAttribute("href").split("/").pop();
    if (linkPath === currentPath) {
      link.classList.add("active");
    } else {
      link.classList.remove("active");
    }
  });
});