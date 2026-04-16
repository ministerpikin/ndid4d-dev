
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i> Notifications Panel
							<div class="pull-right">
								<select class="select-filter" id="dashboard-notification-filter" ajax-request="true" data-action="dashboard" data-todo="">
									<option value="get_unread_notification">UNREAD</option>
									<option value="get_read_notification">READ</option>
									<option value="get_all_notification">ALL</option>
								</select>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body" id="dashboard-notification">
							<?php include "dashboard-notification-cell-item.php"; ?>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                </div>
                <!-- /.col-lg-6 -->