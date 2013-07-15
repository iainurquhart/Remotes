<script type='text/javascript'>
	
	// Return a helper with preserved width of cells
	var fixHelper = function(e, ui) {
	    ui.children().each(function() {
	        $(this).width($(this).width());
	    });
	    return ui;
	};
	
	$(document).ready(function() {
		
		var $container = $(".roland_table tbody").roland();
		var opts = $.extend({}, $.fn.roland.defaults);
		
		$(".roland_table tbody").sortable({
			helper: fixHelper, // fix widths
			handle: '.roland_drag_handle',
			cursor: 'move',
			update: function(event, ui) { 
				$.fn.roland.updateIndexes($container, opts); 
			}
		});

	});
</script>

<?php
	
	echo form_open($_form_base_url.AMP.'method=save_remotes');
	
	$this->table->set_template($roland_template);
	
	$this->table->set_heading(
			array('data' => '', 'style' => 'width: 10px;'),
			'Remote URL to Cache',
			'Short Name',
			'Refresh Time in Seconds',
			array('data' => '', 'style' => 'width: 47px;')
		);
	
	if($remotes)
	{
		$i = 0;
		foreach($remotes as $k => $r)
		{
			$this->table->add_row(
				array('data' => $drag_handle, 'class' => 'roland_drag_handle'),
				form_input("urls[$i]", $r['url']),
				form_input("cache_file_names[$i]", $r['file_name']).
				form_hidden("ids[$i]", $r['id']),
				form_input("lifetime[$i]", $r['lifetime']),
				array('data' => $nav, 'class' => 'roland_nav')
			);
			$i++;		
		}
	}
	else
	{
		$this->table->add_row(
			array('data' => $drag_handle, 'class' => 'roland_drag_handle'),
			form_input('urls[0]', ''),
			form_input('cache_file_names[0]', '').
			form_hidden("order[0]", ''),
			form_input("lifetime[0]", ''),
			array('data' => $nav, 'class' => 'roland_nav')
		);
	}
	
	echo $this->table->generate();
	
	echo form_submit('submit', 'Update Remotes', 'class="submit"');
	
	echo form_close();
	
?>


