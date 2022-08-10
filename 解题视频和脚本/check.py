# -*- coding:utf8 -*-
import sys
import re
import requests

#除了返回的正常/错误，其余不输出任何信息，且需要设置超时时间！

def check1(url):
    try:
        res = requests.get(url,timeout=5)
        if res.status_code == 301:
            return True
    except Exception as e:
        return False
    return True

def check2(url):
    try:
        res = requests.get(url+"login.php?act=in",timeout=5)
        if res.status_code == 200:
            return True
    except Exception as e:
        return False
    return True

def check3(url):
    try:
        return True
    except Exception as e:
        return False
    return True

def checker(host, port):
    try:
        url = "http://"+ip+":"+str(port)
        if check1(url) and check2(url) and check3(url):
            return (True,"IP: "+host+" OK")
    except Exception as e:
        return (False, , "IP: "+host+" is down, "+str(e))

if __name__ == '__main__':
    # print(checker("192.168.8.10", 10001))
    ip=sys.argv[1]
    port=int(sys.argv[2])
    print(checker(ip,port))
