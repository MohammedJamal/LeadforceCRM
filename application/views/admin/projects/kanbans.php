<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
	#kan-ban .kan-ban-col {
		/* padding-right: 10px; */
	}
	#kan-ban .panel-body{
		margin-top: 0px;
	}
	#kan-ban-tab {
		height: 600px;
		background: transparent;
	}
	.kan-ban-content {
		background: transparent;
	}
</style>
<div id="wrapper">
	<div class="content">
			<div class="panel_s">
				<div class="panel-body" style="padding-top:10px;padding-bottom:10px">

					<div class="row">
						<div class="col-md-3">
							<div class="_buttons">
								<?php if (has_permission('projects', '', 'create')) { ?>
									<a href="<?php echo admin_url('projects/project'); ?>" class="btn btn-info pull-left display-block mright5">
										<?php echo _l('new_project'); ?>
									</a>
								<?php }
								if (isset($_SESSION['pipelines'])) {
									$pid = $_SESSION['pipelines'];
								} else {
									$pid = $pipelines[0]['id'];
								}
								if (isset($_SESSION['member'])) {
									$mem = $_SESSION['member'];
								} else {
									$mem = get_staff_user_id();
								}
								if (isset($_SESSION['gsearch'])) {
									$gsearch = $_SESSION['gsearch'];
								} else {
									$gsearch = '';
								}
								//  $list_url = admin_url('projects/index_list?pipelines=&member=&gsearch=');
								//  $kanban_onscroll_url = admin_url('projects/kanban_noscroll?pipelines='.$pid.'&member='.$mem.'&gsearch='.$gsearch);
								// //  $kanban_url = admin_url('projects/kanbans?pipelines='.$pid.'&member='.$mem.'&gsearch='.$gsearch);
								//  $forecast_url = admin_url('projects/kanbans_forecast?pipelines='.$pid.'&member='.$mem.'&gsearch='.$gsearch);
								//  $approval_url = admin_url('projects/index_list?approvalList=1&pipelines=&member=&gsearch=');
								//  if(!is_admin(get_staff_user_id())) {
								// 	//$list_url = admin_url('projects/index_list?pipelines='.$pipelines[0]['id'].'&member='.get_staff_user_id().'&gsearch=');
								// 	$list_url = admin_url('projects/index_list?pipelines=&member='.get_staff_user_id().'&gsearch=');
								// 	$kanban_onscroll_url = admin_url('projects/kanban_noscroll?pipelines='.$pid.'&member='.$mem.'&gsearch='.$gsearch);
								// 	// $kanban_url = admin_url('projects/kanbans?pipelines='.$pid.'&member='.$mem.'&gsearch='.$gsearch);
								// 	$forecast_url = admin_url('projects/kanbans_forecast?pipelines='.$pid.'&member='.$mem.'&gsearch='.$gsearch);
								// } 
								$list_url = admin_url('projects/index_list?pipelines=&gsearch=');
								foreach ($mem as $member) {
									$list_url .= '&member[]=' . $member;
								}

								$kanban_onscroll_url = admin_url('projects/kanban_noscroll?pipelines=' . $pid . '&gsearch=' . $gsearch);
								foreach ($mem as $member) {
									$kanban_onscroll_url .= '&member[]=' . $member;
								}

								$forecast_url = admin_url('projects/kanbans_forecast?pipelines=' . $pipelines[0]['id'] . '&gsearch=' . $gsearch);
								foreach ($mem as $member) {
									$forecast_url .= '&member[]=' . $member;
								}

								$approval_url = admin_url('projects/index_list?approvalList=1&pipelines=&gsearch=');
								foreach ($mem as $member) {
									$approval_url .= '&member[]=' . $member;
								}
								if (!is_admin(get_staff_user_id())) {
									//$list_url = admin_url('projects/index_list?pipelines='.$pipelines[0]['id'].'&member='.get_staff_user_id().'&gsearch=');
									$list_url = admin_url('projects/index_list?pipelines=&member=' . get_staff_user_id() . '&gsearch=');
									$kanban_onscroll_url = admin_url('projects/kanban_noscroll?pipelines=' . $pid . '&member=' . $mem . '&gsearch=' . $gsearch);
									// $kanban_url = admin_url('projects/kanbans?pipelines='.$pid.'&member='.$mem.'&gsearch='.$gsearch);
									$forecast_url = admin_url('projects/kanbans_forecast?pipelines=' . $pid . '&member=' . $mem . '&gsearch=' . $gsearch);
								}

								?>
								<a href="<?php echo $list_url; ?>" data-toggle="tooltip" title="<?php echo _l('projects'); ?>" class="btn btn-default"><i class="fa fa-list" aria-hidden="true"></i></a>
								<!-- <a href="<?php echo admin_url('projects/gantt?pipelines=' . $pipelines[0]['id'] . '&member=&gsearch='); ?>" data-toggle="tooltip" title="<?php echo _l('project_gant'); ?>" class="btn btn-default"><i class="fa fa-align-left" aria-hidden="true"></i></a> -->
								<a href="<?php echo $kanban_onscroll_url; ?>" data-toggle="tooltip" title="<?php echo _l('leads_switch_to_kanban_noscroll'); ?>" class="btn btn-primary"><i class="fa fa-th" aria-hidden="true"></i></a>
								<!-- <a href="<?php echo $kanban_url; ?>" data-toggle="tooltip" title="<?php echo _l('leads_switch_to_kanban'); ?>" class="btn btn-default"><i class="fa fa-th-large" aria-hidden="true"></i></a> -->
								<a href="<?php echo $forecast_url; ?>" data-toggle="tooltip" title="<?php echo _l('leads_switch_to_forecast'); ?>" class="btn btn-default"><i class="fa fa-line-chart" aria-hidden="true"></i></a>
								<a href="<?php echo $approval_url; ?>" data-toggle="tooltip" title="<?php echo _l('deal_approval_list'); ?>" class="btn <?php echo isset($_GET['approvalList']) ? 'btn-primary' : 'btn-default' ?> "><i class="fa fa-check-square-o" aria-hidden="true"></i></a>
							</div>
						</div>
						<div class="col-md-1">
							<h4><?php echo _l('filter_by'); ?></h4>
						</div>
						<?php echo form_open(admin_url('projects/kanban_noscroll'), array('method' => 'get', 'id' => 'ganttFiltersForm')); ?>
						<div class="col-md-2 pipeselect">
							<select class="selectpicker" data-none-selected-text="<?php echo _l('all'); ?>" name="pipelines" id="pipeline_id" data-width="100%">
								<?php foreach ($pipelines as $status) {
								?>
									<option value="<?php echo $status['id']; ?>" <?php if ($selected_statuses == $status['id']) {
																						echo ' selected';
																					} ?>>
										<?php echo $status['name']; ?>
									</option>
								<?php } ?>
							</select>
						</div>
						<?php
						/**
						 * Only show this filter if user has permission for projects view otherwise
						 * wont need this becuase by default this filter will be applied
						 */
						$mandatory_fields = '';
						$fields = get_option('deal_fields');
						$need_fields = array();
						if (!empty($fields) && $fields != 'null') {
							$need_fields = json_decode($fields);
						}
						if (has_permission('projects', '', 'view') /*&& !empty($need_fields) && in_array("members", $need_fields)*/) { ?>
							<div class="col-md-2">
								<?php if (!$selectedMember) {
									$selectedMember = array();
								} ?>
								<select multiple class="selectpicker" data-live-search="true" data-title="All Members" name="member[]" id="staff_id" data-width="100%">
									<?php foreach ($project_members as $member) { ?>
										<option value="<?php echo $member['staff_id']; ?>" <?php if (in_array($member['staff_id'], $selectedMember)) {
																								echo 'selected';
																							} ?>>
											<?php echo $member['firstname'] . ' ' . $member['lastname']; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						<?php } ?>
						<div class="col-md-1">
							<button type="submit" class="btn btn-default"><?php echo _l('apply'); ?></button>
						</div>
						<?php echo form_close(); ?>
					</div>

				</div>
			</div>
			<div class="kan-ban-tab" id="kan-ban-tab" style="padding:0px 16px;">
				<div class="row">
					<div id="kanban-params">
						<?php echo form_hidden('project_id', $this->input->get('project_id')); ?>
					</div>
					<div class=" projects-kan-ban">
						<div id="kan-ban"></div>
					</div>
				</div>
			</div>


			<div class="modal" id="newDealModal" style="display: none; z-index: 1050;">
				<div class="modal-dialog">
					<div class="modal-header">
						<span class="title">New Deal</span>
						<button type="button" class="close" data-dismiss="modal">×</button>
					</div>
					<div class="modal-content">
						<div class="modal-body" id="newDealModalContent">
						</div>
					</div>
				</div>
			</div>


		<?php init_tail(); ?>
		<script>
			$(function() {
				project_kanban();
			});

			// Edit status function which init the data to the modal
			function edit_status(invoker, id) {
				$('#additional').append(hidden_input('id', id));
				$('#status input[name="name"]').val($(invoker).data('name'));
				$('#status .colorpicker-input').colorpicker('setValue', $(invoker).data('color'));
				$('#status input[name="statusorder"]').val($(invoker).data('order'));
				$('#status').modal('show');
				$('.add-title').addClass('hide');
			}
		</script>
		<script>
			var maxHeight = function(elems){
				return Math.max.apply(null, elems.map(function ()
				{
					return $(this).height();
				}).get());
			}
			$(function() {
				if ($('#status').length > 0) {
					$('.form_status .selectpicker').addClass("formstatus");
				}
				if ($('.form_assigned .selectpicker').length > 0) {
					$('.form_assigned .selectpicker').addClass("formassigned");
				}

				if ($('#teamleader').length > 0) {
					$('.form_teamleader .selectpicker').addClass("formteamleader");
				}

				$('#pipeline_id').change(function() {
					$('.formstatus').selectpicker('destroy');
					$('.formstatus').html('').selectpicker('refresh');

					$('.formassigned').selectpicker('destroy');
					$('.formassigned').html('').selectpicker('refresh');

					$('.formteamleader').selectpicker('destroy');
					$('.formteamleader').html('').selectpicker('refresh');

					var pipeline_id = $('#pipeline_id').val();
					if (pipeline_id) {
						$.ajax({
							url: admin_url + 'leads/changepipeline',
							type: 'POST',
							data: {
								'pipeline_id': pipeline_id
							},
							dataType: 'json',
							success: function success(result) {
								$('.formstatus').selectpicker('destroy');
								$('.formstatus').html(result.statuses).selectpicker('refresh');


								$('.formassigned').selectpicker('destroy');
								$('.formassigned').html(result.teammembers).selectpicker('refresh');

								$('.formteamleader').selectpicker('destroy');
								$('.formteamleader').html(result.teamleaders).selectpicker('refresh');

							}
						});
					} else {
						$.ajax({
							url: admin_url + 'leads/selectAllpipeline',
							type: 'POST',
							data: {
								'pipeline_id': ''
							},
							dataType: 'json',
							success: function success(result) {

								$('.formassigned').selectpicker('destroy');
								$('.formassigned').html(result.teammembers).selectpicker('refresh');

							}
						});
					}
				});
				var pipelines_count = <?php echo count((array)$pipelines); ?>;
				if (pipelines_count == 1) {
					$('#pipeline_id option[value="<?php echo $pipelines[0]['id']; ?>"]').attr('selected', 'selected')
					$('#pipeline_id').selectpicker('refresh');
					$('#pipeline_id').trigger('change');
				}


			});
			$(function() {
				// var stickyHeaderTop = $('#kan-ban .panel-heading-bg').offset().bottom;

				// $(window).scroll(function(){
				//         if( $(window).scrollTop() > stickyHeaderTop ) {
				// 				$('#kan-ban .panel-heading-bg').css({position: 'fixed', padding: '9px', width: '15.6%', 'z-index': '9999'});
				// 				$('.kan-ban-content').css({padding: '41px 2px 2px 2px'});
				//         } else {
				// 				$('#kan-ban .panel-heading-bg').css({position: 'relative', width: 'auto'});
				// 				$('.kan-ban-content').css({padding: '2px'});
				//         }
				// });
				// var fixmeTop = $('#kan-ban .panel-heading-bg').offset().top;
				// $(window).scroll(function() {
				// 	var currentScroll = $(window).scrollTop();
				// 	console.log(currentScroll);
				// 	if(currentScroll > 100) {
				// 		$('#kan-ban .panel-heading-bg').css({position: 'fixed', padding: '9px', width: '15.6%', 'z-index': '9999',top:0});
				// 				$('.kan-ban-content').css({padding: '41px 2px 2px 2px'});
				// 	}

				// 	if(currentScroll < 100) {
				// 		$('#kan-ban .panel-heading-bg').css({position: 'relative', width: 'auto', top: 'auto'});
				// 				$('.kan-ban-content').css({padding: '2px'});
				// 	}
				// });
			});

			$(document).ready(function() {
				var busy = false;
				var limit = <?php echo get_option('projects_kanban_limit'); ?>;
				var offset = 0;

				function displayRecords(lim, off) {
					var url = admin_url + 'projects/kanban_more_load';
					$.ajax({
						type: "GET",
						async: false,
						url: url,
						data: "limit=" + lim + "&offset=" + off,
						cache: false,
						beforeSend: function() {
							$("#loader_message").html("").hide();
							$('#loader_image').show();
						},
						success: function(html) {
							var obj = JSON.parse(html);
							<?php foreach ($statuses as $status) { ?>
								$("#status_<?php echo $status['id']; ?>").append(obj.status_<?php echo $status['id']; ?>);
							<?php } ?>
							//$("#results").append(html);
							//$('#loader_image').hide();
							if (html == "") {
								// $("#loader_message").html('<button data-atr="nodata" class="btn btn-default" type="button">No more records.</button>').show()
							} else {
								//$("#loader_message").html('<button class="btn btn-default" type="button">Loading please wait...</button>').show();
							}
						}
					});
				}
				/*$(window).scroll(function() {
				          // make sure u give the container id of the data to be loaded in.
				          if ($(window).scrollTop() + $(window).height() > $("#kan-ban").height() && !busy) {
				            busy = true;
				            offset = limit + offset;
				            displayRecords(limit, offset);
				          }
				});*/
				$('#kan-ban-tab').scroll(function() {
					// make sure u give the container id of the data to be loaded in.
					// if ($(window).scrollTop() + $(window).height() > $("#results").height() ) {
					busy = true;
					offset = limit + offset;
					displayRecords(limit, offset);
					//  }

				})
			});
		</script>
		</body>

		</html>

		<style>
			.kan-ban-col {
				width: calc(100%/<?php echo count($statuses); ?>);
			}

			ul.dropdown-menu li:first-child {
				display: block !important;
			}
		</style>
		<style>
			.deal-kanban-call-btn{
				color: green !important;
			}
			.text-bold{
				font-weight: 500;
			}
			.kanban-stage-deal-count{
				padding: 5px;
				background-color: #eaeef1;
				border-radius: 5px;
				font-weight: 500;
			}
			.panel-heading-bg .heading.pointer {
				white-space: nowrap;
				overflow: hidden;
				text-overflow: ellipsis;
				display: block;
				float: left;
				width: 100%;
				font-weight: 500;
				margin-bottom: 0px;
			}

			.panel-heading-bg .pointer {
				float: left;
			}

			.panel-heading-bg.primary-bg {
				height: 39px;
			}

			.projects-kan-ban {
				background-color: transparent;
				min-height: 500px;
			}

			#kan-ban .panel-heading-bg {
				position: absolute;
				padding: 9px;
				width: calc((100%/<?php echo count($statuses); ?>) - 10px);
				z-index: 2;
				background-color: aliceblue;
			}

			.kan-ban-content {
				position: relative;
				padding:70px 2px 2px 2px;
			}

			.projects-kan-ban {
				height: 100%;
			}
			#kan-ban{
				display: flex;
			}
			.kan-ban-col-wrapper, .kan-ban-col-wrapper >.panel_s, .kan-ban-content-wrapper,.kan-ban-content,.projects-status{
				height: 100%;
			}

			li.project-kan-ban:last-child {
				background-color: #FFFFFF;
				position: sticky;
				top: 127px;
			}
		</style>