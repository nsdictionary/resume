# 아래와 같은 결과를 출력하는 function을 구현하라
#
# bool OneEditApart(string s1, string s2)
#
# OneEditApart("cat", "dog") = false
# OneEditApart("cat", "cats") = true
# OneEditApart("cat", "cut") = true
# OneEditApart("cat", "cast") = true
# OneEditApart("cat", "at") = true
# OneEditApart("cat", "acts") = false
# 한개의 문자를 삽입, 제거, 변환을 했을때 s1, s2가 동일한지를 판별하는 OneEditApart 함수를 작성하시오.

def c( a, b, differ ):
	if len(differ) == 1:
		return True if a == b.replace( differ[0], '' ) else False
	elif len(differ) == 0:
		rs = []
		for i in range( len(b) ):
			if b[i] not in rs and a[len(rs)] == b[i]: rs.append(b[i])
		return True if a == ''.join(rs) else False
	else:
		return False


def d( a, b, differ ):
	if len(differ) > 0:
		return False
	else:
		for i in range( len(a) ):
			if b == a[:i] + a[i+1:]: return True
		return False


def u( a, b, differ ):
	if len(differ) > 0:
		for i in range( len(a) ):
			if b == a[:i] + differ[0] + a[i+1:]: return True
		return False
	else:
		if a == b: return True
		else:
			for i in range( len(b) ):
				b1 = b[:i] + b[i+1:]
				d1 = [x for x in b1 if x not in a]
				if d(a, b1, d1): return True
			return False

def foo( a, b ):
	differ = [x for x in b if x not in a]
	if 	 len(a) + 1 == len(b): return c( a, b, differ )
	elif len(a) - 1 == len(b): return d( a, b, differ )
	elif len(a)     == len(b): return u( a, b, differ )
	else: return False

print( foo( 'cat', 'dog' ) )
print( foo( 'cat', 'cats' ) )
print( foo( 'cat', 'cut' ) )
print( foo( 'cat', 'cast' ) )
print( foo( 'cat', 'at' ) )
print( foo( 'cat', 'acts' ) )