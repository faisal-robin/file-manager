@extends('layouts.master')

@section('content')
 
	
   <br>    
   <div class="card">
        <div class="card-header">
             <h4 class="float-left">File Manager</h4>
             <button  type="button"  class="float-right btn  btn-info btn-flat" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> Add New Folder</button>
        </div>
     <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
              @foreach($folder_details  as $key => $value)
                <div class="col-md-2 text-center">
                   <a href="{{url('file-manager/'.$value->id)}}"><i style="font-size: 80px;color: #edd178" class="fa fa-folder"></i></a> 
                   <p><span class="rename" data-old="{{ $value->folder_name }}" data-id="{{ $value->id }}" contenteditable="true">{{ $value->folder_name }}</span></p>
                </div>
              @endforeach
            </div>
        </div>

     <!-- /.card-body -->
    </div>
 <!-- /.card -->
	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add New Folder</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<form id="folder_form">
                    <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                    <input type="hidden" name="parent_folder" value="{{ $parent_folder_details->folder_name}}">
					<div class="modal-body">
						<input required="" type="text" class="form-control" name="folder_name" id="folder_name" placeholder="Folder Name">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary folder">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>


	<!-- Edit Modal -->
	<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalTable" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editModalTable">Rename Folder</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<form autocomplete="off" id="edit_form">
					<div id="modal_body" class="modal-body">
						
					</div>
                    @method('PUT')
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary edit_button">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(".folder").click(function (){
        $(".error_msg").html('');
        var data = new FormData($('#folder_form')[0]);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "POST",
            url: "{{url("file-manager")}}",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data, textStatus, jqXHR) {
                
            }
        }).done(function() {
            $("#success_msg").html("Data Save Successfully");
            location.reload();
        }).fail(function(data, textStatus, jqXHR) {
            var json_data = JSON.parse(data.responseText);
            $.each(json_data.errors, function(key, value){
                $("#" + key).after("<span class='error_msg' style='color: red;font-weigh: 600'>" + value + "</span>");
            });
        });
    });
	</script>

	<script type="text/javascript">
		$(".view_modal").click(function (){
        
        var id = $(this).data("id");

        $.ajax({
            method: "GET",
            url: "folder/"+id+"/edit",
            data: id,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data, textStatus, jqXHR) {
                $("#modal_body").html(data);
                $("#editModal").modal("show");
                
            }
        });
    });
	</script>

	<script type="text/javascript">

    $(document).on('blur', '.rename', function() {

        var folder_name = $(this).text();       
        var old_folder_name = $(this).data("old");
        var id = $(this).data("id");
        alert(old_folder_name);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "PUT",
            url: "file-manager/"+id,
            data: {folder_name:folder_name,old_folder_name:old_folder_name},
            cache: false,
            success: function (data, textStatus, jqXHR) {
                
            }
        }).done(function() {
            $("#success_msg").html("Data Save Successfully");
            //location.reload();
        }).fail(function(data, textStatus, jqXHR) {
            var json_data = JSON.parse(data.responseText);
            $.each(json_data.errors, function(edit_key, value){
                $("#edit_"+edit_key).after("<span class='error_msg' style='color: red;font-weigh: 600'>" + value + "</span>");
            });
        });

    });
	</script>

          <!-- /.box -->
@endsection