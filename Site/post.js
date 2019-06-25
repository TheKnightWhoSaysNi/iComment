function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#cover')
                .attr('src', e.target.result)

            document.getElementById("cover").style.display = "flex";
        };

        reader.readAsDataURL(input.files[0]);
    }
}