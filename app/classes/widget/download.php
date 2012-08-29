<?php


/**
 * Create Download buttons.
 * If there is more than 1 Part for the media,
 * we create a dropdown button.
 * Size info for parts are displayed
 * 
 * @extends Widget
 */
class Widget_Download extends Widget {
	
	
	/**
	 * Store Model_Metadata wheren forge() is called
	 * 
	 * (default value: null)
	 * 
	 * @var mixed
	 * @access protected
	 * @static
	 */
	protected static $metadata = null;
	
	
	/**
	 * @access public
	 * @static
	 * @param Model_Metadata $model
	 * @return void
	 */
	public static function forge(Model_Metadata $model)
	{
		static::$metadata = $model;
		
		return new self();
	}
	
	
	/**
	 * Generates the dropdown content
	 * 
	 * @access protected
	 * @return void
	 */
	protected function dropdown_list()
	{
		$items = array();
		$i = 1;
		
		foreach (static::$metadata->media->parts as $part)
		{
			$text = __('app.download').' '.__('app.part').' '.$i.' '.html_tag('small', array(), Num::format_bytes($part->size));
			$items[] = array(To::download($part), $text, array('icon' => 'download'));
			$i++;
		}
		return $items;
	}
	
	
	/**
	 * Render a single button, or a dropdown for multipart items.
	 * 
	 * @access public
	 * @return void
	 */
	public function render()
	{
		$output = null;
		
		if (count(static::$metadata->media->parts) > 1)
		{
			$output = Html::dropdown_button( __('app.download'), $this->dropdown_list(), array(
				'split' => true,
				'size' => 'small'
			));
		}
		elseif (count(static::$metadata->media->parts) === 1)
		{
			$text		= __('app.download').' '.html_tag('small', array(), Num::format_bytes(static::$metadata->media->size));
			$output	= Html::button(To::download(reset(static::$metadata->media->parts)), $text, array(
				'class'=> 'btn-group',
				'icon' => 'download',
				'size' => 'small'
			));
		}
		return $output;
	}
	
}