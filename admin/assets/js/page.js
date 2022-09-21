var win = navigator.platform.indexOf('Win') > -1;
if (win && document.querySelector('#sidenav-scrollbar')) {
    var options = {
        damping: '0.5'
    }
    
    Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
}

$(document).ready(function () {
    $("body").on("click","._delrow",function(e){
        var el = $(this);
        bootbox.dialog({
            message: "Weet je zeker dat je deze wilt verwijderen?",
            title: "Delete item?",
            buttons: {
                cancel: {
                    label: "NO"
                },
                main: {
                    label: "YES",
                    callback: function(){ 
                        var id = el.attr("lang"); 
                        $.post('ajax.php?type=del', { id: id}, function(data){
                            if($.trim(data) == 'true'){
                                
                                $("tr#item_"+id).remove();
                                
                            }
                        });	
                    }
                }
            }
        });
        e.preventDefault();
    });

    $('body').on('click','._show-signature',function(){
        console.log($(this).parent());
        $(this).parent().find('._signature-item').toggleClass('d-none');
    });
});
