# 중복을 허용하는 정수 배열이 있다. 이러한 배열의 순차집합 중 가장 큰 갯수의 순차집합을 구하시오.
# 만약 아래와 같은 배열이라면
# 
# {1,6,10,4,7,9,5}
# 다음과 같은 순차집합이 있을 수 있다.
# 
# {1} : 1개
# {4,5,6,7} : 4개
# {9,10} : 2개
# 가장 큰 갯수의 순차집합은 원소 갯수가 4개인 {4,5,6,7} 이 된다.
# 
# Sort를 이용하면 문제가 너무 단순해지므로 Sort 함수를 이용하지 말고 O(n) 시간에 푸시오.

t0 = [1,6,10,4,7,9,5]
max = 0; maxList = False

done = True
for i in range(len(t0)):	
	for j in range(i+1, len(t0)):	
		if type(t0[i]) is not list and type(t0[j]) is not list:
			if abs(t0[i] - t0[j]) == 1:
				t0[i], t0[j] = False, list([t0[i], t0[j]])
				
		elif type(t0[i]) is list and type(t0[j]) is not list:	
			for k in t0[i]:
				if abs(k - t0[j]) == 1:
					t0[i], t0[j] = t0[i].push(t0[j]), False
					
		elif type(t0[i]) is not list and type(t0[j]) is list:
			for k in t0[j]:
				if abs(k - t0[i]) == 1:
					t0[j], t0[i] = t0[i].push(t0[i]), False
		else:
			done = True
			for k in t0[i]:
				if not done: break
				for l in t0[j]:
					if abs(k - l) == 1:
						t0[i], t0[j] = False, t0[i] + t0[j]
						done = False
						
t0.remove(False);
for i in range(len(t0)):
	if type(t0[i]) is not list:
		t0[i] = list([t0[i]])
		
	if len(t0[i]) > max:
		max = len(t0[i])
		maxList = t0[i]
				
print(set(maxList))