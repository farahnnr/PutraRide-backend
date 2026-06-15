import express from 'express';
import cors from 'cors';
import authRoutes from './routes/authRoutes';
import db from './config/db'; // Sambungan MySQL

const app = express();
const PORT = 5000;

app.use(cors());
app.use(express.json());

// Jalankan ujian sambungan ke MySQL sebaik sahaja server bermula
db.getConnection()
  .then((connection) => {
    console.log('MySQL / MariaDB berjaya disambungkan!');
    connection.release();
  })
  .catch((err) => {
    console.error('Gagal menyambung ke MySQL. Sila pastikan XAMPP/MySQL anda sudah dihidupkan!', err);
  });

// Laluan API
app.use('/api/auth', authRoutes);

app.listen(PORT, () => {
  console.log(`Server Backend PutraRide sedang berjalan di http://localhost:${PORT}`);
});