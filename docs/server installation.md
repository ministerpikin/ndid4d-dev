
* * *

Installing and Setting Up Docker and Supervisor on Ubuntu
============================================================
 Prerequisites
---------------

*   Ubuntu 20.04 or later
    
*   sudo/root privileges
    
*   Internet connection
    

* * *

Step 1: Update Your System
-----------------------------
`sudo apt update && sudo apt upgrade -y`

* * *

Step 2: Install Docker
-------------------------

### 2.1 Uninstall Old Versions (Optional)
`sudo apt remove docker docker-engine docker.io containerd runc`

### 2.2 Set Up the Repository
`sudo apt install ca-certificates curl gnupg lsb-release -y `

```
sudo mkdir -p /etc/apt/keyrings  
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | \
   sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
```

### 2.3 Add Docker Repository
```
echo \ 
        "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] \
        https://download.docker.com/linux/ubuntu \
        $(lsb_release -cs) stable" | \
        sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
```

### 2.4 Install Docker Engine
`sudo apt update `

`sudo apt install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin -y`

### 2.5 Enable and Start Docker
`sudo systemctl enable docker` 

`sudo systemctl start docker`

### 2.6 Test Docker
`sudo docker run hello-world`

* * *

Step 3: Install Supervisor
-----------------------------
`sudo apt install supervisor -y`

### 3.1 Supervisor Directory Structure

*   Configs go in: `/etc/supervisor/conf.d/`
    
*   Main config: `/etc/supervisor/supervisord.conf`

* * *

Verify Installations
-----------------------

*   Docker: `docker --version`
    
*   Supervisor: `supervisord --version`
    
* * *


Step 4: Creating Supervisor Tasks
---------------------------------------------

### 4.1 Background Host Process 
`sudo nano /etc/supervisor/conf.d/bg_process.conf`

```
[program:bg_process]
command=docker exec backend php /var/www/html/engine/php/worker.php
autostart=true
autorestart=true
stderrfile=/var/log/supervisor/bg_process.err.log
stdoutfile=/var/log/supervisor/bg_process.out.log
```

### 4.2 Rabbit MQ Workers 
`sudo nano /etc/supervisor/conf.d/queue_worker_linux.conf`

```
[program:queue_worker_linux]
command=docker exec backend php /var/www/html/engine/plugins/nwp_queue/rabbit-mq/worker-linux.php
process_name=queue_worker-%(process_num)d
numprocs=3
autostart=true
autorestart=true
retry=5
interval=45
stderrfile=/var/log/supervisor/queue_worker/linux-%(process_num)d.err.log
stdoutfile=/var/log/supervisor/queue_worker/linux-%(process_num)d.out.log
```



### 4.2 Reload Supervisor Configs

`sudo supervisorctl reread` 

`sudo supervisorctl update` 


* * *

Optional: Manage Supervisor Services
----------------------------------------
- View Status of supervisor tasks

  `sudo supervisorctl status `

- Start All stopped supervisor tasks
  
  `sudo supervisorctl start all`

- Stop a specific supervisor Task
  
  `sudo supervisorctl stop bg_process`

- Restart a specific supervisor Task

  `sudo supervisorctl restart bg_process`

- Stop All active supervisor tasks
  
  `sudo supervisorctl stop all`
* * *