# 2나 5로 나눌 수 없는 0 이상 10,000 이하의 정수 n이 주어졌는데, n의 배수 중에는 10진수로 표기했을 때 모든 자리 숫자가 1인 것이 있다. 그러한 n의 배수 중에서 가장 작은 것은 몇 자리 수일까?

# Sample Input
# 3
# 7
# 9901

# Sample Output
# 3
# 6
# 12



def foo(n):
	if n % 2 == 0 or n % 5 == 0 or n > 10000: return
	offset = n;
	s = set( list( str(n) ) )
	while len(s) != 1 or int(list(s)[0]) != 1:
		n += offset
		s = set( list( str(n) ) )

	print( len( str(n) ) )



def bar(n):
	if n % 2 == 0 or n % 5 == 0 or n > 10000: return
	offset = n;
	while True:
		t0 = ''
		for i in range( len( str(n) ) + 1 ): t0 += '1'
		if int(t0) % offset == 0: break
		else: n += offset;

	print( len( str(t0) ) );



# foo(3)
# foo(7)
# foo(9901)

bar(3)
bar(7)
bar(9901)