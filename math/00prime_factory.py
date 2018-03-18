from sympy import isprime

sol = []
n = 1000000

while len(sol) < 2:
    if isprime(n):
        chars = str(n)
        if isprime(sum([int(i) for i in chars])):
            sol.append(n)
    n += 1

print(sol)
