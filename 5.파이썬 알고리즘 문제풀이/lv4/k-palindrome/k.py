# k-palindrome 은 문자열에서 최대 k개의 문자를 제거했을 때 palindrome이 되는 문자열을 말한다.
# 문자열 S와 정수값 K가 주어질 때 주어진 문자열이 k-palindrome일 경우 "YES", 아닐경우에는 "NO"를 출력하시오.
# (단, S의 최대길이는 20,000, K의 범위는 0<=K<=30)
# 팔린드롬(palindrome) - 바로 읽든 거꾸로 읽든 결과가 같아지는 단어, 문구, 숫자를 말한다. (예: 'eye', 'Madam', '아들딸들아')

# 샘플 예:
# Input - abxa 1 
# Output - YES 

# Input - abdxa 1 
# Output - No

def chk_palindrome(s):
	return True if s.lower() == s[::-1].lower() else False
	
def chk_k_palindrome(s, k, loop=0, originLen=None):
	if len(s) > 20000 or 0 > k or k > 30 or k < 0: return
	if not originLen: originLen = len(s)
	
	if loop < k:
		for i in range(len(s)):
			if chk_palindrome(s[:i] + s[i+1:]):
				print('YES')
				return True
			elif chk_k_palindrome(s[:i] + s[i+1:], k, loop+1):
				return True
			elif i == originLen-1 and loop == 0:
				print('NO')
				return False
	else:
		return False

												
chk_k_palindrome('xkxacbcakz', 2)
chk_k_palindrome('abxa', 1)
chk_k_palindrome('abdxa', 1)