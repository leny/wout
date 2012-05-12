<?php
/** flatLand! : wout
 * /classes/wout.php : main class
 */

namespace Wout;

class Wout extends Tools\Singleton {

	public function __get( $sName ) {
		switch( $sName ) {
			case 'version':
				return \Wout\WOUT_VERSION;
				break;
			case 'route':
				return $this->_oRoute;
				break;
			default:
				throw new \InvalidArgumentException( 'Wout: there is no property called "' . $sName . '" !' );
				break;
		}
	} // __get

	public function __call( $sName, $aArguments ) {
		switch( $sName ) {
			// ROUTING shortcuts
			case 'run':
			case 'post':
			case 'get':
			case 'map':
			case 'error':
			case 'redirect':
			case 'callError':
			case 'callErrorOn':
				call_user_func_array( array( $this->route, $sName ), $aArguments );
				break;
			default:
				throw new \InvalidArgumentException( 'Wout: there is no method called "' . $sName . '" !' );
				break;
		}
	} // __call

	public function init( $mConfig=null, $sPathBase = null ) {
		$this->_applyConfig( $aConfig, $sPathBase );
	} // init

	private function _applyConfig( $aConfig, $sPathBase ) {
		$this->_oRouting = Routing\Router::getInstance();
	} // _applyConfig

	private $_oRouting;

} // class::Wout
