document.querySelector("body").style.height = (window.innerHeight - 64) + "px";

function resize() {
    document.querySelector("body").style.height = (window.innerHeight - 64) + "px";
    console.log("resized");
}

function displayPopup(){
    console.log("displayed");
    document.getElementById('overlay').style.display = "block"
    document.getElementById('popup').style.display = "block"
}

function hidePopup(){
    console.log("hidden")
    document.getElementById('overlay').style.display = "none"
    document.getElementById('popup').style.display = "none"
}