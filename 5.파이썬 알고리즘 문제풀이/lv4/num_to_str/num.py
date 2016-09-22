# 알파벳을 다음과 같이 숫자에 매핑했을 때,

# a=1, b=2, c=3,....z=26
# 숫자로 만들어 질 수 있는 모든 문자열을 찾으시오.
 
# 예: 
# 입력:
# 1123

# 출력:
# aabc // a = 1, a = 1, b = 2, c = 3 
# kbc  // k는 11 이므로, b = 2, c= 3 
# alc  // a = 1, l = 12, c = 3 
# aaw  // a= 1, a =1, w= 23 
# kw   // k = 11, w = 23

from string import ascii_lowercase
input = str(1123)
apbs = list(ascii_lowercase)

for i in range(len(str(input))-1):	
	if int(input[i] + input[i+1]) <= len(apbs):
		print(input[i] + input[i+1])