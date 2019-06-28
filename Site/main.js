var prevScrollpos = window.pageYOffset;

window.onscroll = function () {
  var currentScrollPos = window.pageYOffset;
  if (document.documentElement.clientWidth > 60){
    if (prevScrollpos < currentScrollPos) {
      document.getElementById("header").style.maxHeight = "60px";
      try{  //try pour pas qu'il y ait d'erreur si "errorBox" a pas encore été créé
        document.getElementById("errorBox").style.top = "60px";
      }catch(error){}
      try {
        document.getElementById("successBox").style.top = "60px";
      } catch (error) { }


    } else {
      document.getElementById("header").style.maxHeight = "75px";
      try{
        document.getElementById("errorBox").style.top = "75px";
      }catch(error){}
      try {
        document.getElementById("successBox").style.top = "75px";
      } catch (error) { }

    }
  }
  prevScrollpos = currentScrollPos;
}

function removeContentBlocker() {
  document.getElementById('content-blocker').outerHTML = '';
}

function openSearch(){
  document.getElementById("searchBar").style.width = "300px";
  document.getElementById("textInput").style.width = "250px";
  document.getElementById("textInput").placeholder = "Search for a game";
  document.getElementById("searchButton").style.display = "flex";
  if(window.innerWidth > 360){
    document.getElementById("searchButton").style.width = "50px";
  } else {
    document.getElementById("searchButton").style.width = "30px";
    document.getElementById("logo").style.display = "none";
    document.getElementsByClassName("navBar")[0].style.display = "none";
  }
}
function closeSearch(){
  document.getElementById("searchBar").style.width = "100px";
  document.getElementById("textInput").style.width = "100px";
  document.getElementById("textInput").placeholder = "Search";
  document.getElementById("searchButton").style.display = "none";
  document.getElementById("searchButton").style.width = "0px";
  if (window.innerWidth <= 360) {
    document.getElementById("logo").style.display = "flex";
    document.getElementsByClassName("navBar")[0].style.display = "flex";
  }
}


url = window.location.href;
window.onload = function () {

  if (typeof (document.getElementById("errorBox")) != 'undefined' && document.getElementById("errorBox") != null) { //si l'élément existe
    setTimeout(function () { document.getElementById("errorBox").style.maxHeight = "0"; document.getElementById('errorBox').style.border = 'none'}, 6000);
  }
  if (typeof (document.getElementById("successBox")) != 'undefined' && document.getElementById("successBox") != null) { //pareil
    setTimeout(function () { document.getElementById("successBox").style.maxHeight = "0"; document.getElementById('successBox').style.border = 'none'}, 6000);
  }

  if (window.innerWidth < 1050) {
    closeSearch();
  } else {
    openSearch();
  }

  if (window.location.href.includes("#login")) {
    document.getElementById("signup").style.display = "none";
    document.getElementById("confirmEmail").style.display = "none";
    document.getElementById("login").style.display = "flex";
  }
  else if (window.location.href.includes("#signup")) {
    document.getElementById("login").style.display = "none";
    document.getElementById("confirmEmail").style.display = "none";
    document.getElementById("signup").style.display = "flex";
    if (!window.location.href.includes("error")) {
      document.getElementById('content-blocker-holder').innerHTML = "<div id='content-blocker'><div id = 'signup-warning'><p>We did not spend anything on security, please chose a unique password!</p><button id='warning-button' onclick='removeContentBlocker()'>I understand</button></div></div>";
    }
  }
  else if (window.location.href.includes("confirmEmail")) { 
    document.getElementById("signup").style.display = "none";
    document.getElementById("login").style.display = "none";
    document.getElementById("confirmEmail").style.display = "flex";
  }
  else {
    document.getElementById("login").style.display = "none";
    document.getElementById("signup").style.display = "none";
  }

  if (!(url.includes("?")) && (url.includes("signup"))) { //si l'utilisateur n'a pas été renvoyé sur la page après une erreur
    document.getElementById('content-blocker-holder').innerHTML = "<div id='content-blocker'><div id = 'signup-warning'><p>We did not spend anything on security, please chose a unique password!</p><button id='warning-button' onclick='removeContentBlocker()'>I understand</button></div></div>";
  }
  if (url.includes("error")){
    document.getElementById("errorBox").style.maxHeight = "100px";
  }
  
}  // c'est plus facile de le supprimer si erreur mais il a le temps d'apparaitre si la connexion est pas tres rapide du coup on le créé si pas d'erreur



window.onhashchange = function(){
  if (window.location.href.includes("#login")) {
    document.getElementById("signup").style.display = "none";
    document.getElementById("confirmEmail").style.display = "none";
    document.getElementById("login").style.display = "flex";
  }
  else if (window.location.href.includes("#signup")) {
    document.getElementById("login").style.display = "none";
    document.getElementById("confirmEmail").style.display = "none";
    document.getElementById("signup").style.display = "flex";
    if(!window.location.href.includes("error")){
      document.getElementById('content-blocker-holder').innerHTML = "<div id='content-blocker'><div id = 'signup-warning'><p>We did not spend anything on security, please chose a unique password!</p><button id='warning-button' onclick='removeContentBlocker()'>I understand</button></div></div>";
    }
  }
  else if (window.location.href.includes("confirmEmail")){
    document.getElementById("signup").style.display = "none";
    document.getElementById("login").style.display = "none";
    document.getElementById("confirmEmail").style.display = "flex";
  }
  else {
    document.getElementById("login").style.display = "none";
    document.getElementById("signup").style.display = "none";
  }
}


var previousWidth = 0;
window.onresize = function(){ //on peut pas utiliser window.onresize parceque sur mobile quand le clavier s'ouvre ca compte comme un resize (vertical je suppose) du coup il faut l'équivalent de onresize mais uniquement horizontal
  var width = window.innerWidth;
  if(width != previousWidth){
    if(window.innerWidth < 1050){
      closeSearch();
    } else {
      openSearch();
    }
    previousWidth = width;
  }
}