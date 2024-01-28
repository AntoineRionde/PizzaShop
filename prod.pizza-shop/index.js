import express from 'express';
import router from './src/routes/router.cjs';

const app = express();
const port = process.env.PORT || 3000;

app.use(express.json());
app.use('/api', router);

app.get('/api/', (req, res) => {
    res.json({'message': 'ok'});
})

app.listen(port, () => {
    console.log(`Server is running on port ${port}`);
});
