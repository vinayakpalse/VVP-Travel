document.getElementById("submitbox").addEventListener("click", function (e) {
  var username1 = document.getElementById("username").value;
  var password1 = document.getElementById("passwordbox").value;
  e.preventDefault();
  if (password1 === "" || username1 === "") {
    alert("Please enter both a username and password");
  } else if (password1 != "" && username1 != "") {
    var newURL = "index.html";
    window.location.href = newURL;
  } else if (username1 === "admin" && password1 === "admin") {
    alert("Welcome admin");
  } else if (username1 === "user1" && password1 === "pass1234") {
    alert("Welcome user");
  }
});
  document.addEventListener('DOMContentLoaded', (event) => {
    const modal = document.getElementById("loginModal");
    const btn = document.getElementById("openLoginModal");
    const span = document.getElementsByClassName("close")[0];
    const form = document.getElementById("loginForm");

    btn.onclick = function() {
        modal.style.display = "block";
    }
    span.onclick = function() {
        modal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    form.onsubmit = function(event) {
        event.preventDefault();
        window.location.href = "index.html";
    }
});
