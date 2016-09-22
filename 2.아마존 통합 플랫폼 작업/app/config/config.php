<?php
class config{
// aws info
const AWS_ACCESS_KEY = '******************';											// 액세스 키
const AWS_SECRET_KEY = '******************';											// 시크릿 키
const AWS_REGION = 'us-west-1';															// 캘리포니아 북부
const AWS_SNS_IOS_ARN = 'arn:aws:sns:us-west-1:639746878306:app/APNS/*************'; 	// SNS iOS app ARN
const AWS_SNS_AND_ARN = 'arn:aws:sns:us-west-1:639746878306:app/GCM/*********';			// SNs Android app ARN
const AWS_S3_BUCKET = '******************';												// S3 Bucket 이름
const AWS_S3_DIR_NAME = '******************';											// S3 썸네일 디렉토리 이름
const AWS_CDN_URI = 'http://cdn.******************.net/';								// CDN 주소
const UPLOAD_FILE_PATH = '/usr/srv/app/upload/';										// 썸네일 파일 업로드 디렉토리

const AWS_SQS_PUSH_SINGLE_URL = 'https://sqs.us-west-1.amazonaws.com/******************';
const AWS_SQS_PUSH_MULTI_URL = 'https://sqs.us-west-1.amazonaws.com/******************';

const SNS_ARN_LIST_URL = 'http://******************:8002/bs/txt/';
const SNS_PUSH_DEFAULT_LANGUAGE = 'en';
};
?>
