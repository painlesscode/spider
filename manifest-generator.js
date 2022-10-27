const crypto = require('crypto');
const fs = require('fs');

const hash = crypto.createHash('md5').update(
    fs.readFileSync('./public/css/output.css')
).digest().toString('hex'); 

const manifest = {
    '/css/output.css' : '/css/output.css?id='+hash
}


fs.writeFileSync('./public/mix-manifest.json', JSON.stringify(manifest, null, 4))