board = [['1','2','3'],['4','5','6'],['7','8','9']]
symbol = ['X', 'O']
player = 1

def chkWin():		
	if board[0][0] == board[1][1] and board[1][1] == board[2][2]:
		return board[0][0]	
	elif board[0][2] == board[1][1] and board[1][1] == board[2][0]:
		return board[0][2]
	else:
		for i in range(len(board)):			
			if len(list(set([board[0][i],board[1][i], board[2][i]]))) == 1:
				return board[0][i]
			elif len(list(set([board[i][0], board[i][1], board[i][2]]))) == 1:
				return board[i][0]
				
	return False
	
def printBoard():
	print('-' * 7)	
	for t in board:
		print('|' + '|'.join(t) + '|')		
	print('-' * 7)
		

def tic(player, idx):	
	board[int((idx-1) / 3)][(idx-1) % 3] = symbol[player-1]
	printBoard()
	if chkWin() != False:
		print('Win playear is: player %s' % player)
		return False
				
	return True
	

def play(player):
	remainPos = ''
	for arr in board:
		for pos in arr:
			if pos not in symbol:
				remainPos += pos 
		
	if remainPos == '':
		print('Game is draw.')
		return False
	else:		
		print('Player %d - please type a position \
(available position(s) are %s):' % (player, ','.join(list(remainPos))))
		
		pos = int(input())
		if remainPos.find(str(pos)) == -1:
			print('Not available position.')
			return play(player)
		else:
			return tic(player, pos)


printBoard()
while True:
	player ^= 1
	if play(player + 1) == False: break


