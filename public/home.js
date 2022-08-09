$(document).ready( function () {
    loadData();
    $("#formSubmit").on("submit",function(event){
        event.preventDefault();
        const formData = new FormData(event.currentTarget);
        let column={};
        for (let [key, value] of formData.entries()) {
            if(key!=="id"&&value === ""){
                alert(`${key} is not empty`);
                $(`#${key}`).focus();
                return false;
            }
            Object.assign(column,{[key]:value})
        }
        $("#btnSubmit").text("loading ...");


        $.ajax({
            data:column,
            url:"/create",
            type:"POST",
            dataType:"JSON",
            success:function(res){
                loadData();
                $("#modalForm").modal("hide");
                $("#formSubmit").trigger("reset");
                $("#btnSubmit").text("save");
            }
        })
    })
} );
function loadData(){
    $.ajax({
        url:"/get",
        type:"POST",
        dataType:"JSON",
        success:function(res){
            $("#users-list").html(res.res);
        }
    })
}
function deleted(id){
    const check = confirm("are you sure ?");
    if(check){
        $(`#id${id}`).html('loading ....');
        const column={id:id};
        $.ajax({
            url:"/delete",
            type:"POST",
            dataType:"JSON",
            data:column,
            success:function(res){
                loadData();
                $(`#id${id}`).html('Delete');
            }
        })
    }
}
function edit(id){
    const column={id:id};
    $(`#edit_${id}`).html('loading ....');
    $.ajax({
        url:"/edit",
        type:"POST",
        dataType:"JSON",
        data:column,
        success:function(res){
            if(res.status){
                $("#modalForm").modal("show");
                $(`#edit_${id}`).html('Edit');
                $("#id").val(res.res.id);
                $("#name").val(res.res.name);
                $("#email").val(res.res.email);
                $("#modalTitle").html("FORM EDIT");
            }
            else{
                alert("get data failed");
            }

        }
    })
}
function actionModal(param){
    $("#id").val("");
    $("#formSubmit").trigger("reset");
    $("#btnSubmit").text("save");
    $("#modalForm").modal("show");
    $("#modalTitle").html("FORM ADD");
}