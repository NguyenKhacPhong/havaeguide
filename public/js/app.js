$(document).ready(function () {
    $('.nav-link.active .sub-menu').slideDown();
    // $("p").slideUp();

    $('#sidebar-menu .arrow').click(function () {
        $(this).parents('li').children('.sub-menu').slideToggle();
        $(this).toggleClass('fa-angle-right fa-angle-down');
    });

    $("input[name='checkall']").click(function () {
        var checked = $(this).is(':checked');
        $('.table-checkall tbody tr td input:checkbox').prop('checked', checked);
    });
});
var loadFile = function (output) {
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src) // free memory
    }
};
var loadFileLogo = function (event) {
    var output = document.getElementById('school_logo');
    loadFile(output);
};
var loadFileImage = function (event, column) {
    var output = document.getElementById(column);
    loadFile(output);
};



tinymce.init(
    {
        selector: 'textarea#school_description',
        menubar: false,
        toolbar: false,
        height: 380,
    }
);
tinymce.init(
    {
        selector: 'textarea#school_detail',
        themes: 'silver',
        plugins: "link image code table advtable lists checklist preview fullpage powerpaste fullscreen searchreplace autolink directionality advcode visualblocks visualchars media table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist textcolor wordcount tinymcespellchecker a11ychecker imagetools mediaembed",
        toolbar: 'undo redo | styleselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | outdent indent |formatselect | numlist bullist outdent indent  | removeformat | fullscreen',
        height: 500,
    }
);
