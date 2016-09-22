t0 = [5,2,4,6,1,3]
for i in range( 1, len(t0) ):
	for j in range( i ):
		while t0[i] < t0[j]:
			t0[i], t0[j] = t0[j], t0[i]
print(t0)