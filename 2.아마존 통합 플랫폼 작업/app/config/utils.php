<?php
class util{
	// 파폭 디버깅용
	static $firephp = NULL;
    private function getFirephp(){
    	if( !bs::debug() ) return NULL;
        if( is_null( self::$firephp ) ){
	        require_once ROOT.'external/FirePHP.class.php';
			self::$firephp = FirePHP::getInstance(true);
        }
        return self::$firephp;
    }
	function _(){
		if( self::getFirephp() && func_num_args() > 0 ) foreach( func_get_args() as $arg ) self::getFirephp()->log($arg);
	}
	function __($t0){if( self::getFirephp() ) self::getFirephp()->trace($t0);}

	// mysql 트랜잭션 자동화
	function queryCommit(){bs::queryCommit(); bs::query('setAutoCommit');}
	function queryRollback(){bs::queryRollback(); bs::query('setAutoCommit');}

	// 기존에 내려주는 형식대로 일단 사용
	function response( $code, $v=array() ){
		$rtn["value"] = $v;
		$rtn["code"] = $code;
		bs::out( json_encode($rtn) );
		bs::end();
	}

	// aws 클래스 객체 생성해서 리턴
	function getAwsObject( $filename, $className ){
	 	require_once ROOT.'external/'.$filename.'.php';
		return new $className();
	}

	// 유저에이전트 판별
	function parse_user_agent( $u_agent = NULL ){
	    if( is_NULL($u_agent) ){
	        if(isset($_SERVER['HTTP_USER_AGENT'])) $u_agent = $_SERVER['HTTP_USER_AGENT'];
	        else throw new \InvalidArgumentException('parse_user_agent requires a user agent');
	    }
	    $platform = NULL;
	    $browser  = NULL;
	    $version  = NULL;
	    $empty = array( 'platform' => $platform, 'browser' => $browser, 'version' => $version );
	    if( !$u_agent ) return NULL;//return $empty;
	    if( preg_match( '/\((.*?)\)/im', $u_agent, $parent_matches ) ){
	        preg_match_all( '/(?P<platform>BB\d+;|Android|CrOS|iPhone|iPad|Linux|Macintosh|Windows(\ Phone)?|Silk|linux-gnu|BlackBerry|PlayBook|Nintendo\ (WiiU?|3DS)|Xbox(\ One)?)
	                (?:\ [^;]*)?
	                (?:;|$)/imx', $parent_matches[1], $result, PREG_PATTERN_ORDER );
	        $priority = array( 'Android', 'Xbox One', 'Xbox' );
	        $result['platform'] = array_unique( $result['platform'] );
	        if( count( $result['platform'] ) > 1 ){
	            if( $keys = array_intersect($priority, $result['platform']) ) $platform = reset($keys);
	            else $platform = $result['platform'][0];
	        } else if( isset($result['platform'][0]) ) $platform = $result['platform'][0];
	    }
	    if( $platform == 'linux-gnu' ) $platform = 'Linux';
	    else if( $platform == 'CrOS' ) $platform = 'Chrome OS';
	    preg_match_all('%(?P<browser>Camino|Kindle(\ Fire\ Build)?|Firefox|Iceweasel|Safari|MSIE|Trident/.*rv|AppleWebKit|Chrome|IEMobile|Opera|OPR|Silk|Lynx|Midori|Version|Wget|curl|NintendoBrowser|PLAYSTATION\ (\d|Vita)+)
	            (?:\)?;?)
	            (?:(?:[:/ ])(?P<version>[0-9A-Z.]+)|/(?:[A-Z]*))%ix', $u_agent, $result, PREG_PATTERN_ORDER );
	    if( !isset($result['browser'][0]) || !isset($result['version'][0]) ) return NULL;//return $empty;
	    $browser = $result['browser'][0];
	    $version = $result['version'][0];
	    $find = function ( $search, &$key ) use ( $result ){
	        $xkey = array_search( strtolower($search), array_map( 'strtolower', $result['browser'] ) );
	        if( $xkey !== FALSE ){ $key = $xkey; return TRUE;}
	        return FALSE;
	    }; $key = 0;
	    if( $browser == 'Iceweasel' ) $browser = 'Firefox';
	    else if( $find('Playstation Vita', $key) ){
	        $platform = 'PlayStation Vita';
	        $browser  = 'Browser';
	    }else if( $find('Kindle Fire Build', $key) || $find('Silk', $key) ){
	        $browser  = $result['browser'][$key] == 'Silk' ? 'Silk' : 'Kindle';
	        $platform = 'Kindle Fire';
	        if( !( $version = $result['version'][$key]) || !is_numeric($version[0]) ){
	            $version = $result['version'][array_search( 'Version', $result['browser'] )];
	        }
	    }else if( $find('NintendoBrowser', $key) || $platform == 'Nintendo 3DS' ){
	        $browser = 'NintendoBrowser';
	        $version = $result['version'][$key];
	    }else if( $find('Kindle', $key) ){
	        $browser  = $result['browser'][$key];
	        $platform = 'Kindle';
	        $version  = $result['version'][$key];
	    }else if( $find('OPR', $key) ){
	        $browser = 'Opera Next';
	        $version = $result['version'][$key];
	    }else if( $find('Opera', $key) ){
	        $browser = 'Opera';
	        $find('Version', $key);
	        $version = $result['version'][$key];
	    }else if( $find('Midori', $key) ){
	        $browser = 'Midori';
	        $version = $result['version'][$key];
	    }else if( $find('Chrome', $key) ){
	        $browser = 'Chrome';
	        $version = $result['version'][$key];
	    }else if( $browser == 'AppleWebKit' ){
	        if( ($platform == 'Android' && !($key = 0)) ) $browser = 'Android Browser';
	        else if( strpos($platform, 'BB') === 0 ){
	            $browser  = 'BlackBerry Browser';
	            $platform = 'BlackBerry';
	        }else if( $platform == 'BlackBerry' || $platform == 'PlayBook' ) $browser = 'BlackBerry Browser';
	        else if( $find('Safari', $key) ) $browser = 'Safari';
	        $find( 'Version', $key );
	        $version = $result['version'][$key];
	    }else if( $browser == 'MSIE' || strpos( $browser, 'Trident' ) !== FALSE ){
	        if( $find( 'IEMobile', $key ) ) $browser = 'IEMobile';
	        else{$browser = 'MSIE'; $key = 0;}
	        $version = $result['version'][$key];
	    }else if( $key = preg_grep('/playstation \d/i', array_map('strtolower', $result['browser'])) ){
	        $key = reset($key);
	        $platform = 'PlayStation '.preg_replace('/[^\d]/i', '', $key);
	        $browser  = 'NetFront';
	    }

	    $data = array( 'platform' => $platform, 'browser' => $browser, 'version' => $version );
	    $platforms = array(
		    'desktop' => array( 'Windows', 'Linux', 'Macintosh', 'Chrome OS' ),
		    'ios' => array( 'iPhone', 'iPad' ),
			'and' => array( 'Android' )
		);
		$device = NULL;
		foreach( $platforms as $k=>$v ){
		    foreach( $v as $pl ){
		    	if( $pl == $data['platform'] ){
		    		$device = $k;
		    		break;
	    		}
		    }
		}
		return $device;
	}
}
?>
