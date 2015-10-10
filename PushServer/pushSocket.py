#!/usr/bin/python

import socket
import threading
import json
from gearman import GearmanClient

class SendThread(threading.Thread):
    def __init__(self, sock, queue):
        threading.Thread.__init__(self)
        self.__sock = sock
        self.__queue = queue

    def run(self):
        while True:
            data = self.__queue.get(True)
            if not data:
                continue
            #print "recevied:", data, "from queue"

            try:
                data = json.loads(data)
                topic = 'Push' + (str(data['type'])).capitalize()
                addr = (str(data['address'])).split(':')
                content = data['content']
            except:
                continue

            msg = dict(topic=topic, content=content)
            self.__sock.sendto(json.dumps(msg), (addr[0], int(addr[1])))

def pushSocket(queue):
    sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    serverAddr = ('0.0.0.0', 28282);
    sock.bind(serverAddr)

    send_thread = SendThread(sock, queue)
    send_thread.start()

    gm_client = GearmanClient(['localhost:4730'])

    while True:
        msg, addr = sock.recvfrom(2048)
        if not msg:
            continue
        #print "recevied:", msg, "from", addr

        try:
            msg = json.loads(msg)
            topic = str(msg['topic'])
            content = msg['content']
        except:
            continue

        if content == None:
            content = dict()
        content['external_addr'] = addr[0] + ':' + str(addr[1])
        gm_request = gm_client.submit_job(topic, json.dumps(content), background=False, wait_until_complete=False)

    sock.close()

