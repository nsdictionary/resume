#/bin/sh

# parameter 가 하나도 없으면 팅
if [ $# -lt 1 ]; then
	echo "오토 스케일링 그룹 최대 사이즈는 필수입니다."
	exit 0
fi

cmd='as-update-auto-scaling-group ycf-asg --max-size '$1''
$cmd

# 작업대기
WORK_PID=`jobs -l | awk '{print $2}'`
wait $WORK_PID

# 오토 스케일링 그룹 갱신 정보 출력
echo -e '\033[0;32m갱신된 ASG정보 :\033[0;34m'
desc_asg_cmd='as-describe-auto-scaling-groups -H'
$desc_asg_cmd

echo -e "\033[0;35m모든 명령이 종료되었습니다\033[0m"