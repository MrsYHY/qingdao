#!/bin/bash

currentPath=$(cd `dirname $0`; pwd) #当前路径

#加载自定义库函数
source $currentPath"/function.lib"

#sendEmail "158664981@qq.com" "test" "test"


#set=$(setFromRedis "huangjinwei" "huangjinwei")
#echo $set

#get=$(getFromRedis "hu1angjinwei")
#if [ -n $get ]
#then
#    echo "1454"
#fi

#del=$(delFromRedis "huangjinwei")
#echo $del

#del1=$(delFromRedis "df")
#echo $del1


startQueue 1 60


