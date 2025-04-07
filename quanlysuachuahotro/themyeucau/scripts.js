// Handle form submission (simulated response)
document.querySelector("form").addEventListener("submit", function (event) {
  event.preventDefault();

  // Simulating form submission success
  document.querySelector(".form-container").style.display = "none";
  document.querySelector(".result-container").style.display = "block";
});
