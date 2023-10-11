#############################################################
# Init Cassandra
#############################################################

sleep 30s
echo "Creating keyspace fizoaibackend ..."
cqlsh cassandra-oai 9042 -u cassandra -p cassandra -e "CREATE KEYSPACE IF NOT EXISTS fizoaibackend WITH replication = {'class': 'SimpleStrategy', 'replication_factor': '1'};"
