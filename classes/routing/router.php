<?php
/** flatLand! : wout
 * /classes/routing/router.php : main routing classe
 */

namespace Wout\Routing;

class Router extends \Wout\Tools\Singleton {

	const METHOD_GET = 'get';
	const METHOD_POST = 'post';

	public function get() {
		$this->_registerRoute( func_get_args(), array( self::METHOD_GET ) );
	} // get

	public function post() {
		$this->_registerRoute( func_get_args(), array( self::METHOD_POST ) );
	} // post

	public function map() {
		$this->_registerRoute( func_get_args(), array( self::METHOD_GET, self::METHOD_POST ) );
	} // map

	public function error() {
		$this->_registerError( func_get_args() );
	} // error

	public function ajaxGet() {
		$this->_registerRoute( func_get_args(), array( self::METHOD_GET ), true );
	} // ajaxGet

	public function ajaxPost() {
		$this->_registerRoute( func_get_args(), array( self::METHOD_POST ), true );
	} // ajaxPost

	public function ajax() {
		$this->_registerRoute( func_get_args(), array( self::METHOD_GET, self::METHOD_POST ), true );
	} // ajax

	public function ajaxError() {
		$this->_registerError( func_get_args(), true );
	} // error

	public function redirect( $sPath ) {
		header( "Location: " . $sPath );
		exit;
	} // redirect

	public function run() {
		$this->_sCurrentURI = isset( $_SERVER[ 'REDIRECT_URL' ] ) ? $_SERVER[ 'REDIRECT_URL' ] : $_SERVER[ 'REQUEST_URI' ];
		$bHasMatched = false;
		foreach( $this->_aRegisteredRoutes as $oRoute ) {
			if( $oRoute->match( $this->_sCurrentURI ) ) {
				$bHasMatched = true;
				$oRoute->exec();
				break;
			}
		}
		if( !$bHasMatched )
			$this->callError( 404 );
	} // run

	public function callError( $iCode ) {
		$bHasMatched = false;
		foreach( $this->_aRegisteredErrorRoutes as $oErrorRoute ) {
			if( $oErrorRoute->match( $iCode ) ) {
				$bHasMatched = true;
				call_user_func_array( array( $oErrorRoute, 'exec' ) , array_slice( func_get_args(), 1 ) );
				break;
			}
		}
		if( !$bHasMatched )
			$this->_defaultErrorRouteHandler( 404 );
		die();
	} // callError

	public function callErrorOn( $bAssertion, $iCode ) {
		if( !$bAssertion ) return;
		call_user_func_array( array( $this, 'callError' ), array_slice( func_get_args(), 1 ) );
	} // callErrorOn

	private function _registerRoute( $aParams, $aMethods, $bIsAJAX = false ) {
		$aCallbacks = $aParams;
		$sPattern = array_shift( $aCallbacks );
		$this->_aRegisteredRoutes[] = new \Wout\Routing\Route( $sPattern, $aMethods, $aCallbacks, $bIsAJAX );
	} // _registerRoute

	private function _registerError( $aParams, $bIsAJAX = false ) {
		$aCallbacks = $aParams;
		$iErrorCode = array_shift( $aCallbacks );
		$this->_aRegisteredErrorRoutes[] = new \Wout\Routing\ErrorRoute( $iErrorCode, $aCallbacks, $bIsAJAX );
	} // _registerError

	private function _defaultErrorRouteHandler( $iCode ) {
		switch( $iCode ) {
			case 400: $sCodeDetails = 'Bad Request'; break;
			case 401: $sCodeDetails = 'Unauthorized'; break;
			case 403: $sCodeDetails = 'Forbidden'; break;
			case 404: $sCodeDetails = 'Not Found'; break;
			case 405: $sCodeDetails = 'Method Not Allowed'; break;
			case 406: $sCodeDetails = 'Not Acceptable'; break;
			case 408: $sCodeDetails = 'Request Timeout'; break;
			case 409: $sCodeDetails = 'Conflict'; break;
			case 410: $sCodeDetails = 'Gone'; break;
			case 418: $sCodeDetails = 'I\'m a teapot'; break;
			case 420: $sCodeDetails = 'Enhance Your Calm'; break;
			case 429: $sCodeDetails = 'Bad Request'; break;
		}
		return header( "HTTP/1.0 " . $iCode . ' ' . $sCodeDetails );
	} // _defaultErrorRouteHandler

	private $_sCurrentURI;
	private $_aRegisteredRoutes = array();
	private $_aRegisteredErrorRoutes = array();

} // class::Router
