<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to CodeIgniter 4!</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-end">
        <button class="btn btn-success mb-2" onclick="actionModal('add')">Add User</button>
    </div>
    <div class="mt-3">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>User Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody  id="users-list"></tbody>
        </table>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="modalForm">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formSubmit">
                <input type="hidden" name="id" id="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnSubmit">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
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


</script>

</body>
</html>
