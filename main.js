var prevScrollpos = window.pageYOffset;

window.onscroll = function () {
  var currentScrollPos = window.pageYOffset;

  if (prevScrollpos < currentScrollPos) {
    document.getElementById("header").style.maxHeight = "60px";
  } else {
    document.getElementById("header").style.maxHeight = "75px";
  }
  prevScrollpos = currentScrollPos;
}

function removeWarning() {
  document.getElementById('content-blocker').outerHTML = '';
}

url = window.location.href;
window.onload = function () {
  if (!(url.includes("?")) && (url.includes("signup"))) { //si l'utilisateur n'a pas été renvoyé sur la page après une erreur
    document.getElementById('content-blocker-holder').innerHTML = "<div id='content-blocker'><div id = 'signup-warning'><p>We did not spend anything on security, please chose a unique password!</p><button id='warning-button' onclick='removeWarning()'>I understand</button></div></div>";
  }
}
// c'est plus facile de le supprimer si erreur mais il a le temps d'apparaitre si la connexion est pas tres rapide du coup on le créé si pas d'erreur