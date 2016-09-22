def pivo(n):
	if n == 0: print([0]); return
	t0 = [0,1]
	while t0[len(t0)-2] + t0[len(t0)-1] <= n:
		t0.append(t0[len(t0)-2] + t0[len(t0)-1])
	print(t0);