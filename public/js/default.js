$(document).ready(function(){
    $("#lhs-menu #close-btn").click(function(){
        $("#lhs-menu").toggle();
    });

    $("#header-bar #lhs-toggle").click(function(){
        $("#lhs-menu").toggle();
    });
});