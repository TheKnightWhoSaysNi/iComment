function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#cover')
                .attr('src', e.target.result)
                .width(10*20)
                .height(10*29); //une image de jeu doit avoir un ratio de 20:29
            document.getElementById("cover").style.display = "flex";
        };

        reader.readAsDataURL(input.files[0]);
    }
}