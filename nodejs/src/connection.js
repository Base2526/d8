const mongoose = require("mongoose");

const User = require("./User.model");

const connection = "mongodb://mongo:27017/mongo_test";

const connectDb = () => {
  // return mongoose.connect(connection);
  console.log('Mongoose version:', mongoose.version);
  mongoose.set('useUnifiedTopology', true);
  mongoose.set('useNewUrlParser', true);
  // mongoose.connect(connectionString, { useFindAndModify: false });

  return mongoose.connect(connection, { useFindAndModify: false });
};

module.exports = connectDb;