Database 
# Backup your databases
docker exec -t your-db-container pg_dumpall -c -U postgres > your_dump.sql

# Restore your databases
cat your_dump.sql | docker exec -i your-db-container psql -U postgres

# แก้ไขการแสดง datetime ผิดกรณีเรา set from programming >>>>  core / modules / datetime
https://www.drupal.org/project/drupal/issues/2993165#comment-12767598
เข้าไปทีหน้า /admin/config/regional/settings  > TIME ZONES > Users may set their own time zone  > จะแสดงเวลาที่ถูกต้อง
