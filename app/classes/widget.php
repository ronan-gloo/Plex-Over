<?php


/**
 * Factorize how widgets elements should be implemented.
 * 
 * @abstract
 */
abstract class Widget {
	
	
	/**
	 * We need this method when __toString() is called
	 * 
	 * @access public
	 * @abstract
	 * @return void
	 */
	abstract public function render();
	
	/**
	 * Render the widget on echo.
	 * 
	 * @access public
	 * @final
	 * @return void
	 */
	final public function __toString(){
		
		return $this->render();
	}
	
	
}