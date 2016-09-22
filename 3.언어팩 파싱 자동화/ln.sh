#!/bin/bash

if [ -$1 == "" ]; then
	echo '통합 파일팩 파일명을 입력하세요'
	exit
fi

LANGUAGE_PACK=`cat "$1"`
declare -a LINE_ARR
IFS='
'

ary=($LANGUAGE_PACK)
for key in "${!ary[@]}"; do
	LINE_ARR[$line_count]=${ary[$key]}
	line_count=$((line_count+1))
done


declare -a KEY_ARR
declare -a FILE_NAMES
key_count=0
col_count=0
file_names_count=0

for LINE in "${LINE_ARR[@]}"; do
	IFS='	'
	ary=($LINE)
	for key in "${!ary[@]}"; do
		KEY=${ary[$key]}
		KEY_ARR[$key_count,$col_count]=$KEY

		# 파일 이름 배열 생성
		if [ $key_count -eq 0  ]; then
			if [ $col_count -ge 1  ]; then
				FILE_NAMES[$file_names_count]=$KEY.txt
				file_names_count=$((file_names_count+1))
			fi
		fi

		col_count=$((col_count+1))
	done


	if [ $key_count -ne 0  ]; then
		i=1
		for k in "${FILE_NAMES[@]}"; do
			echo "${KEY_ARR[$key_count,0]} = ${KEY_ARR[$key_count,$i]}" >> ${FILE_NAMES[$((i-1))]}
			i=$((i+1))
		done
	fi

	key_count=$((key_count+1))
	col_count=0
done


