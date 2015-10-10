#!/usr/bin/python

from gearman import GearmanWorker
import threading
import json
import hashlib
import time
import random

SEND_MESSAGE_TIMEOUT = 3.0

class PushWorker(GearmanWorker):
    def __init__(self, host, queue):
        GearmanWorker.__init__(self, host)
        self.queue = queue
        self.send_threads = {}

    def on_job_complete(self, current_job, job_result):
        if job_result != 'suspend':
            self.send_job_complete(current_job, job_result)
        return True

class SendMessageThread(threading.Thread):
    def __init__(self, worker, job):
        threading.Thread.__init__(self)
        self.__worker = worker
        self.__job = job
        self.cond = threading.Condition()

    def run(self):
        try:
            data = json.loads(self.__job.data)
            if data['content'] == None:
                data['content'] = {}
        except:
            return self.__worker.send_job_complete(self.__job, 'fail')

        md5 = hashlib.md5()
        md5.update(self.__job.data + time.ctime() + str(random.randint(0, 999)))
        msg_id = md5.hexdigest()
        data['content']['id'] = msg_id
        self.__job.data = json.dumps(data)

        try:
            self.__worker.send_threads[msg_id] = self
        except:
            return self.__worker.send_job_complete(self.__job, 'fail')

        result = 'fail'
        for i in range(3):
            last_time = time.time()

            self.__worker.queue.put(self.__job.data)
            self.cond.acquire()
            self.cond.wait(SEND_MESSAGE_TIMEOUT)

            now = time.time()
            if now - last_time < SEND_MESSAGE_TIMEOUT:
                result = 'success'
                break

        self.__worker.send_threads.pop(msg_id, None)
        return self.__worker.send_job_complete(self.__job, result)

def pushCallback(worker, job):
    send_thread = SendMessageThread(worker, job)
    send_thread.start()
    return 'suspend'

def ackCallback(worker, job):
    try:
        data = json.loads(job.data)
        msg_id = str(data['id'])
        send_thread = worker.send_threads[msg_id]
    except:
        return 'fail'

    send_thread.cond.acquire()
    send_thread.cond.notify()
    send_thread.cond.release()

    return 'success'

def pushWork(queue):
    worker = PushWorker(['localhost:4730'], queue)

    worker.register_task('Push', pushCallback)
    worker.register_task('PushAck', ackCallback)

    worker.work()
