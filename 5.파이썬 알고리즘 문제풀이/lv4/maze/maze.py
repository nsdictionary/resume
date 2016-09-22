# # 통과 불가능
# (공백) 통과 가능
# < 시작 지점
# > 목적 지점

mazeList = [
'<     >',

"""########
#<     #
#  ##  #
#  ##  #
#     >#
########""",	

"""#######
#<    #
##### #
#     #
# #####
# #   #
# # # #
#   #>#
#######""",	

'<   #   >',

"""########
#<     #
#     ##
#    #>#
########""",

""""#< #  #
#  #  #
#  # >#"""
]




def left(line):
	curPos = line.index('<')
	if line[curPos-1] == '#' or curPos == 0:
		return line
	else:
		line = line.replace('<', ' ')
		return line[:curPos-1] + '<' + line[curPos:]
		
def right(line):
	curPos = line.index('<')
	if line[curPos+1] == '#' or curPos == len(line)-1:
		return line
	else:
		line = line.replace('<', ' ')
		return line[:curPos+1] + '<' + line[curPos+2:]
		
def down(line, nextLine):
	curPos = line.index('<')
	if nextLine[curPos] == '#':
		return line, nextLine
	else:
		line = line.replace('<', ' ')
		nextLine = nextLine[:curPos] + '<' + nextLine[curPos+1:]
		return line, nextLine
		


def findExit(maze):
	print(maze)	
	mazeLines = maze.split('\n')
		
	sLidx = None
	fLidx = None
	
	for i in range(len(mazeLines)):
		if mazeLines[i].find('<') != -1:
			sLidx = i
		elif mazeLines[i].find('>') != -1:
			fLidx = i
			
	mazeLines[sLidx] = right(mazeLines[sLidx])
	
	if mazeLines[sLidx+1]:
		mazeLines[sLidx], mazeLines[sLidx+1] = down(mazeLines[sLidx],
													mazeLines[sLidx+1])
		sLidx += 1
	print('\n'.join(mazeLines))
	


findExit(mazeList[1])

















































