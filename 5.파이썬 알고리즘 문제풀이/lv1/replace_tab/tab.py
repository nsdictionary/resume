import sys, os
f = open( sys.argv[1], 'r' )
t0 = f.read()
f.close()

f = open( sys.argv[1], 'w' )
f.write( t0.replace( '\t', ' ' * 4 ) )
f.close()

os.system( 'cat ' + sys.argv[1] )
