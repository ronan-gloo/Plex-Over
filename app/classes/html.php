<?php


/**
 * Add ,some functionnalities to the Bootstrap\Html class package.
 * Particulary: rating system, audio player, video player and thumbs markups.
 * 
 * @extends Bootstrap\Html
 */
class Html extends Bootstrap\Html {
	
	/**
	 * thumb function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $item
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public static function thumb($item, array $attrs = array())
	{
		// Build the streaming uri
		$url = To::thumb($item, $attrs);
		
		$attrs['alt'] = isset($item->title) ? $item->title : $item->name;
		
		return Html::img($url, $attrs);
	}
	
	
	/**
	 * Genrates a rating css element.
	 * 
	 * @access public
	 * @static
	 * @param mixed $value: value of the curent rating
	 * @param int $items (default: 5): number of labels to display
	 * @param int $value (default: 10): value ratio rating
	 * @return void
	 */
	public static function rating($value, $items = 5, $max = 10)
	{
		$perc		= Num::to_percent($value, $max);
		$stars	= '';
		
		for ($i = 1; $i <= $items; $i++)
		{
			$class  = (Num::to_percent($i, $items) < $perc) ? ' star-full' : '';
			$stars .= html_tag('span', array('class' => 'star'.$class), '');
		}
		
		return html_tag('div', array('class' => 'rating'), $stars);
	}
	
	
	/**
	 * Generates the audio player in 'bootstrap' style.
	 * @access public
	 * @static
	 * @param mixed $link
	 * @param mixed $title
	 * @return void
	 */
	public static function audio_player($title, array $attrs = array())
	{
		$html  = '<div class="control">';
		$html .= static::button('#', '', array('icon' => 'play'));
		$html .= '</div>';
		$html .= '<div class="timeline">';
		$html .= static::button('#', '0:00');
		$html .= '</div>';
		$html .= '<div class="title"><span>'.$title.'</span><a></a></div>';
		$html .= '<div class="progress">';
		$html .= '<div class="bar play"></div>';
		$html .= '<div class="bar load"></div>';
		$html .= '</div>';
		
		if (empty($attrs['class']))
		{
			$attrs['class'] = 'audio';
		}
		else
		{
			$attrs['class'] = 'audio '.$attrs['class'];
		}
		
		return html_tag('div', $attrs, $html);
	}
	
	/**
	 * Build video element with poster, subtitles and extra css classes
	 * 
	 * @access public
	 * @static
	 * @param Model_Media_Part $part
	 * @param array $attrs (default: array())
	 * @return void
	 */
	public static function video(Model_Media_Part $part, $attrs = array())
	{
		$attrs['width']		= 700;
		$attrs['height']	= round(700 / $part->media->display_aspect_ratio, 2);
				
		// set thevideo player attributes
		$attrs = array(
			'class' 			=> 'video-js vjs-default-skin',
			'id'					=> 'video_'.$part->id,
			'preload'			=> 'none',
			'controls'		=> 'controls',
			'data-setup'	=> json_encode(array())
		) + $attrs;
		
		if (! isset($attrs['poster']))
		{
			$attrs['poster'] = To::art($part->media, array('width' => $attrs['width']));
		}
		// set the video source
		$content = html_tag('source', array('src' => To::stream($part, 'video'), 'type'	=> 'video/mp4'));
		
		if ($sub = $part->subtitle())
		{
			// Add class for subtitles color
			$attrs['class'] .= ' sub-'.Config::get('main.subtitles_color');
			$lcode = Config::get('language'); //see http://videojs.com/docs/tracks
			$content .= html_tag('track', array(
				'kind'		=> 'subtitles',
				'src'			=> To::subtitle(base64_encode($sub)),
				'srclang'	=> $lcode,
				'label'		=> __('app.lang.'.$lcode) ?: __('app.on'),
			));
		}
		return Html_tag('video', $attrs, $content);
	}
	
}