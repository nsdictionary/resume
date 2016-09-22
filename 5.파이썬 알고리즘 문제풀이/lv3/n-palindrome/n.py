# 앞뒤가 같은 수는 바로 쓴 값과 거꾸로 쓴 값이 같은 수이다. 다음과 같은 예를 들 수 있다.

# 1
# 44
# 101
# 2002
# 8972798
# 1111111111111
# 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 11, 22, 33, 44, 55, 66, 77, 88, 99, 101, 111, ... 과 같이,
# 0 이상의 앞뒤가 같은 수를 크기 순으로 나열할 때, n 번째 수를 계산하는 프로그램을 작성하라.

# n은 1부터 시작하며 크기에는 제한이 없다.
 
# 입출력예
 
# 예 1) 1 => 0
# 예 2) 4 => 3
# 예 3) 30 => 202
# 예 4) 100 => 909
# 예 5) 30000 => 200000002
# 예 6) 1000000 => 90000000009

def foo(n):
	loop = i = 0
	while loop < n:
		if str(i) == str(i)[::-1]:
			loop += 1
		i+=1
	print(i-1)
	
# foo(1)
# foo(4)
# foo(30)
# foo(100)
# foo(30000)
# foo(1000000)	


def bar(n):
	pLen = 1; lenTotal = 0; palindrome = None	
	while lenTotal < n:
		palindromes = getPalindromes(pLen)
		lenTotal += len(palindromes)
		pLen += 1				
	print(palindromes[n - (lenTotal+1)])

def getPalindromes(l):
    if l < 1: return []
    if l == 1: return [x for x in range(10)]
    palindromes = []
    if l % 2 == 0:
        halfLen = l // 2
        for i in range(10 ** (halfLen - 1), 10 ** halfLen):
            palindromes.append(int(str(i) + str(i)[::-1]))
    else:
        halfLen = (l-1) // 2
        for i in range(0, 10):
            for j in range(10 ** (halfLen - 1), 10 ** halfLen):
                palindromes.append(int(str(j) + str(i) + str(j)[::-1]))
    palindromes.sort()
    return palindromes
    
bar(1)
bar(4)
bar(30)
bar(100)
bar(30000)
bar(1000000)	