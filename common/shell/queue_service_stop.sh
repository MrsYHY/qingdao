#!/bin/bash
# 该脚本不要在window下编辑，免得linux环境不识别
# 此脚本将关闭整个推送系统的服务  分别是消息队列监控进程 和5个等级的消息队列进程、消息延时进程、消息预删除进程、消息归档进程
# 关闭进程均采用软停止
# 脚本与消息队列监控进程 和5个等级的消息队列进程、消息延时进程、消息预删除进程、消息归档进程通信是通过redis key的设计为
# 1级消息队列： push_system:close_queue:one_level
# 2级消息队列： push_system:close_queue:two_level
# 3级消息队列： push_system:close_queue:three_level
# 4级消息队列： push_system:close_queue:four_level
# 5级消息队列： push_system:close_queue:five_level
# 延时消息队列： push_system:close_queue:delayed
# 预删除消息队列： push_system:close_queue:pre_delete
# 归档消息队列： push_system:close_queue:persistent
# 消息监控：     push_system:close_monitor_queue:monitor
#
# author kouga-huang
# since 2016-03-29
#set -x

currentPath=$(cd `dirname $0`; pwd) #当前路径

#加载自定义库函数
source $currentPath"/function.lib"

# 软停止消息监控
$execRedis set "push_system:close_monitor_queue:monitor" "1"

# 软停止1级消息队列
$execRedis set "push_system:close_queue:one_level" "1"

# 软停止2级消息队列
$execRedis set "push_system:close_queue:two_level" "1"

# 软停止3级消息队列
$execRedis set "push_system:close_queue:three_level" "1"

# 软停止4级消息队列
$execRedis set "push_system:close_queue:four_level" "1"

# 软停止5级消息队列
$execRedis set "push_system:close_queue:five_level" "1"

# 软停止延时队列
$execRedis set "push_system:close_queue:delayed" "1"

# 软停止预删除
$execRedis set "push_system:close_queue:pre_delete" "1"

# 软停止归档
$execRedis set "push_system:close_queue:persistent" "1"

