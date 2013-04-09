<?php if ($this->method == 'edit'): ?>
	<section class="title">
    	<h4><?php echo sprintf(lang('users:groups:edit_title'), $group->name) ?></h4>
	</section>
<?php else: ?>
	<section class="title">
    	<h4><?php echo lang('users:groups:add_title') ?></h4>
	</section>
<?php endif ?>

<?php echo form_open() ?>

<section class="item">
	<div class="content">

		<div class="form_inputs">

		    <ul>
				<li>
					<label for="description"><?php echo lang('users:groups:name') ?> <span>*</span></label>
					<div class="input"><?php echo form_input('description', $group->description) ?></div>
				</li>

				<li class="even">
					<label for="name"><?php echo lang('users:groups:short_name') ?> <span>*</span></label>

					<div class="input">

					<?php if ( ! in_array($group->name, array('user', 'admin'))): ?>
					<?php echo form_input('name', $group->name) ?>

					<?php else: ?>
					<p><?php echo $group->name ?></p>
					<?php endif ?>

					</div>
				</li>
		    </ul>

		</div>

	</div>
</section>

<script type="text/javascript">
	jQuery(function($) {
		$('form input[name="description"]').keyup($.debounce(300, function(){

			var slug = $('input[name="name"]');

			$.post(SITE_URL + 'ajax/url_title', { title : $(this).val() }, function(new_slug){
				slug.val( new_slug );
			});
		}));
	});
</script>

<section class="title">
	<h4><?php echo $group->description ?></h4>
</section>
<section class="item">
	<div class="content js-permissions">
		<?php echo form_open() ?>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo form_checkbox(array('id'=>'check-all', 'name' => 'action_to_all', 'class' => 'check-all', 'title' => lang('permissions:checkbox_tooltip_action_to_all'))) ?></th>
					<th><?php echo lang('permissions:module') ?></th>
					<th><?php echo lang('permissions:roles') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($modules as $module): ?>

				<tr>
					<td style="width: 30px">
						<?php echo form_checkbox(array(
							'value' => $module['slug'],
							'name'=>'modules[]',
							'checked'=> $group->hasAccess($module['slug'].'.*'),
							'title' => sprintf(lang('permissions:checkbox_tooltip_give_access_to_module'), $module['name']),
							'class' => 'js-perm-module',
						)) ?>
					</td>
					<td>
						<label class="inline" for="<?php echo $module['slug'] ?>">
							<?php echo $module['name'] ?>
						</label>
					</td>
					<td>
					<?php if ( ! empty($module['roles'])): ?>
						<?php foreach ($module['roles'] as $role): ?>
						<label class="inline">
							<?php echo form_checkbox(array(
								'name' => 'module_roles['.$module['slug'].'][]',
								'value' => $role,
								'checked' => $group->hasAccess($module['slug'].'.'.$role),
								'class' => 'js-perm-role',
							)) ?>
							<?php echo lang($module['slug'].':role_'.$role) ?>
						</label>
						<?php endforeach ?>
					<?php endif ?>

					</td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
		
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))) ?>

	</div>
</section>

<?php echo form_close() ?>
