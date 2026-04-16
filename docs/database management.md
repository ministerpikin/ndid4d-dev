# Database Management

## Introduction
The DB engine is mysql latest version as managed by docker.   
The system uses multiple db's:
 - Local DB: This is pre-installed with docker-compose

**NOTE ALWAYS REPLACE THE BELOW LISTED VARIABLES BEFORE RUNNING ANY CODE**
```
your_username = your db user name
your_password = your db password
your_database = your db name
```

## Installation
**Pre-requisite** Docker Container **configdb** is running  

1. Navigate to docker root directory. NB this is likely to be called m-and-e

2. Run the below command to load the db
```
cd ~ && cd ./m-and-e/
sudo docker exec -i configdb mysql -u"your_username" -p"your_password" "your_database" < ./ndid4d-dev/docs/database/id4d_metadata.sql
```

3. Run the below command to verify db was loaded successfully
```
sudo docker exec -i configdb mysql -u"your_username" -p"your_password" -e "SELECT COLUMN_NAME, EXTRA FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'your_database' AND TABLE_NAME = 'workflow_trail' AND COLUMN_NAME = 'serial_num';"
```

You should get the below result if all is well:  
```
COLUMN_NAME     EXTRA
serial_num      auto_increment
```

## Data Wipe
To wipe all user generated and transactional data run the following command  
```
sudo docker exec -i configdb mysql -u"your_username" -p"your_password" -e "USE your_database; TRUNCATE bulk_message; TRUNCATE bulk_message_group; TRUNCATE bulk_message_group_member; TRUNCATE bulk_message_recepient; TRUNCATE comments; TRUNCATE complaint_items; TRUNCATE dc_reps; TRUNCATE dpco_license; TRUNCATE eosic_business_registration; TRUNCATE eosic_company; TRUNCATE eosic_company_owners; TRUNCATE eosic_contact; TRUNCATE eosic_c_certification; TRUNCATE eosic_directors; TRUNCATE eosic_hints; TRUNCATE eosic_officers; TRUNCATE eosic_payments; TRUNCATE files; TRUNCATE files_movement; TRUNCATE locked_files; TRUNCATE logged_in_users; TRUNCATE nipc_client; TRUNCATE nipc_complaint; TRUNCATE nipc_meeting; TRUNCATE nipc_ticket_cat; TRUNCATE notifications; TRUNCATE pd_description; TRUNCATE revision_history; TRUNCATE share; TRUNCATE vendors; TRUNCATE workflow; TRUNCATE workflow_trail;"

```

## Run multiple queries
You can also run multiple queries contained in a SQL file by  
```
sudo docker exec -i configdb mysql -u"your_username" -p"your_password" -e "source /path/to/your/file.sql;"
```