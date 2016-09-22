# 주어진 문자열(공백 없이 쉼표로 구분되어 있음)을 가지고 아래 문제에 대한 프로그램을 작성하세요.
# 이유덕,이재영,권종표,이재영,박민호,강상희,이재영,김지완,최승혁,이성연,박영서,박민호,전경헌,송정환,김재성,이유덕,전경헌

# 김씨와 이씨는 각각 몇 명 인가요?
# "이재영"이란 이름이 몇 번 반복되나요?
# 중복을 제거한 이름을 출력하세요.
# 중복을 제거한 이름을 오름차순으로 정렬하여 출력하세요.

names = "이유덕,이재영,권종표,이재영,박민호,강상희,이재영,김지완,최승혁,이성연,박영서,박민호,전경헌,송정환,김재성,이유덕,전경헌".split(',')

kimCnt = leeCnt = dupLjyCnt = 0
delDupNames = list( set(names) )

for name in names:
	if name[0] == '김': kimCnt += 1
	elif name[0] == '이': leeCnt += 1
	if name == '이재영': dupLjyCnt+= 1

print('김씨: ' + str(kimCnt) + '명')
print('이씨: ' + str(leeCnt) + '명')
print('이재영 반복: ' + str(dupLjyCnt) + '번')
print('중복제거: ' +  ', '.join(delDupNames) ); delDupNames.sort()
print('오름차순: ' + ', '.join(delDupNames) )



