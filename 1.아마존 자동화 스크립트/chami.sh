#!/bin/bash

# parameter 가 하나도 없으면 팅
if [ $# -lt 1 ]; then
	echo "AMI ID는 필수정보 입니다. ami-id를 입력하세요"
	exit 0
fi

# 현재 launch config 출력
declare -a lc_name
declare -a lc_name_old
IFS=' '
ary=(`as-describe-launch-configs`)
for key in "${!ary[@]}" ; do			
	a=${ary[$key]}	
	b=${a:0:3}
		
	if [ "$b" == "ycf" ] 
	then		
		if [ "${a:7:1}" == "1" ] 
		then
			lc_name='ycf-lc-2'
			lc_name_old='ycf-lc-1'
		else
			lc_name='ycf-lc-1'
			lc_name_old='ycf-lc-2'
		fi
	fi
done

echo -e '\033[0;32m생성할 lc 이름 : '$lc_name'\033[0;34m'

# 새로운 lc 생성
create_new_lc_cmd='as-create-launch-config '$lc_name' --image-id '$1' --instance-type m1.small --group ycf-sg --key fivethirty_key_california --user-data-file /Workspace/Server/tool/startup_script.sh'
$create_new_lc_cmd

# 작업대기
WORK_PID=`jobs -l | awk '{print $2}'`
wait $WORK_PID

# 오토 스케일링 그룹 lc갱신
update_asg_cmd='as-update-auto-scaling-group ycf-asg --launch-configuration '$lc_name''
$update_asg_cmd

# 작업대기
WORK_PID=`jobs -l | awk '{print $2}'`
wait $WORK_PID

# 기존 lc 삭제
delete_lc_cmd='as-delete-launch-config '$lc_name_old' -f'
$delete_lc_cmd


# 작업대기
WORK_PID=`jobs -l | awk '{print $2}'`
wait $WORK_PID

# 새로운 lc정보 출력
echo -e "\033[0;33m--새로운 lc 정보 :\033[0;32m"
desc_lc_cmd='as-describe-launch-configs -H'
$desc_lc_cmd

# 새로운 asg정보 출력 
echo -e '\033[0;33m--새로운 asg 정보 :\033[0;32m'
desc_asg_cmd='as-describe-auto-scaling-groups -H'
$desc_asg_cmd

echo -e "\033[0;35m모든 명령이 종료되었습니다\033[0m"

