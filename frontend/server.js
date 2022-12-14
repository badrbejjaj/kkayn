const express = require('express');

const app = express();

app.use(express.static('./dist/kkayn-frontend'));

app.get('/*', (req, res) => {
    res.sendFile('index.html', {root: 'dist/kkayn-frontend'});
});

app.listen(process.env.PORT || 8080);