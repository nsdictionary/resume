# 위 그림을 참조하여 라이브러리를 사용하지 말고 10진수를 n진수로 변환하는 프로그램을 작성하시오.. (단, n의 범위는 2 <= n <= 16)
# 예)
# 2진수로 변환 : 23310 --> 111010012
# 8진수로 변환 : 23310 --> 3518
# 16진수로 변환 : 23310 --> E916

import math
def convert( decimal, n ):
	rs = []
	while decimal > 0:
		t0 = decimal % n
		if t0 > 9: t0 =['A','B','C','D','E','F'][t0 - 10]
		rs.append( str( t0 ) )
		decimal = math.floor( decimal / n );

	rs.reverse()
	print( ''.join(rs) );

convert(233, 2)
convert(233, 8)
convert(233, 16)