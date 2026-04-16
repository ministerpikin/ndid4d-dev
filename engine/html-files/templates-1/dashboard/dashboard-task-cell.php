
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tasks fa-fw"></i> Task List
                            <div class="pull-right">
								<select class="select-filter" ajax-request="true" data-action="dashboard" data-todo="" id="dashboard-task-filter">
									<option value="get_pending_task">PENDING TASKS</option>
									<option value="get_completed_task">COMPLETED TASKS</option>
									<option value="get_all_task">ALL TASKS</option>
								</select>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body" id="dashboard-task">
                            <?php include "dashboard-task-cell-item.php"; ?>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
					
                </div>
                <!-- /.col-lg-6 -->