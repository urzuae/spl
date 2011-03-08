$.tablesorter.addParser({
        id: "horas",
        is: function(s) {
            return false;
        },
        format: function(s) {
            return s.toLowerCase().replace(/ d /,".").replace(/ horas/,"");
        },
        type: "numeric"
    }) ;
$(document).ready(function(){
    $(".tablesorter").tablesorter({
        headers: {
                2: {
                    sorter:"horas"
                },
            }
    });
});
