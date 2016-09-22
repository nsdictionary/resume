#!/bin/bash

# 인스턴스 리스트 예시
# ELB_LIST="INSTANCE_ID  i-9b5b0faf  InService  N/A  N/A
# INSTANCE_ID  i-d06e0de4  InService  N/A  N/A"

# 로드 밸런서 에속한 인스턴스 리스트 출력
ELB_LIST=`elb-describe-instance-health --lb ycf-lb`;

declare -a IDS			#서버 ami-id 리스트
declare -a INFOS		#서버 정보 리스트
declare -a SERVER		#서버 퍼블릭 도메인 리스트

#리스트 루프 카운트
id_count=0
info_count=0
addr_count=0

# 리스트에서 인스턴스 id를 파싱해서 IDS 배열에 insert
IFS=' '
ary=($ELB_LIST)
for key in "${!ary[@]}" ; do
	a=${ary[$key]}
	b=${a:0:2}

	if [ "$b" == "i-" ]
	then
		IDS[$id_count]=$a
		id_count=$(($id_count+1))
	fi
done


# 해당 인스턴스 아이디로 서버정보를 받아와서 INFOS 배열에 insert
for key in ${IDS[@]} ; do
	ec2din='ec2din --filter "instance-id='$key'"'
	INFO=`$ec2din`

	INFOS[$info_count]=$INFO;
	info_count=$(($info_count+1))
done


# 각각 서버 정보에서 퍼블릭 도메인을 파싱해서 SERVER 배열에 insert
for ((i=0; i < ${#INFOS[@]}; i++)) do
	IFS='	'
	ary=(${INFOS[$i]})
	for key in "${!ary[@]}"; do

		a=${ary[$key]}
		b=${a:0:4}

		if [ "$b" == "ec2-" ]
		then
			SERVER[$addr_count]=$a
			addr_count=$(($addr_count+1))
		fi
	done
done

# 서버리스트 출력, 라이브 서버가 없다면 쉘 종료
if [ ${#SERVER[@]} -eq 0 ] ; then
	echo "서버 리스트가 없습니다"
	exit
else
	echo -e "\033[0;32m서버리스트 : "
	echo -e "${SERVER[@]}\033[0m"
fi

# exit;

# 서버리스트의 퍼블릭 도메인을 조회해서 ping 테스트후에 이상이 없으면 ssh로 접속하여 git서버에서 최신 소스로 갱신
for ((i=0; i < ${#SERVER[@]}; i++)) do
	echo "---------------------------------------------------------------------------------------------"

	cnt=$(($i+1))
	echo -e "\033[0;34m$cnt 번째 서버\033[0m : ${SERVER[$i]}  .. 접속 시도중"

	ping -c 1 -w 1 ${SERVER[$i]}  &> /dev/null

	echo 'dev/null return '"$?"

	if [ "$?" -eq 0 ] ; then
		echo -e "${SERVER[$i]}  .. \033[0;34m접속 성공 git pull 시도\033[0m"

		ssh  -o "StrictHostKeyChecking no" -i /Users/sanghyunlyu/Dropbox/kota/sign/aws/fivethirty/fivethirty_key_jp.pem ubuntu@${SERVER[$i]} "cd /usr/srv/ycf; sudo git pull origin master"
	else
		echo "\033[0;31m${SERVER[$i]}  .. 접속 실패! 점검 요망\033[0m"
	fi
	echo "---------------------------------------------------------------------------------------------"
done