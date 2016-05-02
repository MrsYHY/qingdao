#!/bin/bash
# 该脚本不要在window下编辑，免得linux环境不识别
# 消息队列监控进程，当发现有消息队列进程意外死亡，那么会尝试重启该进程，并发出邮件警告
# 
# author kouga-huang
# since 2016-03-29
# param $1 睡眠时间
#
#set -x

# 解决由于监控的轮询导致邮件多次的发送
first=1
lastTime=$(date '+%s') 

if [ $# -ne 1 ]
then
    echo "脚本监控参数不合法，需要一个参数"
    exit 1
fi

currentPath=$(cd `dirname $0`; pwd) #当前路径

#加载自定义库函数
source $currentPath"/function.lib"

# 是否首次启动
firstStartOneLevel=1
firstStartTwoLevel=1
firstStartThreeLevel=1
firstStartFourLevel=1
firstStartFiveLevel=1
firstStartDelayed=1
firstStartPreDeleted=1
firstStartPersistent=1

# 是否再监控
isMonitorOneLevel=1
isMonitorTwoLevel=1
isMonitorThreeLevel=1
isMonitorFourLevel=1
isMonitorFiveLevel=1
isMonitorDelayed=1
isMonitorPreDeleted=1
isMonitorPersistent=1


while [ 1 ]
do
    get=$(getFromRedis "push_system:close_monitor_queue:monitor")
    # 当发现该监控进程需要被软停止了 那么就停止该进程
    if [[ $get -eq 1 ]]
    then
        delFromRedis "push_system:close_monitor_queue:monitor"  #删除该标识，防止之后重启监控服务又被软停止
        subject="服务监控进程正常退出提醒"
        content="服务监控进程已被软停止，正常退出！"
        sendEmail $toEmail "$subject" "$content"
        echo "服务监控进程已被软停止，正常退出！"
        exit 0
    fi
    
######################################一级消息队列进程监控######################################################################
    if [[ $isMonitorOneLevel -eq 1 ]]
    then
        one_level=$(isExistForProcess "$startQueue 1")
        if [[ $? -eq 1 ]] #当进程不存在
        then
            if [[ $firstStartOneLevel -eq 1 ]] # 当首次启动
            then
                firstStartOneLevel=0 
                monitor_queue=$(getFromRedis "push_system:monitor_queue:one_level")
                if [[ -n $monitor_queue ]]
                then
                    runResult=$(startQueueCommand 1 1000)
                    if [[ $? -eq 2 ]] #启动失败
                    then
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动1级消息队列进程失败"
                        subject="1级消息队列启动失败提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动1级消息队列进程失败"
                        sendEmail $toEmail "$subject" "$content"
                    else
                        subject="1级消息队列启动成功提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动1级消息队列进程成功"
                        sendEmail $toEmail "$subject" "$content"
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动1级消息队列进程成功"
                    fi
                elif [[ $monitor_queue -eq 1 ]]
                then
                    isMonitorOneLevel=0 #当不再启动的时候，监控进程就不再监控
                    echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动1级消息队列进程"
                    delFromRedis "push_system:monitor_queue:one_level"  #删除该标识，防止监控进程监控混乱
                    subject="1级消息队列不再启动提醒"
                    content="于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动1级消息队列进程"
                    sendEmail $toEmail "$subject" "$content"
                else
                    if [[ $monitor_queue -le $(date '+%s') ]] 
                    then
                        runResult=$(startQueueCommand 1 1000)
                        if [[ $? -eq 2 ]]
                        then
                            echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动1级消息队列进程失败"
                            subject="1级消息队列启动失败提醒"
                            content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动1级消息队列进程失败"
                            sendEmail $toEmail "$subject" "$content"
                        else
                            delFromRedis "push_system:monitor_queue:one_level"  #删除该标识，防止监控进程监控混乱
                            subject="1级消息队列启动成功提醒"
                            content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动1级消息队列进程成功"
                            sendEmail $toEmail "$subject" "$content"
                            echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动1级消息队列进程成功"
                        fi
                    else
                        subject="1级消息队列启动条件未满足提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，1级消息队列进程还没有启动，还不符合启动条件，该重启类型为首次启动"
                        sendEmail $toEmail "$subject" "$content"
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，1级消息队列进程还没有启动，还不符合启动条件，该重启类型为首次启动"
                    fi  
                fi
            else
                 #当不是首次启动
                 monitor_queue=$(getFromRedis "push_system:monitor_queue:one_level")
                 if [[ -n $monitor_queue ]]
                 then
                     runResult=$(startQueueCommand 1 1000)
                     if [[ $? -eq 2 ]]
                     then
                         subject="1级消息队列启动失败提醒"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动1级消息队列进程失败"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动1级消息队列进程失败"
                     else
                         delFromRedis "push_system:monitor_queue:one_level"  #删除该标识，防止监控进程监控混乱
                         subject="1级消息队列尝试启动成功"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动1级消息队列进程成功"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动1级消息队列进程成功"
                     fi
                 elif [[ $monitor_queue -eq 1 ]]
                 then
                     isMonitorOneLevel=0 #当不再启动的时候，监控进程就不再监控 
                     subject="1级消息队列不再启动提醒"
                     content="于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动1级消息队列进程"
                     sendEmail $toEmail "$subject" "$content"
                     delFromRedis "push_system:monitor_queue:one_level"  #删除该标识，防止监控进程监控混乱
                     echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动1级消息队列进程"
                 else
                     if [[ $monitor_queue -le $(date '+%s') ]]
                     then
                         runResult=$(startQueueCommand 1 1000)
                         if [[ $? -eq 2 ]]
                         then
                             subject="1级消息队列启动失败提醒"
                             content="于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动1级消息队列进程失败"
                             sendEmail $toEmail "$subject" "$content"
                             echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动1级消息队列进程失败"
                         else
                             delFromRedis "push_system:monitor_queue:one_level"  #删除该标识，防止监控进程监控混乱 
                             subject="1级消息队列启动成功提醒"
                             content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动1级消息队列进程成功"
                             sendEmail $toEmail "$subject" "$content"
                             echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动1级消息队列进程成功"
                         fi
                     else
                         subject="1级消息队列启动条件未满足提醒"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，1级消息队列进程还没有启动，还不符合启动条件，该重启类型为尝试再启动"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，1级消息队列进程还没有启动，还不符合启动条件，该重启类型为尝试再启动"
                     fi
                 fi
            fi
        fi
    else
        monitor_queue=$(getFromRedis "push_system:monitor_queue:one_level")
        if [[ $monitor_queue -eq -1 ]]
        then
            isMonitorOneLevel=1 #恢复监控
        fi
        echo "于"$(date "+%Y-%m-%d %H:%M:%s")",不在监控1级消息队列进程"
    fi
##########################################################################################################################################

######################################二级消息队列进程监控######################################################################
    if [[ $isMonitorTwoLevel -eq 1 ]]
    then
        two_level=$(isExistForProcess "$startQueue 2")
        if [[ $? -eq 1 ]] #当进程不存在
        then
            if [[ $firstStartTwoLevel -eq 1 ]] # 当首次启动
            then
                firstStartTwoLevel=0
                monitor_queue=$(getFromRedis "push_system:monitor_queue:two_level")
                if [[ -n $monitor_queue ]]
                then
                    runResult=$(startQueueCommand 2 1000)
                    if [[ $? -eq 2 ]] #启动失败
                    then
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动2级消息队列进程失败"
                        subject="2级消息队列启动失败提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动2级消息队列进程失败"
                        sendEmail $toEmail "$subject" "$content"
                    else
                        subject="2级消息队列启动成功提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动2级消息队列进程成功"
                        sendEmail $toEmail "$subject" "$content"
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动2级消息队列进程成功"
                    fi
                elif [[ $monitor_queue -eq 1 ]]
                then
                    isMonitorTwoLevel=0 #当不再启动的时候，监控进程就不再监控
                    echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动2级消息队列进程"
                    delFromRedis "push_system:monitor_queue:two_level"  #删除该标识，防止监控进程监控混乱
                    subject="2级消息队列不再启动提醒"
                    content="于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动2级消息队列进程"
                    sendEmail $toEmail "$subject" "$content"
                else
                    if [[ $monitor_queue -le $(date '+%s') ]]
                    then
                        runResult=$(startQueueCommand 2 1000)
                        if [[ $? -eq 2 ]]
                        then
                            echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动2级消息队列进程失败"
                            subject="2级消息队列启动失败提醒"
                            content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动2级消息队列进程失败"
                            sendEmail $toEmail "$subject" "$content"
                        else
                            delFromRedis "push_system:monitor_queue:two_level"  #删除该标识，防止监控进程监控混乱
                            subject="2级消息队列启动成功提醒"
                            content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动2级消息队列进程成功"
                            sendEmail $toEmail "$subject" "$content"
                            echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动2级消息队列进程成功"
                        fi
                    else
                        subject="2级消息队列启动条件未满足提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，2级消息队列进程还没有启动，还不符合启动条件，该重启类型为首次启动"
                        sendEmail $toEmail "$subject" "$content"
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，2级消息队列进程还没有启动，还不符合启动条件，该重启类型为首次启动"
                    fi 
                fi
            else
                 #当不是首次启动
                 monitor_queue=$(getFromRedis "push_system:monitor_queue:two_level")
                 if [[ -n $monitor_queue ]]
                 then
                     runResult=$(startQueueCommand 2 1000)
                     if [[ $? -eq 2 ]]
                     then
                         subject="2级消息队列启动失败提醒"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动2级消息队列进程失败"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动2级消息队列进程失败"
                     else
                         delFromRedis "push_system:monitor_queue:two_level"  #删除该标识，防止监控进程监控混乱
                         subject="2级消息队列尝试启动成功"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动2级消息队列进程成功"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动2级消息队列进程成功"
                     fi
                 elif [[ $monitor_queue -eq 1 ]]
                 then
                     isMonitorTwoLevel=0 #当不再启动的时候，监控进程就不再监控
                     subject="2级消息队列不再启动提醒"
                     content="于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动2级消息队列进程"
                     sendEmail $toEmail "$subject" "$content"
                     delFromRedis "push_system:monitor_queue:two_level"  #删除该标识，防止监控进程监控混乱
                     echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动2级消息队列进程"
                 else
                     if [[ $monitor_queue -le $(date '+%s') ]]
                     then
                         runResult=$(startQueueCommand 2 1000)
                         if [[ $? -eq 2 ]]
                         then
                             subject="2级消息队列启动失败提醒"
                             content="于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动2级消息队列进程失败"
                             sendEmail $toEmail "$subject" "$content"
                             echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动2级消息队列进程失败"
                         else
                             delFromRedis "push_system:monitor_queue:two_level"  #删除该标识，防止监控进程监控混乱
                             subject="2级消息队列启动成功提醒"
                             content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动2级消息队列进程成功"
                             sendEmail $toEmail "$subject" "$content"
                             echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动2级消息队列进程成功"
                         fi
                     else
                         subject="2级消息队列启动条件未满足提醒"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，2级消息队列进程还没有启动，还不符合启动条件，该重启类型为尝试再启动"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，2级消息队列进程还没有启动，还不符合启动条件，该重启类型为尝试再启动"
                     fi
                 fi
            fi
        fi
    else
        monitor_queue=$(getFromRedis "push_system:monitor_queue:two_level")
        if [[ $monitor_queue -eq -1 ]]
        then
            isMonitorTwoLevel=1 #恢复监控
        fi
        echo "于"$(date "+%Y-%m-%d %H:%M:%s")",不在监控2级消息队列进程"
    fi
##########################################################################################################################################


######################################三级消息队列进程监控######################################################################
    if [[ $isMonitorThreeLevel -eq 1 ]]
    then
        three_level=$(isExistForProcess "$startQueue 3")
        if [[ $? -eq 1 ]] #当进程不存在
        then
            if [[ $firstStartThreeLevel -eq 1 ]] # 当首次启动
            then
                firstStartThreeLevel=0
                monitor_queue=$(getFromRedis "push_system:monitor_queue:three_level")
                if [[ -n $monitor_queue ]]
                then
                    runResult=$(startQueueCommand 3 1000)
                    if [[ $? -eq 2 ]] #启动失败
                    then
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动3级消息队列进程失败"
                        subject="3级消息队列启动失败提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动3级消息队列进程失败"
                        sendEmail $toEmail "$subject" "$content"
                    else
                        subject="3级消息队列启动成功提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动3级消息队列进程成功"
                        sendEmail $toEmail "$subject" "$content"
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动3级消息队列进程成功"
                    fi
                elif [[ $monitor_queue -eq 1 ]]
                then
                    isMonitorThreeLevel=0 #当不再启动的时候，监控进程就不再监控
                    echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动3级消息队列进程"
                    delFromRedis "push_system:monitor_queue:three_level"  #删除该标识，防止监控进程监控混乱
                    subject="3级消息队列不再启动提醒"
                    content="于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动3级消息队列进程"
                    sendEmail $toEmail "$subject" "$content"
                else
                    if [[ $monitor_queue -le $(date '+%s') ]]
                    then
                        runResult=$(startQueueCommand 3 1000)
                        if [[ $? -eq 2 ]]
                        then
                            echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动3级消息队列进程失败"
                            subject="3级消息队列启动失败提醒"
                            content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动3级消息队列进程失败"
                            sendEmail $toEmail "$subject" "$content"
                        else
                            delFromRedis "push_system:monitor_queue:three_level"  #删除该标识，防止监控进程监控混乱
                            subject="3级消息队列启动成功提醒"
                            content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动3级消息队列进程成功"
                            sendEmail $toEmail "$subject" "$content"
                            echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动3级消息队列进程成功"
                        fi
                    else
                        subject="3级消息队列启动条件未满足提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，3级消息队列进程还没有启动，还不符合启动条件，该重启类型为首次启动"
                        sendEmail $toEmail "$subject" "$content"
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，3级消息队列进程还没有启动，还不符合启动条件，该重启类型为首次启动"
                    fi
                fi
            else
                 #当不是首次启动
                 monitor_queue=$(getFromRedis "push_system:monitor_queue:three_level")
                 if [[ -n $monitor_queue ]]
                 then
                     runResult=$(startQueueCommand 3 1000)
                     if [[ $? -eq 2 ]]
                     then
                         subject="3级消息队列启动失败提醒"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动3级消息队列进程失败"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动3级消息队列进程失败"
                     else
                         delFromRedis "push_system:monitor_queue:three_level"  #删除该标识，防止监控进程监控混乱
                         subject="3级消息队列尝试启动成功"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动3级消息队列进程成功"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动3级消息队列进程成功"
                     fi
                 elif [[ $monitor_queue -eq 1 ]]
                 then
                     isMonitorThreeLevel=0 #当不再启动的时候，监控进程就不再监控
                     subject="3级消息队列不再启动提醒"
                     content="于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动3级消息队列进程"
                     sendEmail $toEmail "$subject" "$content"
                     delFromRedis "push_system:monitor_queue:three_level"  #删除该标识，防止监控进程监控混乱
                     echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动3级消息队列进程"
                 else
                     if [[ $monitor_queue -le $(date '+%s') ]]
                     then
                         runResult=$(startQueueCommand 3 1000)
                         if [[ $? -eq 2 ]]
                         then
                             subject="3级消息队列启动失败提醒"
                             content="于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动3级消息队列进程失败"
                             sendEmail $toEmail "$subject" "$content"
                             echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动3级消息队列进程失败"
                         else
                             delFromRedis "push_system:monitor_queue:three_level"  #删除该标识，防止监控进程监控混乱
                             subject="3级消息队列启动成功提醒"
                             content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动3级消息队列进程成功"
                             sendEmail $toEmail "$subject" "$content"
                             echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动3级消息队列进程成功"
                         fi
                     else
                         subject="3级消息队列启动条件未满足提醒"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，3级消息队列进程还没有启动，还不符合启动条件，该重启类型为尝试再启动"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，3级消息队列进程还没有启动，还不符合启动条件，该重启类型为尝试再启动"
                     fi
                 fi
            fi
        fi
    else
        monitor_queue=$(getFromRedis "push_system:monitor_queue:three_level")
        if [[ $monitor_queue -eq -1 ]]
        then
            isMonitorThreeLevel=1 #恢复监控
        fi
        echo "于"$(date "+%Y-%m-%d %H:%M:%s")",不在监控3级消息队列进程"
    fi
##########################################################################################################################################


######################################四级消息队列进程监控######################################################################
    if [[ $isMonitorFourLevel -eq 1 ]]
    then
        four_level=$(isExistForProcess "$startQueue 4")
        if [[ $? -eq 1 ]] #当进程不存在
        then
            if [[ $firstStartFourLevel -eq 1 ]] # 当首次启动
            then
                firstStartFourLevel=0
                monitor_queue=$(getFromRedis "push_system:monitor_queue:four_level")
                if [[ -n $monitor_queue ]]
                then
                    runResult=$(startQueueCommand 4 1000)
                    if [[ $? -eq 2 ]] #启动失败
                    then
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动4级消息队列进程失败"
                        subject="4级消息队列启动失败提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动4级消息队列进程失败"
                        sendEmail $toEmail "$subject" "$content"
                    else
                        subject="4级消息队列启动成功提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动4级消息队列进程成功"
                        sendEmail $toEmail "$subject" "$content"
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动4级消息队列进程成功"
                    fi
                elif [[ $monitor_queue -eq 1 ]]
                then
                    isMonitorFourLevel=0 #当不再启动的时候，监控进程就不再监控
                    echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动4级消息队列进程"
                    delFromRedis "push_system:monitor_queue:four_level"  #删除该标识，防止监控进程监控混乱
                    subject="4级消息队列不再启动提醒"
                    content="于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动4级消息队列进程"
                    sendEmail $toEmail "$subject" "$content"
                else
                    if [[ $monitor_queue -le $(date '+%s') ]]
                    then
                        runResult=$(startQueueCommand 4 1000)
                        if [[ $? -eq 2 ]]
                        then
                            echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动4级消息队列进程失败"
                            subject="4级消息队列启动失败提醒"
                            content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动4级消息队列进程失败"
                            sendEmail $toEmail "$subject" "$content"
                        else
                            delFromRedis "push_system:monitor_queue:four_level"  #删除该标识，防止监控进程监控混乱
                            subject="4级消息队列启动成功提醒"
                            content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动4级消息队列进程成功"
                            sendEmail $toEmail "$subject" "$content"
                            echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动4级消息队列进程成功"
                        fi
                    else
                        subject="4级消息队列启动条件未满足提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，4级消息队列进程还没有启动，还不符合启动条件，该重启类型为首次启动"
                        sendEmail $toEmail "$subject" "$content"
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，4级消息队列进程还没有启动，还不符合启动条件，该重启类型为首次启动"
                    fi
                fi
            else
                 #当不是首次启动
                 monitor_queue=$(getFromRedis "push_system:monitor_queue:four_level")
                 if [[ -n $monitor_queue ]]
                 then
                     runResult=$(startQueueCommand 4 1000)
                     if [[ $? -eq 2 ]]
                     then
                         subject="4级消息队列启动失败提醒"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动4级消息队列进程失败"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动4级消息队列进程失败"
                     else
                         delFromRedis "push_system:monitor_queue:four_level"  #删除该标识，防止监控进程监控混乱
                         subject="4级消息队列尝试启动成功"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动4级消息队列进程成功"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动4级消息队列进程成功"
                     fi
                 elif [[ $monitor_queue -eq 1 ]]
                 then
                     isMonitorFourLevel=0 #当不再启动的时候，监控进程就不再监控
                     subject="4级消息队列不再启动提醒"
                     content="于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动4级消息队列进程"
                     sendEmail $toEmail "$subject" "$content"
                     delFromRedis "push_system:monitor_queue:four_level"  #删除该标识，防止监控进程监控混乱
                     echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动4级消息队列进程"
                 else
                     if [[ $monitor_queue -le $(date '+%s') ]]
                     then
                         runResult=$(startQueueCommand 4 1000)
                         if [[ $? -eq 2 ]]
                         then
                             subject="4级消息队列启动失败提醒"
                             content="于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动4级消息队列进程失败"
                             sendEmail $toEmail "$subject" "$content"
                             echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动4级消息队列进程失败"
                         else
                             delFromRedis "push_system:monitor_queue:four_level"  #删除该标识，防止监控进程监控混乱
                             subject="4级消息队列启动成功提醒"
                             content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动4级消息队列进程成功"
                             sendEmail $toEmail "$subject" "$content"
                             echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动4级消息队列进程成功"
                         fi
                     else
                         subject="4级消息队列启动条件未满足提醒"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，4级消息队列进程还没有启动，还不符合启动条件，该重启类型为尝试再启动"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，4级消息队列进程还没有启动，还不符合启动条件，该重启类型为尝试再启动"
                     fi
                 fi
            fi
        fi
    else
        monitor_queue=$(getFromRedis "push_system:monitor_queue:four_level")
        if [[ $monitor_queue -eq -1 ]]
        then
            isMonitorFourLevel=1 #恢复监控
        fi
        echo "于"$(date "+%Y-%m-%d %H:%M:%s")",不在监控4级消息队列进程"
    fi
##########################################################################################################################################


######################################五级消息队列进程监控######################################################################
    if [[ $isMonitorFiveLevel -eq 1 ]]
    then
        five_level=$(isExistForProcess "$startQueue 5")
        if [[ $? -eq 1 ]] #当进程不存在
        then
            if [[ $firstStartFiveLevel -eq 1 ]] # 当首次启动
            then
                firstStartFiveLevel=0
                monitor_queue=$(getFromRedis "push_system:monitor_queue:five_level")
                if [[ -n $monitor_queue ]]
                then
                    runResult=$(startQueueCommand 5 1000)
                    if [[ $? -eq 2 ]] #启动失败
                    then
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动5级消息队列进程失败"
                        subject="5级消息队列启动失败提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动5级消息队列进程失败"
                        sendEmail $toEmail "$subject" "$content"
                    else
                        subject="5级消息队列启动成功提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动5级消息队列进程成功"
                        sendEmail $toEmail "$subject" "$content"
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动5级消息队列进程成功"
                    fi
                elif [[ $monitor_queue -eq 1 ]]
                then
                    isMonitorFiveLevel=0 #当不再启动的时候，监控进程就不再监控
                    echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动5级消息队列进程"
                    delFromRedis "push_system:monitor_queue:five_level"  #删除该标识，防止监控进程监控混乱
                    subject="5级消息队列不再启动提醒"
                    content="于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动5级消息队列进程"
                    sendEmail $toEmail "$subject" "$content"
                else
                    if [[ $monitor_queue -le $(date '+%s') ]]
                    then
                        runResult=$(startQueueCommand 5 1000)
                        if [[ $? -eq 2 ]]
                        then
                            echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动5级消息队列进程失败"
                            subject="5级消息队列启动失败提醒"
                            content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动5级消息队列进程失败"
                            sendEmail $toEmail "$subject" "$content"
                        else
                            delFromRedis "push_system:monitor_queue:five_level"  #删除该标识，防止监控进程监控混乱
                            subject="5级消息队列启动成功提醒"
                            content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动5级消息队列进程成功"
                            sendEmail $toEmail "$subject" "$content"
                            echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动5级消息队列进程成功"
                        fi
                    else
                        subject="5级消息队列启动条件未满足提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，5级消息队列进程还没有启动，还不符合启动条件，该重启类型为首次启动"
                        sendEmail $toEmail "$subject" "$content"
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，5级消息队列进程还没有启动，还不符合启动条件，该重启类型为首次启动"
                    fi
                fi
            else
                 #当不是首次启动
                 monitor_queue=$(getFromRedis "push_system:monitor_queue:five_level")
                 if [[ -n $monitor_queue ]]
                 then
                     runResult=$(startQueueCommand 5 1000)
                     if [[ $? -eq 2 ]]
                     then
                         subject="5级消息队列启动失败提醒"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动5级消息队列进程失败"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动5级消息队列进程失败"
                     else
                         delFromRedis "push_system:monitor_queue:five_level"  #删除该标识，防止监控进程监控混乱
                         subject="5级消息队列尝试启动成功"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动5级消息队列进程成功"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动5级消息队列进程成功"
                     fi
                 elif [[ $monitor_queue -eq 1 ]]
                 then
                     isMonitorFiveLevel=0 #当不再启动的时候，监控进程就不再监控
                     subject="5级消息队列不再启动提醒"
                     content="于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动5级消息队列进程"
                     sendEmail $toEmail "$subject" "$content"
                     delFromRedis "push_system:monitor_queue:five_level"  #删除该标识，防止监控进程监控混乱
                     echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动5级消息队列进程"
                 else
                     if [[ $monitor_queue -le $(date '+%s') ]]
                     then
                         runResult=$(startQueueCommand 5 1000)
                         if [[ $? -eq 2 ]]
                         then
                             subject="5级消息队列启动失败提醒"
                             content="于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动5级消息队列进程失败"
                             sendEmail $toEmail "$subject" "$content"
                             echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动5级消息队列进程失败"
                         else
                             delFromRedis "push_system:monitor_queue:five_level"  #删除该标识，防止监控进程监控混乱
                             subject="5级消息队列启动成功提醒"
                             content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动5级消息队列进程成功"
                             sendEmail $toEmail "$subject" "$content"
                             echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动5级消息队列进程成功"
                         fi
                     else
                         subject="5级消息队列启动条件未满足提醒"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，5级消息队列进程还没有启动，还不符合启动条件，该重启类型为尝试再启动"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，5级消息队列进程还没有启动，还不符合启动条件，该重启类型为尝试再启动"
                     fi
                 fi
            fi
        fi
    else
        monitor_queue=$(getFromRedis "push_system:monitor_queue:five_level")
        if [[ $monitor_queue -eq -1 ]]
        then
            isMonitorFiveLevel=1 #恢复监控
        fi
        echo "于"$(date "+%Y-%m-%d %H:%M:%s")",不在监控5级消息队列进程"
    fi
##########################################################################################################################################


######################################延时推送进程监控######################################################################
    if [[ $isMonitorDelayed -eq 1 ]]
    then
        delayed=$(isExistForProcess "$startDelayQueue")
        if [[ $? -eq 1 ]] #当进程不存在
        then
            if [[ $firstStartDelayed -eq 1 ]] # 当首次启动
            then
                firstStartDelayed=0
                monitor_queue=$(getFromRedis "push_system:monitor_queue:delayed")
                if [[ -n $monitor_queue ]]
                then
                    runResult=$(startDelayCommand 1000)
                    if [[ $? -eq 2 ]] #启动失败
                    then
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动延时推送消息队列进程失败"
                        subject="延时推送消息队列启动失败提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动延时推送消息队列进程失败"
                        sendEmail $toEmail "$subject" "$content"
                    else
                        subject="延时推送消息队列启动成功提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动延时推送消息队列进程成功"
                        sendEmail $toEmail "$subject" "$content"
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动延时推送消息队列进程成功"
                    fi
                elif [[ $monitor_queue -eq 1 ]]
                then
                    isMonitorDelayed=0 #当不再启动的时候，监控进程就不再监控
                    echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动延时推送消息队列进程"
                    delFromRedis "push_system:monitor_queue:delayed"  #删除该标识，防止监控进程监控混乱
                    subject="延时推送消息队列不再启动提醒"
                    content="于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动延时推送消息队列进程"
                    sendEmail $toEmail "$subject" "$content"
                else
                    if [[ $monitor_queue -le $(date '+%s') ]]
                    then
                        runResult=$(startDelayCommand 1000)
                        if [[ $? -eq 2 ]]
                        then
                            echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动延时推送消息队列进程失败"
                            subject="延时推送消息队列启动失败提醒"
                            content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动延时推送消息队列进程失败"
                            sendEmail $toEmail "$subject" "$content"
                        else
                            delFromRedis "push_system:monitor_queue:delayed"  #删除该标识，防止监控进程监控混乱
                            subject="延时推送消息队列启动成功提醒"
                            content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动延时推送消息队列进程成功"
                            sendEmail $toEmail "$subject" "$content"
                            echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动延时推送消息队列进程成功"
                        fi
                    else
                        subject="延时推送消息队列启动条件未满足提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，延时推送消息队列进程还没有启动，还不符合启动条件，该重启类型为首次启动"
                        sendEmail $toEmail "$subject" "$content"
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，延时推送消息队列进程还没有启动，还不符合启动条件，该重启类型为首次启动"
                    fi
                fi
            else
                 #当不是首次启动
                 monitor_queue=$(getFromRedis "push_system:monitor_queue:delayed")
                 if [[ -n $monitor_queue ]]
                 then
                     runResult=$(startDelayCommand 1000)
                     if [[ $? -eq 2 ]]
                     then
                         subject="延时推送消息队列启动失败提醒"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动延时推送消息队列进程失败"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动延时推送消息队列进程失败"
                     else
                         delFromRedis "push_system:monitor_queue:delayed"  #删除该标识，防止监控进程监控混乱
                         subject="延时推送消息队列尝试启动成功"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动延时推送消息队列进程成功"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动延时推送消息队列进程成功"
                     fi
                 elif [[ $monitor_queue -eq 1 ]]
                 then
                     isMonitorDelayed=0 #当不再启动的时候，监控进程就不再监控
                     subject="延时推送消息队列不再启动提醒"
                     content="于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动延时推送消息队列进程"
                     sendEmail $toEmail "$subject" "$content"
                     delFromRedis "push_system:monitor_queue:delayed"  #删除该标识，防止监控进程监控混乱
                     echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动延时推送消息队列进程"
                 else
                     if [[ $monitor_queue -le $(date '+%s') ]]
                     then
                         runResult=$(startDelayCommand 1000)
                         if [[ $? -eq 2 ]]
                         then
                             subject="延时推送消息队列启动失败提醒"
                             content="于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动延时推送消息队列进程失败"
                             sendEmail $toEmail "$subject" "$content"
                             echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动延时推送消息队列进程失败"
                         else
                             delFromRedis "push_system:monitor_queue:delayed"  #删除该标识，防止监控进程监控混乱
                             subject="延时推送消息队列启动成功提醒"
                             content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动延时推送消息队列进程成功"
                             sendEmail $toEmail "$subject" "$content"
                             echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动延时推送消息队列进程成功"
                         fi
                     else
                         subject="延时推送消息队列启动条件未满足提醒"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，延时推送消息队列进程还没有启动，还不符合启动条件，该重启类型为尝试再启动"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，延时推送消息队列进程还没有启动，还不符合启动条件，该重启类型为尝试再启动"
                     fi
                 fi
            fi
        fi
    else
        monitor_queue=$(getFromRedis "push_system:monitor_queue:delayed")
        if [[ $monitor_queue -eq -1 ]]
        then
            isMonitorDelayed=1 #恢复监控
        fi
        echo "于"$(date "+%Y-%m-%d %H:%M:%s")",不在监控延时推送消息队列进程"
    fi
##########################################################################################################################################


######################################预删除队列进程监控######################################################################
    if [[ $isMonitorPreDeleted -eq 1 ]]
    then
        delete=$(isExistForProcess "$startPreDeleteQueue")
        if [[ $? -eq 1 ]] #当进程不存在
        then
            if [[ $firstStartPreDeleted -eq 1 ]] # 当首次启动  
            then
                firstStartPreDeleted=0
                monitor_queue=$(getFromRedis "push_system:monitor_queue:predelete")
                if [[ -n $monitor_queue ]]
                then
                    runResult=$(startDeleteCommand 1000)
                    if [[ $? -eq 2 ]] #启动失败
                    then
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动预删除消息队列进程失败"
                        subject="预删除消息队列启动失败提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动删除消息队列进程失败"
                        sendEmail $toEmail "$subject" "$content"
                    else
                        subject="预删除消息队列启动成功提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动延时推送消息队列进程成功"
                        sendEmail $toEmail "$subject" "$content"
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动删除消息队列进程成功"
                    fi
                elif [[ $monitor_queue -eq 1 ]]
                then
                    isMonitorPreDeleted=0 #当不再启动的时候，监控进程就不再监控  
                    echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动预删除消息队列进程"
                    delFromRedis "push_system:monitor_queue:predelete"  #删除该标识，防止监控进程监控混乱   
                    subject="预删除消息队列不再启动提醒"
                    content="于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动预删除消息队列进程"
                    sendEmail $toEmail "$subject" "$content"
                else
                    if [[ $monitor_queue -le $(date '+%s') ]]
                    then
                        runResult=$(startDeleteCommand 1000)
                        if [[ $? -eq 2 ]]
                        then
                            echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动预删除消息队列进程失败"
                            subject="预删除消息队列启动失败提醒"
                            content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动预删除消息队列进程失败"
                            sendEmail $toEmail "$subject" "$content"
                        else
                            delFromRedis "push_system:monitor_queue:predelete"  #删除该标识，防止监控进程监控混乱   
                            subject="预删除消息队列启动成功提醒"
                            content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动预删除消息队列进程成功"
                            sendEmail $toEmail "$subject" "$content"
                            echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动预删除消息队列进程成功"
                        fi
                    else
                        subject="预删除消息队列启动条件未满足提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，预删除消息队列进程还没有启动，还不符合启动条件，该重启类型为首次启动"
                        sendEmail $toEmail "$subject" "$content"
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，预删除消息队列进程还没有启动，还不符合启动条件，该重启类型为首次启动"
                    fi
                fi
            else
                 #当不是首次启动
                 monitor_queue=$(getFromRedis "push_system:monitor_queue:predelete")
                 if [[ -n $monitor_queue ]]
                 then
                     runResult=$(startDeleteCommand 1000)
                     if [[ $? -eq 2 ]]
                     then
                         subject="预删除消息队列启动失败提醒"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动预删除消息队列进程失败"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动预删除消息队列进程失败"
                     else
                         delFromRedis "push_system:monitor_queue:predelete"  #删除该标识，防止监控进程监控混乱   
                         subject="预删除消息队列尝试启动成功"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动预删除消息队列进程成功"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动预删除消息队列进程成功"
                     fi
                 elif [[ $monitor_queue -eq 1 ]]
                 then
                     isMonitorPreDeleted=0 #当不再启动的时候，监控进程就不再监控  
                     subject="预删除消息队列不再启动提醒"
                     content="于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动预删除消息队列进程"
                     sendEmail $toEmail "$subject" "$content"
                     delFromRedis "push_system:monitor_queue:predelete"  #删除该标识，防止监控进程监控混乱   
                     echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动预删除消息队列进程"
                 else
                     if [[ $monitor_queue -le $(date '+%s') ]]
                     then
                         runResult=$(startDeleteCommand 1000)
                         if [[ $? -eq 2 ]]
                         then
                             subject="预删除消息队列启动失败提醒"
                             content="于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动预删除消息队列进程失败"
                             sendEmail $toEmail "$subject" "$content"
                             echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动预删除消息队列进程失败"
                         else
                             delFromRedis "push_system:monitor_queue:predelete"  #删除该标识，防止监控进程监控混乱   
                             subject="消息队列启动成功提醒"
                             content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动预删除消息队列进程成功"
                             sendEmail $toEmail "$subject" "$content"
                             echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动预删除消息队列进程成功"
                         fi
                     else
                         subject="预删除消息队列启动条件未满足提醒"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，预删除消息队列进程还没有启动，还不符合启动条件，该重启类型为尝试再启动"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，预删除消息队列进程还没有启动，还不符合启动条件，该重启类型为尝试再启动"
                     fi
                 fi
            fi
        fi
    else
        monitor_queue=$(getFromRedis "push_system:monitor_queue:predelete")
        if [[ $monitor_queue -eq -1 ]]
        then
            isMonitorPreDeleted=1 #恢复监控  
        fi
        echo "于"$(date "+%Y-%m-%d %H:%M:%s")",不在监控预删除消息队列进程"
    fi
##########################################################################################################################################

######################################归档进程监控######################################################################
    if [[ $isMonitorPersistent -eq 1 ]]
    then
        delayed=$(isExistForProcess "$startPersistent")
        if [[ $? -eq 1 ]] #当进程不存在
        then
            if [[ $firstStartPersistent -eq 1 ]] # 当首次启动  
            then
                firstStartPersistent=0
                monitor_queue=$(getFromRedis "push_system:monitor_queue:persistent")
                if [[ -n $monitor_queue ]]
                then
                    runResult=$(startPerSistentCommand 1000)
                    if [[ $? -eq 2 ]] #启动失败
                    then
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动归档消息进程失败"
                        subject="归档消息队列启动失败提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动归档消息进程失败"
                        sendEmail $toEmail "$subject" "$content"
                    else
                        subject="归档消息队列启动成功提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动归档消息队列进程成功"
                        sendEmail $toEmail "$subject" "$content"
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动归档消息队列进程成功"
                    fi
                elif [[ $monitor_queue -eq 1 ]]
                then
                    isMonitorPersistent=0 #当不再启动的时候，监控进程就不再监控  
                    echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动归档消息队列进程"
                    delFromRedis "push_system:monitor_queue:persistent"  #删除该标识，防止监控进程监控混乱   
                    subject="归档消息队列不再启动提醒"
                    content="于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动归档消息队列进程"
                    sendEmail $toEmail "$subject" "$content"
                else
                    if [[ $monitor_queue -le $(date '+%s') ]]
                    then
                        runResult=$(startPerSistentCommand 1000)
                        if [[ $? -eq 2 ]]
                        then
                            echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动归档消息队列进程失败"
                            subject="归档消息队列启动失败提醒"
                            content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动归档消息队列进程失败"
                            sendEmail $toEmail "$subject" "$content"
                        else
                            delFromRedis "push_system:monitor_queue:persistent"  #删除该标识，防止监控进程监控混乱   
                            subject="归档消息队列启动成功提醒"
                            content="于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动归档消息队列进程成功"
                            sendEmail $toEmail "$subject" "$content"
                            echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，首次启动归档消息队列进程成功"
                        fi
                    else
                        subject="归档消息队列启动条件未满足提醒"
                        content="于"$(date "+%Y-%m-%d %H:%M:%s")"，归档消息队列进程还没有启动，还不符合启动条件，该重启类型为首次启动"
                        sendEmail $toEmail "$subject" "$content"
                        echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，归档消息队列进程还没有启动，还不符合启动条件，该重启类型为首次启动"
                    fi
                fi
            else
                 #当不是首次启动
                 monitor_queue=$(getFromRedis "push_system:monitor_queue:persistent")
                 if [[ -n $monitor_queue ]]
                 then
                     runResult=$(startPerSistentCommand 1000)
                     if [[ $? -eq 2 ]]
                     then
                         subject="归档消息队列启动失败提醒"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动归档消息队列进程失败"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动归档消息队列进程失败"
                     else
                         delFromRedis "push_system:monitor_queue:persistent"  #删除该标识，防止监控进程监控混乱   
                         subject="归档消息队列尝试启动成功"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动归档消息队列进程成功"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动归档消息队列进程成功"
                     fi
                 elif [[ $monitor_queue -eq 1 ]]
                 then
                     isMonitorPersistent=0 #当不再启动的时候，监控进程就不再监控  
                     subject="归档消息队列不再启动提醒"
                     content="于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动归档消息队列进程"
                     sendEmail $toEmail "$subject" "$content"
                     delFromRedis "push_system:monitor_queue:persistent"  #删除该标识，防止监控进程监控混乱   
                     echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，不再启动归档消息队列进程"
                 else
                     if [[ $monitor_queue -le $(date '+%s') ]]
                     then
                         runResult=$(startPerSistentCommand 1000)
                         if [[ $? -eq 2 ]]
                         then
                             subject="归档消息队列启动失败提醒"
                             content="于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动归档消息队列进程失败"
                             sendEmail $toEmail "$subject" "$content"
                             echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动归档消息队列进程失败"
                         else
                             delFromRedis "push_system:monitor_queue:persistent"  #删除该标识，防止监控进程监控混乱   
                             subject="归档消息队列启动成功提醒"
                             content="于"$(date "+%Y-%m-%d %H:%M:%s")"，再次尝试启动归档消息队列进程成功"
                             sendEmail $toEmail "$subject" "$content"
                             echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，尝试再次启动归档消息队列进程成功"
                         fi
                     else
                         subject="归档消息队列启动条件未满足提醒"
                         content="于"$(date "+%Y-%m-%d %H:%M:%s")"，归档消息队列进程还没有启动，还不符合启动条件，该重启类型为尝试再启动"
                         sendEmail $toEmail "$subject" "$content"
                         echo "于"$(date "+%Y-%m-%d %H:%M:%s")"，归档消息队列进程还没有启动，还不符合启动条件，该重启类型为尝试再启动"
                     fi
                 fi
            fi
        fi
    else
        monitor_queue=$(getFromRedis "push_system:monitor_queue:persistent")
        if [[ $monitor_queue -eq -1 ]]
        then
            isMonitorPersistent=1 #恢复监控  
        fi
        echo "于"$(date "+%Y-%m-%d %H:%M:%s")",不在监控归档消息队列进程"
    fi
##########################################################################################################################################

    sleep $1  #监控睡眠时间
done
exit 1
