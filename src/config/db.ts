import mysql from 'mysql2/promise';

const db = mysql.createPool({
  host: '127.0.0.1',
  port: 3307,        // <--- TAMBAH BARIS INI (Sebab kita dah tukar port di XAMPP)
  user: 'root',      
  password: '',      
  database: 'putaride',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

export default db;