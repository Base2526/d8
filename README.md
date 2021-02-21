Database 
# Backup your databases
docker exec -t your-db-container pg_dumpall -c -U postgres > your_dump.sql

# Restore your databases
cat your_dump.sql | docker exec -i your-db-container psql -U postgres
# d8

# Server & Client
# https://github.com/Base2526/cfu 

# ReactJs
- Breadcrumb
https://medium.com/@mattywilliams/generating-an-automatic-breadcrumb-in-react-router-fed01af1fc3

- react-flexbox-grid

###################### mongodb ######################
Replica set mongodb
step 1
- docker-compose.yml
mongo1:
    hostname: mongo1
    container_name: localmongo1
    image: mongo:4.0-xenial
    expose:
      - 27017
    volumes:
      - ./mongodb/mongo1:/data/db <<< ต้องสร้าง folder ไว้ก่อนด้วย
    restart: always
    entrypoint: [ "/usr/bin/mongod", "--bind_ip_all", "--replSet", "rs0" ]
mongo2:
    hostname: mongo2
    container_name: localmongo2
    image: mongo:4.0-xenial
    expose:
      - 27017
    volumes:
      - ./mongodb/mongo2:/data/db <<< ต้องสร้าง folder ไว้ก่อนด้วย
    restart: always
    entrypoint: [ "/usr/bin/mongod", "--bind_ip_all", "--replSet", "rs0" ]
mongo3:
    hostname: mongo3
    container_name: localmongo3
    image: mongo:4.0-xenial
    expose:
      - 27017
    volumes:
      - ./mongodb/mongo3:/data/db <<< ต้องสร้าง folder ไว้ก่อนด้วย
    restart: always
    entrypoint: [ "/usr/bin/mongod", "--bind_ip_all", "--replSet", "rs0" ]
mongo-express:
    image: mongo-express
    restart: always
    ports:
      - 9999:8081
    environment:
      ME_CONFIG_MONGODB_ADMINUSERNAME: root
      ME_CONFIG_MONGODB_ADMINPASSWORD: example
      ME_CONFIG_MONGODB_SERVER: mongo1  # กรณีเรา set replset เราต้องกำหนด ME_CONFIG_MONGODB_SERVER ด้วย

step 2
 - exec -ti mongo1 bash
 - mongo
    var cfg = {
        "_id": "rs0",
        "version": 1,
        "members": [
        {
            "_id": 0,
            "host": "mongo1:27017",
            "priority": 2
        },
        {
            "_id": 1,
            "host": "mongo2:27017",
            "priority": 0
        },
        {
            "_id": 2,
            "host": "mongo3:27017",
            "priority": 0
        }
        ]
    };
    rs.initiate(cfg);

step 3
    nodejs connect mongodb

    const connection = 'mongodb://mongo1:27017,mongo2:27017,mongo3:27017/{db}?replicaSet=rs0';
      
    console.log('Mongoose version:', mongoose.version);
    mongoose.set('useUnifiedTopology', true);

    return mongoose.connect(connection, {
                                            useNewUrlParser : true,
                                            useFindAndModify: false, // optional
                                            useCreateIndex  : true,
                                            // replicaSet      : 'rs0', // We use this from the entrypoint in the docker-compose file
                                            // dbName: 'mongo_test'
                                        });
        
step 4
    const mongoose = require('mongoose')
    const Schema = mongoose.Schema

    const productSchema = new Schema({
    name:  String,
    category: String,
    price: Number,
    tags: [String]
    }, { timestamps: true, versionKey: false })

    const ProductModel = mongoose.model('Product', productSchema)

    ProductModel.watch().on('change', data => console.log(new Date(), data)); <<ทุกครั้งที่มีการ add, update, delete จะมี watch() change ทุกครั้ง

step 5 
สามารถใช้งานได้ปกติ
###################### mongodb ######################


/*
  // Convert date to timestamp
  var date = Date.parse(date_transfer.toString());
  console.log(date);

  // Convert timestamp to date
  var date = new Date(date);
  console.log(date);
*/


# cron job
> run  = cron
service cron status
service cron stop
service cron start

# Drupal 8  query datetime BETWEEN, created, changed
$query = \Drupal::entityQuery('node');
$query->condition('type', 'chits');
$now = time();
$last_year = '1589101320';//$now - 60*60*24*365; 
// $query->condition('created', $last_year', '>=');
// $query->condition('changed', $last_year, '>=');
// $query->condition('changed', '1589101440', '<=');
$query->condition('changed', ['1589101320', '1589101440'], 'BETWEEN');
$results = $query->execute();

dpm($results);

// 05/10/2020 - 16:02
$timestamp = strtotime("05/10/2020 16:04");
dpm( $timestamp );

$d = date("Y-m-d H:i:s", $timestamp);
dpm($d);

// reactjs  timestamp to date
https://makitweb.com/convert-unix-timestamp-to-date-time-with-javascript/

# nodejs log mongodb
https://www.techighness.com/post/hook-node-js-console-log-insert-in-mongodb/


Drupal 8-9 (PHP 7)  Try-catch
- try {
            
 } catch (\Throwable $e) {
     \Drupal::logger('SearchApi')->notice($e->__toString());
 }
