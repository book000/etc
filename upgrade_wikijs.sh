#!/bin/bash

# Wiki.js upgrader
# Install location dir: wikijs/
# systemd: wikijs

cd `dirname $0`
JSON=`curl -s https://api.github.com/repos/Requarks/wiki/releases/latest`

systemctl stop wikijs

DOWNLOAD_URL=`echo $JSON | jq -r '.assets[] | select(.name == "wiki-js.tar.gz") | .browser_download_url'`
curl -OL $DOWNLOAD_URL

if [ $? -ne 0 ]; then
    echo "Latest file download failed!"
    exit 1
fi

TAG_NAME=`echo $JSON | jq -r '.tag_name'`
tar cvf backup-$TAG_NAME.tar.gz wikijs/

cp -v wikijs/config.yml config.yml
cp -v wikijs/database.sqlite database.sqlite

rm -rfv wikijs/*

tar xzfv wiki-js.tar.gz -C ./wikijs

rm wiki-js.tar.gz

cp -v config.yml wikijs/config.yml
cp -v database.sqlite wikijs/database.sqlite

systemctl start wikijs

echo Upgrade successful: $TAG_NAME
