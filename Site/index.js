var expanded = false;
function expand() {
    if (!expanded) {
        document.getElementById("accordion").style.maxHeight = "930px";
        document.getElementById("arrow").style.transform = "rotate(-90deg)";
    }
    else {
        document.getElementById("accordion").style.maxHeight = "310px";
        document.getElementById("arrow").style.transform = "rotate(90deg)";
    }

    expanded = !expanded;
}

//quand on clique sur un 'top' selectionné on est mené sur la page du jeu mais sinon on le selectionne
function click(o) {
    alert(o);
    if (document.getElementById(o).checked) {
        alert("caca");
    } else { alert("pipi") }
}

//quand on clique ailleurs que sur un des "most downloaded", le top1 reviens devant
window.onclick = e => {
    if (e.target.type != "radio" && !document.getElementById('labelTop1').checked) {
        document.getElementById('labelTop1').checked = true;
    }
} 