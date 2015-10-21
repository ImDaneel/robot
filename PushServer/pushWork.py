#!/usr/bin/python

from gearman import GearmanWorker
import json

SEND_MESSAGE_TIMEOUT = 3.0

class PushWorker(GearmanWorker):
    def __init__(self, host, queue):
        GearmanWorker.__init__(self, host)
        self.queue = queue

def batchPush(worker, data):
    try:
        addr = data['address']
        notifications = data['notifications']
    except:
        return 'fail'

    for notification in notifications:
        notification['address'] = addr
        worker.queue.put(json.dumps(notification))

    return 'success'

def pushCallback(worker, job):
    try:
        data = json.loads(job.data)
    except:
        return 'fail'

    if data.has_key('batch') and data['batch']:
        return batchPush(worker, data)

    worker.queue.put(job.data)
    return 'success'

def pushWork(queue):
    worker = PushWorker(['localhost:4730'], queue)
    worker.register_task('Push', pushCallback)
    worker.work()
