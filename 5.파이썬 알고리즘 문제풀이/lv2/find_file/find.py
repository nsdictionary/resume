# A라는 디렉토리 하위에 있는 텍스트 파일(*.txt) 중에서 LIFE IS TOO SHORT 라는 문자열을 포함하고 있는 파일들을 모두 찾을 수 있는 프로그램을 작성하시오.
# 단, 하위 디렉토리도 포함해서 검색해야 함.

import sys, os

t0 = os.popen( 'find ' + sys.argv[1] + ' -name "*.txt"' )
files = []
for file in t0.read().split('\n'):
	if file:
		f = open(file, 'r');
		t0 = f.read()
		if t0.find('LIFE IS TOO SHORT') != -1: files.append(file)
		f.close()

print(files)