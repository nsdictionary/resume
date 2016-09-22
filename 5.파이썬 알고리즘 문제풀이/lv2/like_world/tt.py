def oneEditApart(s1, s2):
    s1_idx_in_s2=[-1]
    for i in range(len(s1)):
        s1_idx_in_s2.append(s2.find(s1[i], s1_idx_in_s2[i]+1))

    s1_idx_in_s2=s1_idx_in_s2[1:]

    if len(s2)==len(s1)+1:
        return True if min(s1_idx_in_s2)>=0 else False
    elif len(s2) in (len(s1), len(s1)-1):
        s1_idx_in_s2.remove(-1)
        return True if min(s1_idx_in_s2)>=0 else False
    else:
        return False

print(oneEditApart("cat", "cct"))
print(oneEditApart("cat", "caa"))
print(oneEditApart("caat", "tcaat"))

print(oneEditApart("cat", "dog"))
print(oneEditApart("cat", "cats"))
print(oneEditApart("cat", "cut"))
print(oneEditApart("cat", "cast"))
print(oneEditApart("cat", "at"))
print(oneEditApart("cat", "acts"))