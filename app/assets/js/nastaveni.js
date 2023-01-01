$(document).ready(function () {
    $(':input#opravneni').on('change', function () {
        var sel_opt = $(this).val();
        if (sel_opt == 0) {
            $('#popup').modal('toggle');
            $('#popup').modal('show');
            $('#popup').modal('hide');
        }
    })
});