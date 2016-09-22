# Quine은 자기 자신을 복사하는 프로그램이다. 즉, 인수를 취하지 않으며, 실행시 자신의 소스코드를 그대로 출력한다.

# 문제: 당신이 선호하는 언어를 이용해 Quine 프로그램을 작성하라.
 
# 단, 소스코드를 프로그램 외부(파일 등)에서 읽어올 경우 존경을 많이 받지 못한다.
# 위키백과 설명(스포일러이므로 문제를 도저히 못 풀 때만 보세요): http://en.wikipedia.org/wiki/Quine_(computing)


# s = '''def foo(): print(s)
# foo()'''
# exec(s)

bar = 'bar = %r; print(bar %% bar)'; print(bar % bar)