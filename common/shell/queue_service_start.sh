#!/bin/bash
## 该脚本不要在window下编辑，免得linux环境不识别
#  启动推送服务脚本
#  设计成只启动队列服务监控进程，然后再由队列服务监控进程去启动消息队列进程，其中队列服务监控进程为shell进程；消息队列进程为php进程
# author kouga-huang
# since 2016-03-29
# param $1 睡眠时间
# 

#set -x

currentPath=$(cd `dirname $0`; pwd) #当前路径

#加载自定义库函数
source $currentPath"/function.lib"

# 参数个数判断
if [ $# -ne 1 ]
then
    echo "启动服务脚本参数不合法 需要一个参数"
    exit 3
fi


if [ $(ps -ef | grep "$startQueueMonitor $1"|grep -v grep|wc -l) -eq 0 ] #如果监控进程还没有启动则尝试启动
then
    nohup bash $startQueueMonitor $1 >>$queueMonitorLog 2>>$queueMonitorLog&
    if [ $? -eq 0 ]
    then
        echo "the queue monitor process start successful"
        exit 0
    else
        echo "the queue monitor process start failure"
        exit 1
    fi
else
    echo "the queue monitor process has already exist"
    exit 0
fi



