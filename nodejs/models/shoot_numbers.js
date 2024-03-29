const mongoose = require('mongoose')
const Schema = mongoose.Schema
const shootNumbersSchema = new Schema({
    round_id: Number,
    numbers: []
}, { timestamps: true, versionKey: false, collection: 'shoot_numbers' })

const ShootNumbersModel = mongoose.model('shoot_numbers', shootNumbersSchema)
module.exports = ShootNumbersModel