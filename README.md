# 6/29 ChatBot HW2

## Result Photo Example :
![](https://i.imgur.com/TOgLxih.png)

## Code

```python=
#!/usr/bin/python
#-*- encoding: UTF-8 -*-

from collections import OrderedDict
from multiprocessing import Pool
import socket
import time

target_host = "140.116.245.151"
target_port = 9998

def seg(sentence):
    # create socket
    # AF_INET 代表使用標準 IPv4 位址或主機名稱
    # SOCK_STREAM 代表這會是一個 TCP client
    client = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    # client 建立連線
    client.connect((target_host, target_port))
    # 傳送資料給 target
    data = "seg@@" + sentence
    client.send(data.encode("utf-8"))

    # 回收結果信息
    data = bytes()
    while True:
        request = client.recv(8)
        if request:
            data += request
            begin = time.time()
        else:
            break

    WSResult = []
    response = data
    if(response is not None or response != ''):
        response = response.decode('utf-8').split()
        for resp in response:
            resp = resp.strip()
            resp = resp[0:len(resp)-1]
            temp = resp.split('(')
            word = temp[0]
            pos = temp[1]
            WSResult.append((word,pos))

    return WSResult

sentence = input("Input sentence: ")
# sentence = "線上展示使用簡化詞類進行斷詞標記，僅供參考並且系統不再進行更新"

result = seg(sentence)

'''
print(result)
print(result[0])
print(result[0][0])
'''

people = []
time = []
place = []
unsorted = []
item = []

for temp in result:
    if temp[1] == 'Nb':
        people.append(temp[0])
    elif temp[1] == 'Nc':
        place.append(temp[0])
    elif temp[1] == 'Na':
        item.append(temp[0])
    elif temp[1] == 'Nd':
        time.append(temp[0])
    else:
        unsorted.append(temp)

print ("Person Name List")
print (people)
print ("Time List")
print (time)
print ("Location List")
print (place)
print ("Object List")
print (item)

simple = []
complete = []

tmp = ''
completetmp = ''


for temp in result:
    if temp[1] == 'VC':
        tmp = temp[0]
        completetmp += temp[0]
    elif temp[1] == 'VA':
        simple.append(temp[0])
        complete.append(temp[0])
    elif temp[1] == 'Cab' or temp[1] == 'Cba' or temp[1] == 'Cbb':
        tmp = ''
        completetmp += temp[0]
    elif (temp[1] == 'Na' or temp[1] == 'Nb' or temp[1] == 'Nc' or temp[1] == 'Nd') and tmp != '':
        tmp += temp[0]
        completetmp += temp[0]
        simple.append(tmp)
        complete.append(completetmp)
        tmp = ''
        completetmp = ''
    elif temp[1] == 'COMMACATEGORY' or temp[1] == 'PERIODCATEGORY':
        completetmp = ''
    elif completetmp != '':
        completetmp += temp[0]

print ("Simple Event List")
print (simple)
print ("Complete Event List")
print (complete)
'''
print ("Unsorted")
print (unsorted)
'''

```
