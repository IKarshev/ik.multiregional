$(function(){
    $(".open_popup").on("click", function(event){
        event.preventDefault();

        var target_popup_id = $(this).data("popup");

        var formPopup = new BX.PopupWindow("my_answer", null, {
                content: BX(`${target_popup_id}`),
                closeIcon: {right: "20px", top: "10px" },
                titleBar: {content: BX.create("span", {'props': {'className': 'access-title-bar'}})}, 
                zIndex: 0,
                offsetLeft: 0,
                offsetTop: 0,
                draggable: COMPONENT_PARAMS.DRAGGABLE,
                resizable: COMPONENT_PARAMS.RESIZABLE,
                closeByEsc: true,
                overlay: {
                    backgroundColor: '#000',
                    opacity: 500
                }, 
            }
        );

        formPopup.show()
        
    });
});