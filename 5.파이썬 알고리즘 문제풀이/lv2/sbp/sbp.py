# 아래는 괄호를 이용한 연산식이다.

# (5+6)∗(7+8)/(4+3)
# 우리는 여는 괄호가 있으면 닫는 괄호가 반드시 있어야 한다는 것을 잘 알고 있다.

# 다음은 정상적인(balanced) 괄호 사용의 예이다.
# (()()()())
# (((())))
# (()((())()))

# 다음은 비정상적인(not balanced) 괄호 사용의 예이다.
# ((((((())
# ()))
# (()()(()
# (()))(
# ())(()
# 괄호의 사용이 잘 되었는지 잘못 되었는지 판별 해 주는 프로그램을 작성하시오.

import re

def chk(p):
	regex = re.compile("[()]")
	l1 = ''.join(regex.findall(p))

	while l1:
		if l1[0] != '(': return False
		else:
			l1 = l1.replace( '(', '', 1 )
			if l1.find(')') == -1: return False
			l1 = l1.replace( ')', '', 1 )
	return True


print( chk('(5+6)∗(7+8)/(4+3)') )
print( chk('(()()()())') )
print( chk('(((())))') )
print( chk('(()((())()))') )

print( chk('((((((())') )
print( chk('()))') )
print( chk('(()()(()') )
print( chk('(()))(') )
print( chk('())(()') )