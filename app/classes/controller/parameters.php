<?php

class Controller_Parameters extends Controller {
	
	public function init()
	{
		Lang::load('parameters');
		$this->ui()->breadcrumb = array(array('#', __('app.parameters')));
	}
	
	public function action_index()
	{
		if ($data = Input::post())
		{
			$this->update($data);
			
		}
		
		$global = Config::get('main');
		
		$folders = File::read_dir(APPPATH.'lang', 1, array(
			'!^\.', // no hidden files / dirs
			'!^' => 'file' // no files
		));
		foreach ($folders as $folder => $nothing)
		{
			$code = rtrim($folder, '/');
			$global['languages'][$code] = __('global.'.$code) ?: $code;
		}
		
		$tabs = Html::tabs();
		$tabs->add(__('global.title'))->set(View::forge('parameters.app', $global));
		$tabs->add(__('video.title'))->set(View::forge('parameters.video', $global));
		//$tabs->add(__('server.title'))->set(View::forge('parameters.server', $global));
		
		// Flash
		$data['msg'] = ($flash = Session::get_flash('parameters'))
			? Html::alert(__('app.'.$flash), __('messages.'.$flash), array('status' => $flash, 'close' => 'fade'))
			: null;
		
		$ui = $this->ui();
		
		$data['tabs'] = $tabs->render();
		$ui->content = View::forge('parameters.index', $data);
		
		return $this->render();
	}
	
	
	/**
	 * Update configuration items.
	 * 
	 * @access protected
	 * @param mixed $data
	 * @return void
	 */
	protected function update($data)
	{
		$saved = 0;
		
		foreach ($data as $config => $params)
		{
			Config::load($config, $config);
			
			foreach ($params as $key => $val)
			{
				$fkey	= $config.'.'.$key;
				
				if ($item = Config::get($fkey) and $item != $val)
				{
					Config::set($fkey, $val);
				}
			}
			$saved += Config::save($config, $config);
		}
		
		Session::set_flash('parameters', ($saved ? 'success' : 'error'));
		Response::redirect(Uri::current());
	}
	
}