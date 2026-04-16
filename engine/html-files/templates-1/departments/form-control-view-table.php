<style type="text/css">
			.form-control-table table thead tr th,
			.form-control-table table{
				font-size:0.9em;
			}
			.form-control-table table tr th{
				background-color:#CCFFD2;/*#f4ccFF*/
				font-weight:bold;
			}
			
		</style>
<table id="work-history-view-table" class="table table-striped table-bordered table-hover">
<thead>
	<tr>
		<th>S/N</th>
		<th>Previous Name</th>
		<th>New Name</th>
		<th>Supporting Document</th>
		<th>Status</th>
		<th> </th>
	</tr>
</thead>
<tbody id="form-control-table-change_of_name">
<?php
	if( isset( $data["change_of_name"] ) && is_array( $data["change_of_name"] ) && $data["change_of_name"] )
		include "form-control-view-row.php";
?>
</tbody>
</table>