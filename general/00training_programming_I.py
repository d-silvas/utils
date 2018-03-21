import requests

url = 'https://www.wechall.net/challenge/training/programming1/index.php?action=request'
cookie = {'WC': '10337479-40113-A2HZpzHNn5lij7J5'}
r = requests.post(url, cookies=cookie)
secretCode = r.text
url2 = 'https://www.wechall.net/challenge/training/programming1/index.php?answer=' + secretCode
r2 = requests.post(url2, cookies=cookie)
print(r2.content)
