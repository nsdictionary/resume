#!/bin/bash

#	php 실행
if [ "`pgrep -x php | wc -l`" -lt "1" ] ; then
	if [ -z "$1" ] ; then
		cd /
		php -f /usr/srv/app/app/bsPHP.0.1.php intranet /getPushJob
	fi
fi