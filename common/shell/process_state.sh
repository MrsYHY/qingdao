#!/bin/bash
# 不要在window下编辑此文件，以免linux不识别
# 
# 可以查看该进程的CPU占用率、内存使用情况和该进程的基本信息

# author kouga-huang
# since 2016-03-29
# param $1 进程关键词 该关键词能够唯一标识该进程
# param $2 checkContent 检测的条件 cpu:cpu占用率 mem:内存占用率 info:其他基本信息

if [ $# -ne 2 ]
then
    echo "the params count error"
    exit 2
fi


currentPath=$(cd `dirname $0`; pwd) #当前路径 

#加载自定义库函数
source $currentPath"/function.lib"

filter="$1"

isExist=$(isExistForProcess "$filter")
if [ $? == 0 ]
then
    cPid=`ps -ef|grep "$filter"|grep -v grep|grep -v "process_state.sh"|awk '{print $2}'`
    case $2 in
    "cpu")
        checkCPU $cPid
        exit 0
        ;;
    "mem")
        checkMEM $cPid
        exit 0
        ;;
    "info")
        proInfo $cPid
        exit 0
        ;;
         *)
        echo "params error"
        exit 2
        ;;
    esac  
elif [ $? == 1 ]
then
    echo "process keyword id $filter and it is  not exists"
    exit 1
fi
exit 2

