<?php
use Aws\Common\Aws;
use Aws\Common\Enum\Region;
use Aws\Sns\SnsClient;
use Aws\Sqs\SqsClient;

class Controller{
	private $sns = NULL;
	private $sqs = NULL;
	private $userArnParseData = array();

	function __construct(){
		require_once ROOT.'external/aws-sdk-php-master/vendor/autoload.php';
		require_once ROOT.'config/config.php';
		require_once ROOT.'config/utils.php';
		bs::db( 'local', FALSE );
		bs::sql( 'sql', FALSE );
		bs::debug(1);
		ini_set( 'date.timezone', 'Asia/Seoul' );
		if( !SHELL_MODE ) header('Access-Control-Allow-Origin: *');
/* 		bs::table2json( 'reports', 'imageNotices', 'apps', 'parseSNS', 'remotePush' ); */
	}

	function index(){
		var_dump('INTRNET TEST');
	}

	// 테스트용 dummy SNS user data change
	function changeUserData( $nextToken = NULL ){
		$dummy = array(
			'{"ln":"ko", "sex":"1"}','{"ln":"ko", "sex":"0"}',
			'{"ln":"ja", "sex":"1"}','{"ln":"ja", "sex":"0"}',
			'{"ln":"en", "sex":"1"}','{"ln":"en", "sex":"0"}',
			'{"ln":"zh", "sex":"1"}','{"ln":"zh", "sex":"0"}',
			'{"ln":"de", "sex":"1"}','{"ln":"de", "sex":"0"}',
			'{"ln":"ru", "sex":"1"}','{"ln":"ru", "sex":"0"}',
			'{"ln":"fr", "sex":"1"}','{"ln":"fr", "sex":"0"}',
			'{"ln":"es", "sex":"1"}','{"ln":"es", "sex":"0"}'
		);
		$options = array( 'PlatformApplicationArn'=>'arn:aws:sns:us-west-1:639746878306:app/APNS/letsfoldworld' );
		if( !is_null($nextToken) ) $options['NextToken'] = $nextToken;
		$result = self::getSns()->listEndpointsByPlatformApplication($options);
		if( count( $result['Endpoints'] ) > 0 ){
			$userArnList = array();
			foreach( $result['Endpoints'] as $data ){
				self::getSns()->setEndpointAttributes(array(
					'EndpointArn'=>$data['EndpointArn'],
					'Attributes'=>array(
						'CustomUserData'=>$dummy[rand( 0, count($dummy) )]
					)
				));
			}
		}

		if( isset($result['NextToken']) ){
			sleep(1);
			self::changeUserData( $result['NextToken'] );
		} else{
			util::response( HTTP_OK, TRUE );
		}
	}

	private function getSns(){
		if( is_null( $this->sns ) ){
			$this->sns = Aws::factory( array(
	        	'key'=>config::AWS_ACCESS_KEY,
	        	'secret'=>config::AWS_SECRET_KEY,
	        	'region'=>config::AWS_REGION )
	    	)->get('sns');
    	}
        return $this->sns;
	}

	private function getSqs(){
		if( is_null( $this->sqs ) ){
			$this->sqs = Aws::factory( array(
	        	'key'=>config::AWS_ACCESS_KEY,
	        	'secret'=>config::AWS_SECRET_KEY,
	        	'region'=>config::AWS_REGION )
	    	)->get('sqs');
    	}
        return $this->sqs;
	}

	private function sendMessage($data){
		$options = array(
			'QueueUrl'=>config::AWS_SQS_PUSH_SINGLE_URL,
			'MessageBody'=>$data
		);
		try{$res = self::getSqs()->sendMessage($options);}
		catch( Exception $e ){
			if( bs::debug() ) echo $e->getMessage();
            $res = FALSE;
		}
		return $res;
	}

	private function sendMessageBatch( $data, $queueUrl ){
		$options = array(
			'QueueUrl'=>$queueUrl,
			'Entries'=>$data
		);
		try{$res = self::getSqs()->sendMessageBatch($options);}
		catch( Exception $e ){
			if( bs::debug() ) echo $e->getMessage();
            $res = FALSE;
		}
		return $res;
	}

/*
 *
 *		SNS 파싱된 파일로 푸시 보내기
 *
 *
 */
  	function savePushJobOnDB(){
		$keys = array();
		$values = array();
  		foreach( $_POST as $k=>$v ){
			array_push( $keys, $k );
			array_push( $values, "'".$v."'" );
  		}

  		$keys = implode( ',', $keys );
  		$values = implode( ',', $values);
  		$data = array( 'insert'=>$keys, 'value'=>$values );

	  	util::response( HTTP_OK, bs::query( 'savePushJobOnDB', $data ) );
  	}

  	function getPushJob(){
 		$data =  bs::query('getPushJob');
 		if($data){
	 		bs::query( 'deletePushJob', array( 'id'=>$data->id ) );
	 		unset( $data->id );

			$_POST['filename'] = $data->filename;
			if( $data->title ){$_POST['title'] = $data->title; unset( $data->title );}
			if( $data->ticker ){$_POST['ticker'] = $data->ticker; unset( $data->ticker );}
			foreach( $data as $k=>$v ) if( !is_null($v) ) $_POST[$k] = $v;

/* 			var_dump($_POST); */
			$this->remotPushAllUserByLanguage();
		}
 	}


