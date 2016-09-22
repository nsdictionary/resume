# '술취한 바퀴벌레' 문제라고도 한다. 다음과 같은 격자에 술취한 바퀴벌레가 있다고 해 보자
# .	. .	.
# .	. .	.
# .	. .	.
# .	. .	.
# .	. .	.
# .	. .	.
# .	. .	.

# 바퀴벌레는 임의의 한 점에서 시작하여서 임의의 방향으로 움직이게 된다. 이미 지나갔던 자리에 다시 갈 수 있으며 프로그램은 바퀴벌레가 각 위치에 몇번 갔는지 기억하여야 한다. 프로그램은 바퀴벌레가 모든 지점에 적어도 한번 이상 도달하였을 경우 끝난다. 바퀴벌레는 가로, 세로, 대각선으로 한칸 씩만 움직일수 있으며, 바퀴벌레가 움직이는 방향을 랜덤하게 만드는 것은 각자가 생각해 보도록 한다.

# 입력
# 격자의 가로, 세로 크기, 바퀴벌레의 초기 위치

# 출력
# 각 칸에 바퀴벌레가 멈추었던 횟수, 바퀴벌레가 움직인 횟수.


# 참고

# 무작위 행보(無作爲行步, random walk, 랜덤 워크)는 수학, 컴퓨터 과학, 물리학 분야에서 임의 방향으로 향하는 연속적인 걸음을 나타내는 수학적 개념이다. 무작위 행보(random walk)라는 개념은 1905년 Karl Pearson이 처음 소개하였으며 생태학, 수학, 컴퓨터 과학, 물리학, 화학 등의 분야에서 광범위하게 사용되고 있다.
# 랜덤 워크는 시간에 따른 편차의 평균이 0이지만 분산은 시간에 비례하여 증가하게 된다. 따라서, 앞 뒤로 움직일 확률이 동일하다고 해도 시간이 흐름에 따라 평균에서 점차 벗어나는 경향을 보인다. 
# 대표적인 예로는 브라운 운동이 있으며, "술고래의 걸음"이라고도 한다.

import random
import time

startTime = time.time()

w = 100; h = 100; pos = [0, 0]; moveCnt = 0
posCounter = [[0 for x in range(w)] for y in range(h)]
posCounter[pos[0]][pos[1]] += 1

def getMovablePos():
	movablePos = [
		[[-1, -1], [-1, 0], [-1, 1]],
		[[0, -1], False, [0, 1]],
		[[1, -1], [1, 0], [1, 1]]
	]
	
	if pos[0] == 0:
		movablePos[0] = list([False])*3
	elif pos[0] == h-1: 		
		movablePos[2] = list([False])*3
		
	if pos[1] == 0:
		for p in movablePos: p[0] = False
	elif pos[1] == w-1:
		for p in movablePos: p[2] = False
	
	rs = []
	for line in movablePos:
		for p in line:
			if p is not False: rs.append(p)	
	return rs


def randomWalk():
	movablePos = getMovablePos()
	movePos = movablePos[random.randrange(0, len(movablePos))]
	
	for i in range(2): pos[i] += movePos[i]
	posCounter[pos[0]][pos[1]] += 1	


def chkEnd():
	for i in range(len(posCounter)):
		for j in range(len(posCounter[0])):
			if posCounter[i][j] == 0: return False			
	return True

while True:
	if chkEnd(): break		
	randomWalk()
	moveCnt += 1
	
endTime = time.time()
print(posCounter, moveCnt, endTime - startTime)
	


