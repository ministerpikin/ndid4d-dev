# Troubleshooting

## Changes made to the app is not visible even after restarting all containers

**Error**  
This occurs when you pull changes from GitHub and the changes are not accessible via the web server.  
It's usually because the Docker volume & Docker image have already been built, often after changing the device path in the `volumes` section of the `docker-compose.yml` file.

**Solution**  
Stop all containers.  
List and delete affected images:

```
sudo docker image ls
sudo docker image rm [name of the image to be deleted]
```

List and delete affected volumes:

```
sudo docker volume ls
sudo docker volume rm [name of the volume to be deleted]
```

## App is not reachable (404 error or “This Site Can’t Be Reached”)

1. Check disk space:  
   `df -h`
2. Check memory and CPU usage:  
   `htop`
3. Confirm Docker containers are active and running:  
   `sudo docker ps -a`
4. Check firewall rules:  
   `sudo ufw status`
5. Try accessing via IP directly (rule out DNS issues).
6. Restart containers:  
   `sudo docker-compose down`  
   `sudo docker-compose up -d`

## App is slow

1. Check disk space:  
   `df -h`
2. Check memory/CPU usage:  
   `htop`
3. Review PHP logs:  
   `docker logs backend`
4. Confirm MySQL container status:  
   `sudo docker ps -a | grep mysql`
5. Check MySQL for slow queries inside the container:  
   `sudo docker exec -it configdb mysql -u root -p -e "SHOW FULL PROCESSLIST;"`
7. Check frontend performance (browser dev tools).
8. Restart services or consider rebooting.

## User is unable to login

1. Determine if issue affects:

   - A single user
   - All users

2. If single user:  
   - Ask admin to reset the password.
3. If all users:

   - Test from local network.
   - Ensure login endpoint is reachable.
   - Review PHP logs:  
     `docker logs backend`
   - Confirm MySQL container is running and users table is accessible:  
     `sudo docker exec -it configdb mysql -u root -p -e "SELECT COUNT(*) FROM dbname.users;"`

4. Reboot the server, start the containers and enable supervisor processes:
   `sudo reboot`
   `sudo sudo docker-compose up -d`
   `sudo supervisorctl start all`

---

## App is slow

1. Check disk space:

   `df -h`
2. Check memory/CPU usage:

   `htop`
3. Review PHP logs:

   `docker logs backend`
4. Confirm MySQL status:

   `systemctl status mysql`
5. Check MySQL for slow queries:

   `SHOW FULL PROCESSLIST;`
   
6. Check frontend performance (browser dev tools).
7. Restart services or consider rebooting.

---

## Emails are not being delivered

1. Verify SMTP credentials in system settings.
2. Check SMTP server reachability:
   `ping smtp.example.com`
   `telnet smtp.example.com 587`
3. Review email logs for errors.
4. Ensure the email service is running.

---

## Technical team cannot update the solution

1. Check GitHub reachability
   `ping github.com`
2. Contact network team if blocked.
3. Confirm Git is installed:
   `git --version`
4. Pull updates from main branch:
   `git pull origin m-and-e`

## User is unable to login
1. Determine if issue affects:
   - A single user
   - All users
2. If single user:
   - Check if the user is using the correct credentials.
   - Check if the user is locked out or disabled.
   - Ask admin to reset the password.
   - Recreate the user account if necessary.
   
3. If all users:
   - Test from local network.
   - Ensure login endpoint is reachable.
   - Review PHP logs:
     `docker logs backend`
   - Confirm MySQL container is running and users table is accessible:

     `sudo docker exec -it configdb mysql -u root -p -e "SELECT COUNT(*) FROM dbname.users;"`
4. Reboot the server, start the containers and enable supervisor processes:
   `sudo reboot`
   `sudo docker-compose up -d`
   `sudo supervisorctl start all`


---

## Invalid Report Values

1. Determine if issue affects:

   - A single user
   - All users

2. If single user:
   - Ask user to validate the queries for the specific report.
3. If all users:
   - Test the specific report values using a superadmin account.
   - Ensure queries are valid and cross reference with endpoint call logs if applicable.
   - Purge Endpoint Call request and recall
   - Escalate to the development team if necessary.

---

## Failing Endpoint Calls

0. Ensure the supervisor process is running:
   `sudo supervisorctl status`
1. Ensure the RabbitMQ service is running:
   `sudo docker ps -a | grep rabbitmq`
2. Check the RabbitMQ Container for errors:
   `sudo docker logs rabbitmq`
3. Ensure the third party endpoint API is online.
4. Verify and Validate Endpoint Configurations (Authentication, Body, Headers) on the MIS.
5. Clear Endpoint Cache (if applicable) and retry the call.
6. Check Endpoint Call logs for errors:

## How to update the system

1. Enable maintenance mode.
2. Backup the database and codebase.
3. Ping GitHub:
   `ping github.com`
4. Pull the latest commit:
   `git pull origin main`
5. If SQL scripts are included:
   - Navigate to:
     `cd ~/m-and-e/ndid4d-dev/`
   - Execute:
      `sudo docker exec -i configdb mysql -u root -p id4d_metadata < a.sql`

6. If there are any errors during execution:
   - Review the error messages for guidance.
   - Check the SQL script for syntax issues.
   - Ensure the database user has the necessary permissions.
