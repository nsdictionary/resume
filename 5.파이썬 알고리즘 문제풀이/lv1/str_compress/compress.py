# 문자열을 입력받아서, 같은 문자가 연속적으로 반복되는 경우에 그 반복 횟수를 표시하여 문자열을 압축하기.
# 입력 예시: aaabbcccccca
# 출력 예시: a3b2c6a1

import sys; t0 = sys.argv[1]
prev = rs = ''
dupCnt = 1
for i in range( len(t0) ):
	if t0[i] == prev:
		dupCnt +=1;
	elif prev != '':
		rs = rs + prev + str(dupCnt)
		dupCnt = 1;
	prev = t0[i]
	if i == len(t0)-1: rs = rs + prev + str(dupCnt)

print(rs)