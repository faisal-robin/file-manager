@extends('layouts.master')

@section('content')
 
	
   <br>    
	 <div class="card">
              <div class="card-header">
              	<h4 class="float-left">extension List</h4>
                <button  type="button"  class="float-right btn  btn-info btn-flat" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> Add</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">

                <table id="example1" class="table table-bordered table-striped">
                  <thead>
					<tr>
						<th>Sl</th>
						<th>extension</th>
						<th>Action</th>
					</tr>
				  </thead>
                  <tbody>
					@foreach($extension_list  as $key => $value)
					<tr>
						<td>{{$key + 1}}</td>
						<td>{{$value->extension_name}}</td>
						<td>

                         	<button  data-id="{{$value->id}}" type="button"  class="float-left btn  btn-info btn-flat mr-2 view_modal" >Edit</button>

							<form method="post" action="{{url('extension/'.$value->id)}}">
								@csrf
								@method('DELETE')
								<button type="submit" class="float-left btn btn-danger btn-flat">Delete</button>
							</form>
						</td>
					</tr>
					@endforeach
				  </tbody>

				</table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add  extension</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<form id="extension_form">
					<div class="modal-body">
						<input required="" type="text" class="form-control" name="extension" id="extension" placeholder="extension Name">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary extension">Save</button>
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
					<h5 class="modal-title" id="editModalTable">Edit extension</h5>
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
		$(".extension").click(function (){
        $(".error_msg").html('');
        var data = new FormData($('#extension_form')[0]);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "POST",
            url: "extension",
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
            url: "extension/"+id+"/edit",
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
		$(".edit_button").click(function (){
			//alert('sdfas');
        $(".error_msg").html('');

        var data = new FormData($('#edit_form')[0]);
        
        var id = $('[name=id]').val();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "POST",
            url: "extension/"+id,
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
            $.each(json_data.errors, function(edit_key, value){
                $("#edit_"+edit_key).after("<span class='error_msg' style='color: red;font-weigh: 600'>" + value + "</span>");
            });
        });
    });
	</script>

          <!-- /.box -->
@endsection