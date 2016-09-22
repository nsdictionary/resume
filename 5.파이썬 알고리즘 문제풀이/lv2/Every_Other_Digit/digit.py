# 모든 짝수번째 숫자를 * 로 치환하시오.(홀수번째 숫자,또는 짝수번째 문자를 치환하면 안됩니다.)
# 로직을 이용하면 쉬운데 정규식으로는 어려울거 같아요.
# Example: a1b2cde3~g45hi6 → a*b*cde*~g4*hi6

t0 = 'a1b2cde3~g45hi6'
for i in range( len(t0) ):
	if (i+1) % 2 == 0 and t0[i].isdigit():
		t0 = t0[:i] + '*' +  t0[i+1:]
print(t0)