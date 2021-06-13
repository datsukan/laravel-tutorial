#!/bin/bash

# ホストマシンのローカルIPアドレスを取得してdocker-composeの環境変数へ追加する
ip=$(hostname -I)
sed -i -e "/^DOCKER_HOST_IP=/s/DOCKER_HOST_IP=.*/DOCKER_HOST_IP=${ip[0]% }/g" .env

exit 0
