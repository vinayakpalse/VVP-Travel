document.getElementById("submitbutton").addEventListener("click", function(e) 
{
    var username1 = document.getElementById("usernamebox").value
    var password1 = document.getElementById("passwordbox1").value
    var email1 =document.getElementById("email").value
    e.preventDefault();
    if(password1 != "" && username1 != "" && email1 != "")
    {var newURL = "login.html";
    window.location.href = newURL;}
    
    else
    alert("Please enter a username,email and password")
}
);  