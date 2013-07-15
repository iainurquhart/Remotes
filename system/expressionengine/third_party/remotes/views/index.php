
<?php
		
	
	$this->table->set_heading(
			'Remote URL',
			'Local URL',
			'Short Name',
			'Last Updated',
			'',
			''
		);
	
	if($remotes)
	{
		foreach($remotes as $k => $r)
		{
			$loc_url = $act_base.AMP.'sn='.$r['file_name'].AMP.'ct='.$r['lifetime'];
			$this->table->add_row(
				$r['url'],
				$loc_url,
				$r['file_name'],
				array('data' => ($r['last_updated'] == 0) ? '--' : $r['nice_date'], 'class' => 'remote_last_updated'),
				anchor($base_url.AMP.'method=clear_remote_data'.AMP.'id='.$r['id'], lang('clear_remote'), array('class' => 'submit')),
				anchor($loc_url, 'View', array('target' => '_blank', 'class' => 'submit'))
			);
		}
	}
	else
	{
		$this->table->add_row(
			array('data' => lang('no_remotes').anchor($base_url.AMP.'method=edit_remotes', lang('manage_remotes')), 'colspan' => '6')
		);
	}
	
	echo $this->table->generate();

?>


