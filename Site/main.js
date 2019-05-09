var prevScrollpos = window.pageYOffset;

window.onscroll = function () {
  var currentScrollPos = window.pageYOffset;
  if (document.documentElement.clientWidth > 600){
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

function refreshHash(){
  
}


url = window.location.href;
window.onload = function () {

  if (typeof (document.getElementById("errorBox")) != 'undefined' && document.getElementById("errorBox") != null) { //si l'élément existe
    setTimeout(function () { document.getElementById("errorBox").style.maxHeight = "0"; document.getElementById('errorBox').style.border = 'none'}, 6000);
  }
  if (typeof (document.getElementById("successBox")) != 'undefined' && document.getElementById("successBox") != null) { //pareil
    setTimeout(function () { document.getElementById("successBox").style.maxHeight = "0"; document.getElementById('successBox').style.border = 'none'}, 6000);
  }

  

  if (window.location.href.includes("#login")) {
    document.getElementById("signup").style.display = "none";
    document.getElementById("login").style.display = "flex";
  }
  else if (window.location.href.includes("#signup")) {
    document.getElementById("login").style.display = "none";
    document.getElementById("signup").style.display = "flex";
    if (!window.location.href.includes("error")) {
      document.getElementById('content-blocker-holder').innerHTML = "<div id='content-blocker'><div id = 'signup-warning'><p>We did not spend anything on security, please chose a unique password!</p><button id='warning-button' onclick='removeContentBlocker()'>I understand</button></div></div>";
    }
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
    document.getElementById("login").style.display = "flex";
  }
  else if (window.location.href.includes("#signup")) {
    document.getElementById("login").style.display = "none";
    document.getElementById("signup").style.display = "flex";
    if(!window.location.href.includes("error")){
      document.getElementById('content-blocker-holder').innerHTML = "<div id='content-blocker'><div id = 'signup-warning'><p>We did not spend anything on security, please chose a unique password!</p><button id='warning-button' onclick='removeContentBlocker()'>I understand</button></div></div>";
    }
  }
  else {
    document.getElementById("login").style.display = "none";
    document.getElementById("signup").style.display = "none";
  }
  

}