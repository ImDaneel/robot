#!/usr/bin/python

from multiprocessing import Process, Queue
from pushSocket import pushSocket
from pushWork import pushWork

queue = Queue()
socket_proc = Process(target=pushSocket, args=(queue,))
work_proc = Process(target=pushWork, args=(queue,))

socket_proc.start()
work_proc.start()

