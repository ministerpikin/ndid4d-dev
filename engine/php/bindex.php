<?php
	if( is_array( $argv ) && ! empty( $argv ) ){
		foreach( $argv as $agk => & $agv ){
			$agv = str_replace("'","", $agv );
		}
	}
	//print_r( $argv ); exit;
	//echo 'speed'; exit;
	
	$dev_mode = 0;
	if( isset( $argv[2] ) && $argv[2] == 'hyella_development' ){
		$dev_mode = 1;
	}
	if( $dev_mode ){
		echo '42docEDMS';
	}
	/*
	sudo apt install imagemagick
	sudo apt install php-imagick
	sudo systemctl restart apache2
	php -m | grep imagick
	php -r 'phpinfo();' | grep imagick
	
	sudo apt update
	sudo apt install tesseract-ocr
	sudo apt install ghostscript
	
	#https://www.digitalocean.com/community/tutorials/how-to-install-and-configure-elasticsearch-on-ubuntu-18-04
	
	java -version #check if java is installed
	sudo apt install default-jre
	java -version
	
	sudo wget https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-7.16.1-amd64.deb
	sudo dpkg -i elasticsearch-7.16.1-amd64.deb
	
	#To restrict access and therefore increase security, find the line that specifies network.host, uncomment it, and replace its value with localhost so it looks like this:
	
	#https://stackoverflow.com/questions/58656747/elasticsearch-job-for-elasticsearch-service-failed
	sudo nano /etc/elasticsearch/elasticsearch.yml
	###### 
	#Set the bind address to a specific IP (IPv4 or IPv6):
	#
	#network.host: 127.0.0.1
	#
	# Set a custom port for HTTP:
	#
	http.port: 9200
	
	discovery.seed_hosts: []
	
	cluster.initial_master_nodes: ["name_of_your_node_gotten_by_running curl localhost:9200"]
	######
	
	#Next, run the code below to determine the cause of the error:
	journalctl -xe
	
	sudo nano /etc/elasticsearch/jvm.options
	#####
	#First, un-comment the value of Xmx and Xms
	
	#Next, modify the value of -Xms and -Xmx to no more than 50% of your physical RAM. The value for these settings depends on the amount of RAM available on your server and Elasticsearch requires memory for purposes other than the JVM heap and it is important to leave space for this.
	#####
	
	
	
	sudo systemctl enable elasticsearch.service
	sudo systemctl start elasticsearch
	sudo systemctl status elasticsearch
	
	#sudo systemctl start elasticsearch.service
	
	sudo /usr/share/elasticsearch/bin/elasticsearch-plugin install ingest-attachment
	sudo systemctl restart elasticsearch
	
	curl -H'Content-Type: application/json' -XGET localhost:9200/_cat/plugins
	curl -H'Content-Type: application/json' -XGET localhost:9200/test3/plugins
	curl -X GET "localhost:9200/test3/_doc/my_id2?pretty"

	
	curl -XPUT "http://localhost:9200/test_index_2" -H 'Content-Type: application/json' -d'{  "settings": {    "number_of_shards": 2,    "number_of_replicas": 2  },  "mappings": {    "properties": {      "field1": { "type": "object" }    }  }}'
	
	
	#https://www.elastic.co/guide/en/elasticsearch/reference/current/system-config.html
	
	
	cd /var/www/html/fermaedms.com.ng/public_html/engine/plugins/nwp_full_text_search
	sudo apt install curl php-cli php-mbstring php-curl git unzip composer
	
	************
	*TOO_MANY_REQUESTS/12/disk usage exceeded flood-stage watermark, index has read-only-allow-delete block*
	***
	curl -XPUT -H "Content-Type: application/json" http://localhost:9200/_cluster/settings -d '{ "transient": { "cluster.routing.allocation.disk.threshold_enabled": false } }'

	curl -XPUT -H "Content-Type: application/json" http://localhost:9200/_all/_settings -d '{"index.blocks.read_only_allow_delete": null}'
	************
	
	************
	install cron
	sudo apt install cron
 
	#You’ll need to make sure it’s set to run in the background too:
	sudo systemctl enable cron
	
	Cron jobs are recorded and managed in a special file known as a crontab. Each user profile on the system can have their own crontab where they can schedule jobs, which is stored under /var/spool/cron/crontabs/.

	To schedule a job, open up your crontab for editing and add a task written in the form of a cron expression. The syntax for cron expressions can be broken down into two elements: the schedule and the 	command to run.
	
	sudo crontab -e
	
	set php alias for cronjob
	alias phpwww='sudo -u www-data php'
	*/
	//#fermaedms bg service - run every 5 minutes
	//#*/5 * * * * cd /var/www/html/fermaedms.com.ng/public_html/edms/server/ && php -f /var/www/html/fermaedms.com.ng/public_html/edms/server/bservice-noloop.php > /dev/null 2>&1
	
	//not used
	//#*/5 * * * * cd /var/www/html/fermaedms.com.ng/public_html/edms/server/ && phpwww -f /var/www/html/fermaedms.com.ng/public_html/edms/server/bservice-noloop.php > /dev/null 2>&1
	/*
	
	minute hour day_of_month month day_of_week command_to_run
	30 17 * * 2 curl http://www.google.com
	
	/etc/init.d/cron reload
	sudo /etc/init.d/cron restart
	
	load data in file
	https://serverfault.com/questions/153769/mysql-load-data-infile-user-privilege-settings
	sudo nano /etc/apparmor.d/usr.sbin.mysqld
	
	/var/www/mysite/** rw,
	/var/www/html/fermaedms.com.ng/public_html/engine/tmp/** rw,
	
	sudo /etc/init.d/apparmor stop
	sudo /etc/init.d/apparmor start
	************
	
	spliting pdf files for preview
	linux
	gs -dSAFER -dBATCH -dNOPAUSE -sDEVICE=pdfwrite -dFirstPage=1 -dLastPage=5 -sOutputFile=output.pdf input.pdf
	
	windows
	gswin64c -dSAFER -dBATCH -dNOPAUSE -sDEVICE=pdfwrite -dFirstPage=1 -dLastPage=3 -sOutputFile=output.pdf input.pdf
	*/
	//sleep(5);
	//cd "C:\xampp\htdocs\feyi-hyella\engine\vendor\imagemagick\" 
	//convert -density 300 "%3"  -depth 8 -strip -background white -alpha off "%4"
	
	$output=null;
	$retval=null;
	if( isset( $argv[4] ) && $argv[4] && isset( $argv[3] ) && $argv[3] ){
		
		if( file_exists( $argv[4] ) ){
			unlink( $argv[4] );
		}
		exec('convert -density 300 "'.$argv[3].'"  -depth 8 -strip -background white -alpha off "'.$argv[4].'"', $output, $retval);
		
		if( file_exists( $argv[4] ) ){
			//echo filesize( $argv[4] );
			
			if( isset( $argv[5] ) && $argv[5] ){
				if( file_exists( $argv[5] ) ){
					unlink( $argv[5] );
				}
				//cd "C:\Program Files\Tesseract-OCR\"
				//tesseract "%4" "%5" -l eng
				$output=null;
				$retval=null;
				exec('tesseract "'.$argv[4].'" "'.$argv[5].'" -l eng', $output, $retval);
			}
			unlink( $argv[4] );
		}
	}
	if( $dev_mode ){
		print_r( $output );
	}
?>