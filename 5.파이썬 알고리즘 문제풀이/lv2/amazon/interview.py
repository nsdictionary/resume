# 다음과 같은 형태의 배열을
# [a1,a2,a3...,an,b1,b2...bn]

# 다음과 같은 형태로 바꾸시오
# [a1,b1,a2,b2.....an,bn]

l1 = ['a1', 'a2', 'a3', 'a4', 'b1', 'b2', 'b3', 'b4']
rs = []; half = int( len(l1) / 2 )
for i in range( half ): rs.extend( ( l1[i], l1[i + half] ) )
print(rs)