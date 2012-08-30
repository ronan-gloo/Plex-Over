<?php


/**
 * Class to help to parse and / or format texts in specific contexts
 * of the application.
 * 
 */
class Text {
	
	
	/**
	 * Store Html modal for summarize() method, if we need to call it later
	 * 
	 * (default value: null)
	 * 
	 * @var mixed
	 * @access protected
	 * @static
	 */
	protected static $modal = null;
	
	/**
	 * 3 Thinks to do here:
	 * Autolink url
	 * Split the end of the summary to a modal
	 * format output
	 * 
	 * @access public
	 * @static
	 * @param mixed $srt: string source
	 * @param bool $readmore (default: true): add read more button and creates modal
	 * @return void
	 */
	public static function summarize($str, $readmore = true)
	{
		// Split original text
		$splited = explode("\n\n", $str);
		
		// What we xant to display in front
		$txt = nl2br(array_shift($splited));
		
		// Genrates readmore + modal
		if ($readmore === true and $splited)
		{
			// Create Modal
			static::$modal = Html::modal(array('fade' => true, 'slide' => true, 'id' => 'modal-summary'))
				->title(__('app.full_story'))
				->body(self::autolink(self::to_html(implode("\n\n", $splited))))
				->render();
			
			// Create Anchor
			$anchor = Html::anchor('#modal-summary', __('app.read_more'), array('data-toggle' => 'modal'));
			
			// Append elements
			$txt .= html_tag('p', array('class' => 'readmore'), $anchor);
			
		}
		return $txt;
	}
	
	
	/**
	 * Get the stored modal.
	 * We have to store it here to render in the main template
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function get_modal()
	{
		$modal = static::$modal;
		static::$modal = null;
		return $modal;
	}
	
	/**
	 * Implode args with a "," separator after filtering empty values.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function concat()
	{
		return implode(', ',  array_filter(func_get_args()));
	}
	
	/**
	 * Convert plain text to basic <p> and <br /> HTML.
	 *
	*/
	public static function to_html($str)
	{
		$str = preg_replace('#(<br\s*?/?>\s*?){2,}#', '</p>'."\n".'<p>', nl2br($str, true));

		return $str;
	}

	/**
	 * Autolink Urls or partials urls in Text 
	 * @access public
	 * @static
	 * @param mixed &$str
	 * @return void
	 */
	public static function autolink($str, $attrs = array('target' => '_blank'))
	{
		// replace urls
		$to_link = function($prefix, $href) use($attrs) {
			return Html::anchor($prefix.$href, $href, $attrs);
		};
		// cherche link
		$str = preg_replace_callback(
			'#(?<!href="|">)((?:https?|ftp|nntp)://[^\s<>()]+)#i',
			function ($matches) use($to_link) {return $to_link('', $matches[0]);},
			$str
		);
		// replace raw urls
		$str = preg_replace_callback(
			'#(?<!href="|">)(?<!http://|https://|ftp://|nntp://)(www\.[^\n\%\ <]+[^<\n\%\,\.\ <])(?<!\))#i',
			function ($matches) use($to_link) {return $to_link('http://', $matches[0]);},
			$str
		);
		return $str;
	}
}