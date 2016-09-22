# 콘웨이의 라이프 게임(Conway's Game of Life)은 세포 시뮬레이션 알고리즘입니다. 각 세포는 살아있거나 죽어있는 두 상태가 있으며, 시뮬레이션 각 단계마다 주변 세포의 상태에 따라 자신의 상태를 갱신합니다. 이번 문제는 1차원 라이프 게임에 관한 것입니다.
 
# 0번부터 N-1까지의 N개의 세포가 있다고 합시다. i번째 세포의 이웃은 i-1번째와 i+1번째의 세포가 될 것입니다. 양 끝의 세포들은 모듈로(modulo) 값으로 이웃을 구합니다. 예를 들어, 0번 세포는 1번과 N-1번째가 이웃이 됩니다.
 
# 이제 라이프 게임의 규칙을 설명합니다.
 
# 매 단계마다 각 세포들은 자신의 두 이웃을 봅니다. 만약 두 이웃의 상태가 모두 같다면, 자신의 상태는 그대로 유지합니다. 만약 두 이웃의 상태가 다르다면, 자신의 상태를 바꿉니다. 이 규칙으로 다음 상태로 전이합니다. (다르게 말하면 오직 한 이웃만이 살아있을 때 자신의 상태를 바꾼다라고 표현할 수 있습니다.)
 
# 예를 들어, 8개의 세포, 01100101 을 생각해봅시다. 0은 죽어있는 상태, 1은 살아있는 상태라고 하죠.
 
# 0과 6번 세포는 모두 살아있는 이웃이 있습니다. 상태를 유지합니다.
# 5, 7번 세포는 살아있는 이웃이 없습니다. 상태를 유지합니다.
# 1, 2, 3, 4번 세포는 하나만 살아있는 이웃이 있습니다. 상태를 바꿉니다.
# 따라서 다음 단계는 00011101이 됩니다.
 
# 이번 코딩 문제는 주어진 세포 상태가 있을 때, "직전의 단계"를 구하는 것입니다. 어떨 때는 여러 이전 단계가 있을 수도 있고 불가능할 수도 있습니다.
 
# 주어진 세포 상태의 직전 단계가 유일하다면 그것을 출력하고, 없다면 "No", 여러 개라면 "Multiple"을 출력하는 코드를 만들어 보세요.
 
# 예: (꼭 인터페이스를 이렇게 할 필요는 없습니다) 
# computePrevState("00011101") == "01100101"
# computePrevState("000") == "Multiple"
# computePrevState("000001") == "No"
# computePrevState("11110") == "10010"
# 입력 문자열의 길이는 3에서 50까지로 가정합니다.

def gof(s):
	rs = ''
	for i in range(len(s)):
		if i < len(s)-1: rs += ( ( '1' if s[i] == '0' else '0' ) if s[i-1] != s[i+1] else s[i]  )
		else: rs += ( ( '1' if s[i] == '0' else '0' ) if s[i-1] != s[0] else s[i]  )			
	return rs;

def checkNo(s):
	chk = s[0]
	for i in range( len(s[1:] ) ):
		if chk == s[1:][i]: return False
		chk = s[1:][i]
	return True
	
def computePrevState(s):
	origin = ''
	prev = ''
	while True:
		if s == origin: print(prev); break;
		elif len( set( list(s) ) ) == 1: print('Multiple');break
		elif checkNo(s): print('No'); break		
		if origin == '': origin = s
		
		prev = s
		s = gof(s)	

computePrevState('00011101')
computePrevState('000')
computePrevState('000001')
computePrevState('11110')
