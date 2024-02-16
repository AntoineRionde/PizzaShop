import express from 'express';
import path from 'path';
const app = express();

app.use(express.static(path.join('/usr/app/', 'src')));

app.get('/', (req, res) => {
    res.sendFile(path.join('/usr/app/', 'src', 'index.html'));
});

export default app