	// 전체푸시 언어별로 발송
	function remotPushAllUserByLanguage(){
		$data = bs::get(config::SNS_ARN_LIST_URL.$_POST['filename']);
		$data = json_decode($data);

		// 언어별로 arn 구별
		$divedeArnList = array();
		foreach( $data as $v ){
			$ln = json_decode( $v->userData )->ln;
			$arn = $v->arn;
			if( !isset( $divedeArnList[$ln] ) ) $divedeArnList[$ln] = array();
			array_push( $divedeArnList[$ln], $arn);
		}

		// 디버그용
		var_dump($divedeArnList);
		mail( 'team@fivethirty.kr', 'SNS remote push 발송 완료', 'intranet : filename:'.$_POST['filename'] );
		exit;

		// 언어별 메시지를 10명씩 발송
		foreach( $divedeArnList as $k=>$v ){
			// 해당 언어에 값이없으면 default 로 en
			if( empty($_POST[$k]) ) $msg = $_POST[config::SNS_PUSH_DEFAULT_LANGUAGE];
			else $msg = $_POST[$k];
			$userArnList = NULL;
			$loopCnt = 0;

/* 			var_dump($msg); exit; // 디버그용 */

			foreach( $v as $arn ){
				$userArnList = $userArnList.$arn.'|';
				if( ++$loopCnt > 9 ){
					$_POST['userArn'] = substr( $userArnList, 0, -1 );
					$_POST['text'] = $msg;
					$userArnList = NULL;
					$loopCnt = 0;
					self::remoteMultiPush(FALSE);
					exit;	// 디버그용
				}
			}
		}
		mail( 'team@fivethirty.kr', 'SNS remote push 발송 완료', 'intranet : filename:'.$_POST['filename'] );
		util::response( HTTP_OK, TRUE );
	}

	// Deprecated: 유저 arn list 확인 - 100개가 한페이지임
	function remotPushAllUser( $nextToken = NULL ){
		$options = array( 'PlatformApplicationArn'=>$_POST['appArn'] );
		if( !is_null($nextToken) ) $options['NextToken'] = $nextToken;
		$result = self::getSns()->listEndpointsByPlatformApplication($options);

		$userArnList = NULL;
		if( count($result['Endpoints']) > 0 ){
			foreach( $result['Endpoints'] as $data ){
				if( $data['Attributes']['Enabled'] == TRUE ) $userArnList = $userArnList.$data['EndpointArn'].'|';
			}
			if($userArnList){
				$_POST['userArn'] = substr( $userArnList, 0, -1 );
				self::remoteMultiPush(FALSE);
			}
		} else util::response( HTTP_OK, FALSE );

		// next page
		if( isset($result['NextToken']) ){
			sleep(1);
			self::remotPushAllUser( $result['NextToken'] );
		}
	}

	// sns 푸시 등록
	function createPlatformEndpoint(){
		$options = array(
			'PlatformApplicationArn'=>$_POST['appArn'],
			'Token'=>$_POST['token'],
			'Attributes'=>array( 'Enabled'=>'TRUE' )
		);
		if( !empty( $_POST['userData'] ) ) $options['CustomUserData'] = $_POST['userData'];

        try{$res = self::getSns()->createPlatformEndpoint($options)['EndpointArn'];}
        catch( Exception $e ){
          	if( bs::debug() ) echo $e->getMessage();
            $res = FALSE;
        }
        util::response( HTTP_OK, $res );
	}

	// sns 푸시 삭제
	function deleteEndpoint(){
		try{$res = self::getSns()->deleteEndpoint( array( 'EndpointArn'=>$_POST['userArn'] ) );}
		catch( Exception $e ){
            if( bs::debug() ) echo $e->getMessage();
            $res = FALSE;
    	}
		util::response( HTTP_OK, $res );
	}

	// 1:1 푸시 발송
	function remotePush(){
		$data = array( 'text'=>$_POST['text'], 'userArn'=>$_POST['userArn'] );
		if( !empty($_POST['title']) ) $data['title'] = $_POST['title'];
		if( !empty($_POST['ticker']) ) $data['ticker'] = $_POST['ticker'];
		$data = json_encode($data);
		util::response( HTTP_OK, self::sendMessage($data) );
	}


	// 1:N 푸시발송
	function remoteMultiPush( $isExit = TRUE ){
		$text = strpos( $_POST['text'], '|' ) === false ? $_POST['text'] : explode( '|', $_POST['text'] );
		$userArn = explode( '|', $_POST['userArn'] );
		$data = array();
		for( $i=0; $i<count($userArn); $i++ ){
			$text = gettype($text) == 'array' ? $text[$i] : $text;
			$arn = $userArn[$i];
			$msg = array( 'text'=>$text,'userArn'=>$arn );
			if( !empty($_POST['title']) ) $msg['title'] = $_POST['title'];
			if( !empty($_POST['ticker']) ) $msg['ticker'] = $_POST['ticker'];
			$msg = json_encode($msg);
			array_push($data, array( 'Id'=>strval($i), 'MessageBody'=>$msg ) );
		}
		$isExit ? util::response( HTTP_OK, self::sendMessageBatch( $data, config::AWS_SQS_PUSH_SINGLE_URL ) ) :
			self::sendMessageBatch( $data, config::AWS_SQS_PUSH_MULTI_URL );
	}
}
?>
