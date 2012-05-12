<?php
/** flatLand! : wout
 * /wout.php : main entry point
 */

namespace Wout;

const WOUT_VERSION = '0.1';

include( __DIR__ . '/tools/singleton.php' );

include( __DIR__ . '/classes/routing/router.php' );
include( __DIR__ . '/classes/routing/route.php' );
include( __DIR__ . '/classes/routing/error_route.php' );

include( __DIR__ . '/classes/wout.php' );

static $wout;
$wout = Wout::getInstance();
