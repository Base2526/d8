Database 
# Backup your databases
docker exec -t your-db-container pg_dumpall -c -U postgres > your_dump.sql

# Restore your databases
cat your_dump.sql | docker exec -i your-db-container psql -U postgres
# d8
