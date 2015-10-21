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
            msg = self.__queue.get(True)
            if not msg:
                continue
            #print "recevied:", msg, "from queue"

            try:
                data = json.loads(msg)
                if data['content'] == None:
                    data['content'] = {}
                else:
                    data['content'] = json.loads(data['content'])

                data['content']['id'] = data.pop('msg_id')
                addr = (str(data.pop('address'))).split(':')
            except:
                continue

            self.__sock.sendto(json.dumps(data), (addr[0], int(addr[1])))

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

            if content == None:
                content = dict()
            content['external_addr'] = addr[0] + ':' + str(addr[1])
        except:
            continue

        gm_request = gm_client.submit_job(topic, json.dumps(content), background=False, wait_until_complete=False)

    sock.close()

