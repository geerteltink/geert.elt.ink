---
id: 2014-11-03-howto-update-teamspeak-3
title: Howto Update TeamSpeak 3 on Debian
summary: This is the missing manual for updating TeamSpeak 3 on Debian.
draft: false
public: true
published: 2014-11-03T10:00:00+01:00
modified: 2017-06-15T14:38:00+01:00
tags:
    - TeamSpeak 3
---

## Updating TeamSpeak 3

I've got TeamSpeak installed in `/opt/teamspeak3/server` and its running as teamspeak.

```bash
# Create the user if you don't have one yet
sudo adduser --disabled-login teamspeak

# Stop TeamSpeak
sudo /etc/init.d/teamspeak stop

# Change to the TeamSpeak dir
cd /opt/teamspeak3

# Backup TeamSpeak
sudo tar -czvf /opt/teamspeak3/ts3_backup.tar.gz /opt/teamspeak3/teamspeak3-server_linux-amd64

# Download the latest version
sudo wget http://dl.4players.de/ts/releases/3.0.13.6/teamspeak3-server_linux_amd64-3.0.13.6.tar.bz2

# Unpack the update
sudo tar -xzf teamspeak3-server_linux_amd64-3.0.13.6.tar.gz
sudo tar -xjf teamspeak3-server_linux_amd64-3.0.13.6.tar.bz2
sudo cp -r teamspeak3-server_linux_amd64/. server
sudo chown -R teamspeak:teamspeak server

# Start TeamSpeak:
sudo /etc/init.d/teamspeak start
# Give password when asked for
```

## TeamSpeak 3 debian autostart script

Edit the script `sudo nano /etc/init.d/teamspeak` and add the following content:

```bash
#!/bin/sh
### BEGIN INIT INFO
# Provides:         teamspeak
# Required-Start:   $local_fs $network
# Required-Stop:    $local_fs $network
# Default-Start:    2 3 4 5
# Default-Stop:     0 1 6
# Description:      Teamspeak 3 Server
### END INIT INFO

######################################
# Customize values for your needs: "User"; "DIR"

USER="teamspeak"
DIR="/opt/teamspeak3/server"

###### Teamspeak 3 server start/stop script ######

case "$1" in
start)
su $USER -c "${DIR}/ts3server_startscript.sh start"
;;
stop)
su $USER -c "${DIR}/ts3server_startscript.sh stop"
;;
restart)
su $USER -c "${DIR}/ts3server_startscript.sh restart"
;;
status)
su $USER -c "${DIR}/ts3server_startscript.sh status"
;;
*)
echo "Usage: {start|stop|restart|status}" >&2
exit 1
;;
esac
exit 0
```

Make the script executable and install it:

```bash
sudo chmod +x /etc/init.d/teamspeak
sudo update-rc.d teamspeak defaults
```

### Script commands

- `sudo /etc/init.d/teamspeak start`
- `sudo /etc/init.d/teamspeak stop`
- `sudo /etc/init.d/teamspeak restart`
- `sudo /etc/init.d/teamspeak status`

## Backup or moving the TeamSpeak server

The files that you want to backup or move to another server:

- `licensekey.dat`
- `query_ip_blacklist.txt`
- `query_ip_whitelist.txt`
- `serverkey.dat`
- `ts3server.ini`
- `ts3server.sqlitedb`
- `files`
